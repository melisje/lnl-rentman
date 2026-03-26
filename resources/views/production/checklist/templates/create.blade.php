@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('templates.index') }}">Templates</a></li>
                    <li class="breadcrumb-item active">New Template</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Create Checklist Template</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('templates.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="4">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Template</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection