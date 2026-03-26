<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\ChecklistTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChecklistTemplateController extends Controller
{
    /**
     * Display a listing of the checklist templates.
     */
    public function index(): View
    {
        // We eager load the item count to display it in the overview table
        $templates = ChecklistTemplate::withCount('items')->get();

        return view('production.checklist.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new checklist template.
     */
    public function create(): View
    {
        return view('production.checklist.templates.create');
    }

    /**
     * Store a newly created checklist template in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        ChecklistTemplate::create($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified checklist template with its items.
     */
    public function show(ChecklistTemplate $template): View
    {
        // Load items ordered by their sequence number
        $template->load(['items' => function ($query) {
            $query->orderBy('sequence', 'asc');
        }]);

        return view('production.checklist.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the checklist template header.
     */
    public function edit(ChecklistTemplate $template): View
    {
        return view('production.checklist.templates.edit', compact('template'));
    }

    /**
     * Update the specified checklist template in storage.
     */
    public function update(Request $request, ChecklistTemplate $template): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $template->update($validated);

        return redirect()->route('templates.show', $template)
            ->with('success', 'Template header updated successfully.');
    }

    /**
     * Remove the specified checklist template from storage.
     */
    public function destroy(ChecklistTemplate $template): RedirectResponse
    {
        // Because of the 'onDelete(cascade)' in the migration,
        // all linked items will be deleted automatically.
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template and all its items deleted.');
    }
}
