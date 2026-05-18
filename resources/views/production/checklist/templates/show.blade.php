@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('production.checklist.template.index') }}">Templates</a></li>
                <li class="breadcrumb-item active">{{ $template->name }}</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('production.checklist.template.edit', $template) }}" class="btn btn-sm btn-outline-primary">Edit Name/Remarks</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Checklist Items</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        Add New Item
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Item Name</th>
                                <th>Remarks</th>
                                <th width="150" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($template->items as $item)
                            <tr>
                                <td class="text-muted">{{ $item->sequence }}</td>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td><small class="text-muted">{{ $item->remarks }}</small></td>
                                <td class="text-end">
                                    {{-- Delete Item Form --}}
                                    <form action="{{ route('production.checklist.template-items.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="return confirm('Remove this item?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No items found. Click 'Add New Item' to begin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('production.checklist.templates.partials.modal-add-item')
@endsection