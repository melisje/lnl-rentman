@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-2 p-4 bg-light rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rentman.customfield.index') }}">{{ __('Custom Fields') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Add New Field') }}</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-0">{{ __('Add Custom Field') }}</h1>
            </div>
            <a href="{{ route('admin.rentman.customfield.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
            </a>
        </div>

        @include('layouts.errors')

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.rentman.customfield.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="account" class="form-label fw-bold">{{ __('Rentman Account') }}</label>
                            <select name="account" id="account" class="form-select @error('account') is-invalid @enderror" required>
                                <option value="">-- {{ __('Select Account') }} --</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->account }}" {{ old('account')==$acc->account ? 'selected' : '' }}>
                                    {{ $acc->account }}
                                </option>
                                @endforeach
                            </select>
                            @error('account')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rm_id" class="form-label fw-bold">{{ __('Rentman ID (rm_id)') }}</label>
                            <input type="number" name="rm_id" id="rm_id" class="form-control @error('rm_id') is-invalid @enderror" value="{{ old('rm_id') }}" required>
                            @error('rm_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">{{ __('Field Name') }}</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="belongs_to" class="form-label fw-bold">{{ __('Belongs To') }}</label>
                            <input type="text" name="belongs_to" id="belongs_to" class="form-control" value="{{ old('belongs_to') }}" placeholder="e.g. project, equipment">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label fw-bold">{{ __('Type') }}</label>
                            <input type="text" name="type" id="type" class="form-control" value="{{ old('type') }}" placeholder="e.g. text, dropdown">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="hidden" value="0">
                                <input type="checkbox" name="hidden" value="1" class="form-check-input" id="hidden" {{ old('hidden') ? 'checked' : '' }}>
                                <label class="form-check-label" for="hidden">{{ __('Hidden') }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="private" value="0">
                                <input type="checkbox" name="private" value="1" class="form-check-input" id="private" {{ old('private') ? 'checked' : '' }}>
                                <label class="form-check-label" for="private">{{ __('Private') }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="mandatory" value="0">
                                <input type="checkbox" name="mandatory" value="1" class="form-check-input" id="mandatory" {{ old('mandatory') ? 'checked' : '' }}>
                                <label class="form-check-label" for="mandatory">{{ __('Mandatory') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <a href="{{ route('admin.rentman.customfield.index') }}" class="btn btn-light border">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('Create Field') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection