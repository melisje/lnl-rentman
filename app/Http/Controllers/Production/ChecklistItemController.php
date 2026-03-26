<?php

namespace App\Http\Controllers\Production;

use App\Models\Production\ChecklistItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Production\Checklist;

class ChecklistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Checklist $checklist)
    {
        // Maak een nieuw leeg item aan met de hoogste sequence + 1
        $nextSequence = $checklist->items()->max('sequence') + 1;

        $item = $checklist->items()->create([
            'content' => 'Nieuw item',
            'sequence' => $nextSequence,
            'is_completed' => false
        ]);

        return response()->json($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChecklistItem $checklistItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChecklistItem $checklistItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChecklistItem $item)
    {
        $validated = $request->validate([
            'is_completed' => 'required|boolean',
            'name'      => 'nullable|string|max:255',
            'remarks'      => 'nullable|string|max:500',
        ]);

        $item->update($validated);

        return response()->json(['success' => true]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChecklistItem $item)
    {
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        foreach ($request->orders as $order) {
            ChecklistItem::where('id', $order['id'])->update(['sequence' => $order['sequence']]);
        }
        return response()->json(['success' => true]);
    }
}
