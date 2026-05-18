@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Checklist Templates</h1>
        <a href="{{ route('production.checklist.template.create') }}" class="btn btn-primary">Create New Template</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Items</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td><strong>{{ $template->name }}</strong></td>
                        <td><span class="badge bg-info">{{ $template->items_count }} items</span></td>
                        <td>{{ $template->created_at->format('d-m-Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('production.checklist.template.show', $template) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('production.checklist.template.edit', $template) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('production.checklist.template.destroy', $template) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection