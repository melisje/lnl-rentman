@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-4 bg-light rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rentman.accounts.index') }}">{{ __('Accounts') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.rentman.accounts.show', $account->account) }}">{{ $account->account }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-0">{{ __('Edit Account') }}: <span class="text-primary">{{ $account->account }}</span></h1>
            </div>
            <a href="{{ route('admin.rentman.accounts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Back to List') }}
            </a>
        </div>

        @include('layouts.errors')

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.rentman.accounts.update', $account->account) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="account_display" class="form-label fw-bold">{{ __('Account Name') }}</label>
                            <input type="text" id="account_display" class="form-control bg-light" value="{{ $account->account }}" disabled>
                            <small class="text-muted italic">{{ __('The account name acts as a unique identifier and cannot be changed.') }}</small>
                            {{-- We don't need to send the account name in the request as it's in the URL --}}
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="url" class="form-label fw-bold">{{ __('API Base URL') }}</label>
                            <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $account->url) }}" required>
                            @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label for="api_token" class="form-label fw-bold">{{ __('API Token') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-key"></i></span>
                            <textarea name="api_token" id="api_token" rows="3" class="form-control @error('api_token') is-invalid @enderror" placeholder="Enter the Rentman API Token..." required>{{ old('api_token', $account->api_token) }}</textarea>
                        </div>
                        @error('api_token')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="webhook_token" class="form-label fw-bold">{{ __('Webhook Token') }} ({{ __('Optional') }})</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-shield-check"></i></span>
                            <input type="text" name="webhook_token" id="webhook_token" class="form-control @error('webhook_token') is-invalid @enderror" value="{{ old('webhook_token', $account->webhook_token) }}" placeholder="Enter the Webhook secret if applicable">
                        </div>
                        @error('webhook_token')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.rentman.accounts.show', $account->account) }}" class="btn btn-light border">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> {{ __('Update Account Settings') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection