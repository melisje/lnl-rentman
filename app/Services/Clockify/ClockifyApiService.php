<?php

namespace App\Services\Clockify;

use App\Models\Rentman\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Exception;

class ClockifyApiService
{
    protected string $apiKey;
    protected string $workspaceId;
    protected string $baseUrl;

    /**
     * Create a new service instance.
     * Configuration is pulled from config/services.php for better performance and caching.
     */
    public function __construct()
    {
        $this->baseUrl     = config('services.clockify.base_url', 'https://api.clockify.me/api/v1');
        $this->apiKey      = config('services.clockify.key');
        $this->workspaceId = config('services.clockify.workspace_id');
    }

    /**
     * Create a new project in Clockify.
     * @param Project $project The Rentman project model instance.
     * @return array
     * @throws Exception
     */
    public function createProject(Project $project): array
    {
        $url = "{$this->baseUrl}/workspaces/{$this->workspaceId}/projects";

        Log::info("Clockify API: Attempting to create project '{$project->displayname}'", [
            'rentman_id' => $project->rm_id,
            'url' => $url
        ]);

        $response = Http::withHeaders([
            'X-Api-Key' => $this->apiKey,
        ])->post($url, [
            'name'     => "{$project->number}-{$project->displayname}",
            'isPublic' => false,
            // Note: Clockify API v1 'post' for projects usually doesn't accept a custom 'id'.
            // If you want to store the Rentman ID, you might need to use a 'note' or 'client'.
            'note'     => "Rentman ID: {$project->rm_id}",
        ]);

        // Handle successful response
        if ($response->successful()) {
            return $response->json();
        }

        // Handle failure
        $errorMessage = $response->json('message') ?? 'Unknown Clockify API error';
        $errorCode    = $response->status();

        Log::error("Clockify API Failure: {$errorMessage}", [
            'status' => $errorCode,
            'body'   => $response->body()
        ]);

        throw new Exception("Clockify API Error ({$errorCode}): {$errorMessage}");
    }

    /**
     * Get all projects from the workspace.
     * @return array
     */
    public function getAllProjects(): array
    {
        $response = Http::withHeaders(['X-Api-Key' => $this->apiKey])
            ->get("{$this->baseUrl}/workspaces/{$this->workspaceId}/projects", [
                'page-size' => 500 // Maximize page size to get as many as possible
            ]);

        return $response->successful() ? $response->json() : [];
    }

    /**
     * Delete a project by its Clockify ID.
     * Note: Projects must be archived before they can be deleted.
     *
     * @param string $id
     * @return bool
     * @throws Exception
     */
    public function deleteProject(string $id): bool
    {
        $headers = ['X-Api-Key' => $this->apiKey];
        $url = "{$this->baseUrl}/workspaces/{$this->workspaceId}/projects/{$id}";

        // Step 1: Archive the project (Set archived to true)
        $archiveResponse = Http::withHeaders($headers)->put($url, [
            'archived' => true
        ]);

        if (!$archiveResponse->successful()) {
            throw new Exception("Could not archive project {$id} before deletion: " . $archiveResponse->json('message'));
        }

        // Step 2: Delete the project
        $deleteResponse = Http::withHeaders($headers)->delete($url);

        if ($deleteResponse->successful()) {
            return true;
        }

        throw new Exception("Could not delete project {$id}: " . $deleteResponse->json('message'));
    }
}
