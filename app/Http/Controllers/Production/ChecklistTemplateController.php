<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\ChecklistTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChecklistTemplateController extends Controller
{
    public function index(): Response
    {
        $templates = ChecklistTemplate::withCount('items')->get()->map(fn($t) => [
            'id'         => $t->id,
            'name'       => $t->name,
            'remarks'    => $t->remarks,
            'items_count'=> $t->items_count,
            'created_at' => $t->created_at->format('d-m-Y'),
        ]);

        return Inertia::render('Production/ChecklistTemplate/Index', compact('templates'));
    }

    public function create(): Response
    {
        return Inertia::render('Production/ChecklistTemplate/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        ChecklistTemplate::create($request->only('name', 'remarks'));

        return redirect()->route('production.checklist.template.index')
            ->with('success', 'Template aangemaakt.');
    }

    public function show(ChecklistTemplate $template): Response
    {
        $template->load(['items' => fn($q) => $q->orderBy('sequence')]);

        return Inertia::render('Production/ChecklistTemplate/Show', [
            'template' => [
                'id'      => $template->id,
                'name'    => $template->name,
                'remarks' => $template->remarks,
                'items'   => $template->items->map(fn($i) => [
                    'id'       => $i->id,
                    'name'     => $i->name,
                    'remarks'  => $i->remarks ?? '',
                    'sequence' => $i->sequence,
                ])->values(),
            ],
        ]);
    }

    public function edit(ChecklistTemplate $template): Response
    {
        return Inertia::render('Production/ChecklistTemplate/Edit', [
            'template' => $template->only('id', 'name', 'remarks'),
        ]);
    }

    public function update(Request $request, ChecklistTemplate $template): RedirectResponse
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $template->update($request->only('name', 'remarks'));

        return redirect()->route('production.checklist.template.show', $template)
            ->with('success', 'Template bijgewerkt.');
    }

    public function destroy(ChecklistTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('production.checklist.template.index')
            ->with('success', 'Template verwijderd.');
    }
}
