@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Checklist Templates</h1>
        <a href="{{ route('production.checklist.create') }}" class="btn btn-primary">Create New Checklist</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Project</th>
                        <th>Name</th>
                        <th>Items</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checklists as $checklist)
                    <tr>
                        <td><strong>{{ $checklist->project?->full_display_name }}</strong></td>
                        <td><strong>{{ $checklist->name }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($checklist->items_count > 0)
                                <span class="badge bg-{{ $checklist->status_color }} me-2">
                                    {{ $checklist->countCompletedItems() }} / {{ $checklist->items_count }}
                                    <small class="ms-2 fw-bold">{{ $checklist->status_percentage }}%</small>
                                </span>
                                @else
                                <span class="badge fw-bold bg-{{ $checklist->status_color }}  me-2">
                                    <small class="text-white italic">Geen checks</small>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill me-1"></i> Acties
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('production.checklist.show', $checklist) }}">
                                            <i class="bi bi-eye text-secondary me-2"></i> Bekijken
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('production.checklist.edit', $checklist) }}">
                                            <i class="bi bi-pencil text-primary me-2"></i> Bewerken
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>
                                        <form action="{{ route('production.checklist.destroy', $checklist) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze checklist wilt verwijderen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Verwijderen
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection