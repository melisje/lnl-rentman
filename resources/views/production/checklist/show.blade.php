@extends('layouts.app')

@push('styles')
<style>
    /* Maak de inputs onzichtbaar totdat je erop focust */
    .bg-transparent {
        border: 1px solid transparent !important;
        transition: all 0.2s ease;
    }

    .bg-transparent:focus {
        background-color: #fff !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    /* De sleep-cursor voor de grip handle */
    .cursor-move {
        cursor: grab;
    }

    .cursor-move:active {
        cursor: grabbing;
    }

    /* Highlight de rij die wordt verplaatst */
    .sortable-ghost {
        opacity: 0.4;
        background-color: #f8f9fa !important;
    }

    /* Verwijder de default blauwe gloed van checkboxes voor een rustiger beeld */
    .form-check-input:focus {
        box-shadow: none;
    }

    /* Hover effect op de rij om delete/drag handles te accentueren */
    #sortable-items tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <h1>{{ $checklist->name }} </h1>
        <span class="badge text-bg-primary fs-6">Project: {{ $checklist->project->name ?? 'Geen project gekoppeld' }}</span>
        <div class="text-secondary ">{{ $checklist->remarks }}</div>
        @include('layouts.errors')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Checklist Items</h5>

                <a href="{{ route('production.checklist.edit', $checklist->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit Checklist
                </a>
                <a href="{{ route('production.checklist.add-template-items.form', $checklist->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Add Template Items
                </a>
                <button class="btn btn-sm btn-success" id="add-item-btn">
                    <i class="bi bi-plus-lg"></i> Item toevoegen
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;"></th>
                            <th style="width: 50px;">Ok</th>
                            <th>Omschrijving</th>
                            <th>Opmerkingen</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="sortable-items">
                        @foreach($checklist->items->sortBy('sequence') as $item)
                        <tr data-id="{{ $item->id }}" class="align-middle">
                            <td class="text-muted cursor-move handle">
                                <i class="bi bi-grip-vertical"></i>
                            </td>
                            <td>
                                <input type="checkbox" class="form-check-input item-checkbox" {{ $item->is_completed ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm border-0 bg-transparent item-content" value="{{ $item->name }}">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm border-0 bg-transparent item-remarks" value="{{ $item->remarks }}" placeholder="...">
                            </td>
                            <td class="text-end">
                                <button class="btn btn-link text-danger p-0 delete-item-btn">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('sortable-items');
    const checklistId = "{{ $checklist->id }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // --- 1. FUNCTIE: AUTOSAVE ---
    async function saveItemData(row) {
        const itemId = row.getAttribute('data-id'); // Gecorrigeerd: was data-item-id
        const isCompleted = row.querySelector('.item-checkbox').checked;
        const remarks = row.querySelector('.item-remarks').value;
        const name = row.querySelector('.item-content').value;

        try {
            const response = await fetch(`/production/checklist-items/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    is_completed: isCompleted ? 1 : 0,
                    remarks: remarks,
                    name: name
                })
            });

            if (response.ok) {
                // Subtiele feedback: rij kleurt heel even groen
                row.style.transition = 'background-color 0.5s';
                row.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
                setTimeout(() => row.style.backgroundColor = 'transparent', 500);
            }
        } catch (error) {
            console.error('Fout bij opslaan:', error);
        }
    }

    // --- 2. SLEPEN (SORTABLE) ---
    new Sortable(el, {
        handle: '.handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: async function () {
            let orders = [];
            document.querySelectorAll('#sortable-items tr').forEach((row, index) => {
                orders.push({ id: row.dataset.id, sequence: index + 1 });
            });

            await fetch('{{ route("production.checklist.item.reorder") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ orders: orders })
            });
        }
    });

    // --- 3. EVENT DELEGATION (Voor Checkbox, Remarks & Delete) ---
    el.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-checkbox')) {
            saveItemData(e.target.closest('tr'));
        }
    });

    el.addEventListener('blur', function(e) {
        if (e.target.classList.contains('item-remarks') || e.target.classList.contains('item-content')) {
            saveItemData(e.target.closest('tr'));
        }
    }, true); // True is nodig voor blur events op een parent container

    // --- 4. TOEVOEGEN ---
    document.getElementById('add-item-btn').addEventListener('click', async function() {
        const response = await fetch(`/production/checklist/${checklistId}/items`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        if (response.ok) {
            location.reload(); // Herlaad om de nieuwe rij met ID en juiste sortering te tonen
        }
    });

    // --- 5. VERWIJDEREN ---
    el.addEventListener('click', async function(e) {
        const deleteBtn = e.target.closest('.delete-item-btn');
        if (deleteBtn) {
            if (!confirm('Item verwijderen?')) return;
            const row = deleteBtn.closest('tr');
            const itemId = row.dataset.id;

            const response = await fetch(`/production/checklist-items/${itemId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            if (response.ok) row.remove();
        }
    });
});
</script>
@endpush