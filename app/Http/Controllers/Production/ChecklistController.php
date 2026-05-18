<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\Checklist;
use App\Models\Production\ChecklistTemplate;
use App\Models\Rentman\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // We eager load the item count to display it in the overview table
        $checklists = Checklist::withCount('items')->get();

        return view('production.checklist.index', compact('checklists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $account = session('current_account', null);
        $projects = Project::where('account', $account)
            ->select('id', 'number', 'name')
            ->get();

        $templates = ChecklistTemplate::select('id', 'name')->get();

        return view('production.checklist.create', compact('projects', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'project_id'  => 'required|exists:rm_projects,id',
            'template_id' => 'required|exists:prod_checklist_templates,id',
        ]);

        // Start transactie om data-integriteit te bewaken
        DB::transaction(function () use ($request) {
            // 1. Maak de nieuwe checklist aan
            $checklist = Checklist::create([
                'name'       => $request->name,
                'project_id' => $request->project_id,
                // Je kunt hier ook de template_id opslaan als referentie
            ]);

            // 2. Haal alle items op van de gekozen template
            $template = ChecklistTemplate::with('items')->find($request->template_id);

            // 3. Kopieer elk item naar de nieuwe checklist
            foreach ($template->items as $item) {
                $checklist->items()->create([
                    'sequence' => $item->sequence, // of hoe je kolom ook heet
                    'name' => $item->name, // of hoe je kolom ook heet
                    'remarks' => $item->remarks, // of hoe je kolom ook heet
                    // Voeg hier andere velden toe zoals volgorde/sort_order
                ]);
            }
        });

        return redirect()->route('production.checklist.index')->with('success', 'Checklist succesvol aangemaakt met template items!');
    }

    public function store_magweg(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|int',
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        Checklist::create($validated);

        return redirect()->route('production.checklist.index')
            ->with('success', 'Checklist created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Checklist $checklist)
    {
        return view('production.checklist.show',compact('checklist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checklist $checklist)
    {
        return view('production.checklist.edit', compact('checklist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checklist $checklist)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'remarks' => 'nullable|string',
            ]);

        $checklist->update($validated);

        return view('production.checklist.show', compact('checklist'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function addTemplateItemsForm(Checklist $checklist)
    {
        $templates = ChecklistTemplate::select('id', 'name')->get();

        return view('production.checklist.add-template-items', compact('checklist', 'templates'));
    }

    /**
     * Store extra template items for a checklist.
     */
    public function addTemplateItemsStore(Request $request, Checklist $checklist)
    {
        // Validatie van de input, we moeten zeker weten dat er een geldige template_id is
        $request->validate([
            'template_id' => 'required|exists:prod_checklist_templates,id',
        ]);

        $template = ChecklistTemplate::with('items')->find($request->template_id);

        // We starten bij de hoogste sequence van de bestaande items, zodat we netjes achteraan toevoegen
        $nextSequence = $checklist->items()->max('sequence') ?? 0;

        DB::transaction(function () use ($checklist, $template, $nextSequence) {
            foreach ($template->items as $item) {
                // We verhogen de sequence voor elk nieuw item dat we toevoegen
                $nextSequence++;

                // We maken een nieuw item aan voor de checklist, gebaseerd op het template item
                $checklist->items()->create([
                    'sequence' => $nextSequence,
                    'name' => $item->name,
                    'remarks' => $item->remarks,
                ]);
            }
        });

        // Na het toevoegen van de items, redirecten we terug naar de checklist detailpagina
        return redirect()->route('production.checklist.show', $checklist)->with('success', 'Items succesvol toegevoegd van template!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist)
    {
        //
    }
}
