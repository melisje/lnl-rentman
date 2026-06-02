<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\Checklist;
use App\Models\Production\ChecklistTemplate;
use App\Models\Rentman\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChecklistController extends Controller
{
    public function index(Request $request)
    {
        $query = Checklist::withCount('items')->with('project:id,name,number');

        if ($request->has('project_id')) {
            $query->where('project_id', $request->get('project_id'));
        }

        $checklists = $query->get()->map(fn($c) => [
            'id'               => $c->id,
            'name'             => $c->name,
            'project_name'     => $c->project?->full_display_name ?? $c->project?->name,
            'items_count'      => $c->items_count,
            'completed_count'  => $c->countCompletedItems(),
            'status_color'     => $c->status_color,
            'status_percentage'=> $c->status_percentage,
        ]);

        return Inertia::render('Production/Checklist/Index', compact('checklists'));
    }

    public function create()
    {
        $account   = session('current_account', null);
        $projects  = Project::where('account', $account)->select('id', 'number', 'name')->get();
        $templates = ChecklistTemplate::select('id', 'name')->get();

        return Inertia::render('Production/Checklist/Create', compact('projects', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'project_id'  => 'required|exists:rm_projects,id',
            'template_id' => 'required|exists:prod_checklist_templates,id',
        ]);

        DB::transaction(function () use ($request) {
            $checklist = Checklist::create([
                'name'       => $request->name,
                'project_id' => $request->project_id,
            ]);

            $template = ChecklistTemplate::with('items')->find($request->template_id);

            foreach ($template->items as $item) {
                $checklist->items()->create([
                    'sequence' => $item->sequence,
                    'name'     => $item->name,
                    'remarks'  => $item->remarks,
                ]);
            }
        });

        return redirect()->route('production.checklist.index')
            ->with('success', 'Checklist succesvol aangemaakt!');
    }

    public function show(Checklist $checklist)
    {
        $checklist->load(['items' => fn($q) => $q->orderBy('sequence'), 'project:id,name']);

        return Inertia::render('Production/Checklist/Show', [
            'checklist' => [
                'id'           => $checklist->id,
                'name'         => $checklist->name,
                'remarks'      => $checklist->remarks,
                'project_name' => $checklist->project?->name,
                'items'        => $checklist->items->map(fn($i) => [
                    'id'           => $i->id,
                    'name'         => $i->name ?? '',
                    'remarks'      => $i->remarks ?? '',
                    'is_completed' => (bool) $i->is_completed,
                    'sequence'     => $i->sequence,
                ])->values(),
            ],
        ]);
    }

    public function edit(Checklist $checklist)
    {
        return Inertia::render('Production/Checklist/Edit', [
            'checklist' => $checklist->only('id', 'name', 'remarks'),
        ]);
    }

    public function update(Request $request, Checklist $checklist)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $checklist->update($request->only('name', 'remarks'));

        return redirect()->route('production.checklist.show', $checklist);
    }

    public function addTemplateItemsForm(Checklist $checklist)
    {
        $templates = ChecklistTemplate::select('id', 'name')->get();

        return Inertia::render('Production/Checklist/AddTemplateItems', [
            'checklist' => $checklist->only('id', 'name'),
            'templates' => $templates,
        ]);
    }

    public function addTemplateItemsStore(Request $request, Checklist $checklist)
    {
        $request->validate([
            'template_id' => 'required|exists:prod_checklist_templates,id',
        ]);

        $template     = ChecklistTemplate::with('items')->find($request->template_id);
        $nextSequence = $checklist->items()->max('sequence') ?? 0;

        DB::transaction(function () use ($checklist, $template, &$nextSequence) {
            foreach ($template->items as $item) {
                $nextSequence++;
                $checklist->items()->create([
                    'sequence' => $nextSequence,
                    'name'     => $item->name,
                    'remarks'  => $item->remarks,
                ]);
            }
        });

        return redirect()->route('production.checklist.show', $checklist)
            ->with('success', 'Items succesvol toegevoegd!');
    }

    public function destroy(Checklist $checklist)
    {
        $checklist->delete();

        return redirect()->route('production.checklist.index')
            ->with('success', 'Checklist verwijderd.');
    }
}
