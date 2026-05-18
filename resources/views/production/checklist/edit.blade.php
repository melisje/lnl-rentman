@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('production.checklist.template.index') }}">Templates</a></li>
                    <li class="breadcrumb-item active">Edit Template</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-warning">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-warning">Edit Checklist: {{ $checklist->name }}</h5>
                    <form action="{{ route('production.checklist.destroy', $checklist) }}" method="POST" onsubmit="return confirm('Delete this entire checklist and all its items?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete Checklist</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="{{ route('production.checklist.update', $checklist) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Checklist Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $checklist->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="4">{{ old('remarks', $checklist->remarks) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('production.checklist.show', $checklist) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-warning">Update Checklist</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection