@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-2 p-4 bg-light rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rentman.customfield.index') }}">{{ __('Custom Fields') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $customField->name }}</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-0">{{ __('Edit Custom Field') }}</h1>
            </div>
            <form action="{{ route('admin.rentman.customfield.destroy', $customField) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                </button>
            </form>
        </div>

        @include('layouts.errors')

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.rentman.customfield.update', $customField->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Rentman Account') }}</label>
                            <input type="text" class="form-control bg-light" value="{{ $customField->account }}" disabled>
                            <input type="hidden" name="account" value="{{ $customField->account }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rm_id" class="form-label fw-bold">{{ __('Rentman ID (rm_id)') }}</label>
                            <input type="number" name="rm_id" id="rm_id" class="form-control @error('rm_id') is-invalid @enderror" value="{{ old('rm_id', $customField->rm_id) }}" required>
                            @error('rm_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">{{ __('Field Name') }}</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customField->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="belongs_to" class="form-label fw-bold">{{ __('Belongs To') }}</label>
                            <input type="text" name="belongs_to" id="belongs_to" class="form-control" value="{{ old('belongs_to', $customField->belongs_to) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label fw-bold">{{ __('Type') }}</label>
                            <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $customField->type) }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="hidden" value="0">
                                <input type="checkbox" name="hidden" value="1" class="form-check-input" id="hidden" {{ old('hidden', $customField->hidden) ? 'checked' : '' }}>
                                <label class="form-check-label" for="hidden">{{ __('Hidden') }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="private" value="0">
                                <input type="checkbox" name="private" value="1" class="form-check-input" id="private" {{ old('private', $customField->private) ? 'checked' : '' }}>
                                <label class="form-check-label" for="private">{{ __('Private') }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="mandatory" value="0">
                                <input type="checkbox" name="mandatory" value="1" class="form-check-input" id="mandatory" {{ old('mandatory', $customField->mandatory) ? 'checked' : '' }}>
                                <label class="form-check-label" for="mandatory">{{ __('Mandatory') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <a href="{{ route('admin.rentman.customfield.index') }}" class="btn btn-light border">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('Update Field') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection