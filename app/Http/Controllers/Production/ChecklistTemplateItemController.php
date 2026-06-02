<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\ChecklistTemplate;
use App\Models\Production\ChecklistTemplateItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ChecklistTemplateItemController extends Controller
{
    /**
     * Store a newly created item for a specific template.
     * Route: templates.items.store
     */
    public function store(Request $request, ChecklistTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'sequence' => 'nullable|integer',
        ]);

        if (!$request->filled('sequence')) {
            $validated['sequence'] = ($template->items()->max('sequence') ?? 0) + 1;
        }

        $item = $template->items()->create($validated);

        if ($request->wantsJson()) {
            return response()->json($item);
        }

        return redirect()->route('production.checklist.template.show', $template)
            ->with('success', 'Item toegevoegd.');
    }

    /**
     * Update the specified item in storage.
     * Route: template-items.update (Shallow)
     */
    public function update(Request $request, ChecklistTemplateItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'sequence' => 'required|integer',
        ]);

        $item->update($validated);

        // Redirect back to the parent template's show page
        return redirect()->route('production.checklist.template.show', $item->template_id)
            ->with('success', 'Template item updated.');
    }

    /**
     * Remove the specified item from storage.
     * Route: template-items.destroy (Shallow)
     */
    public function destroy(ChecklistTemplateItem $item): RedirectResponse
    {
        $templateId = $item->template_id;

        $item->delete();

        return redirect()->route('production.checklist.template.show', $templateId)
            ->with('success', 'Item removed from template.');
    }
}
