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
                        <li class="breadcrumb-item active" aria-current="page">{{ $account->account }}</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-0">{{ __('Account Details') }}: <span class="text-primary">{{ $account->account }}</span></h1>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.rentman.accounts.edit', $account->account) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil-fill me-1"></i> {{ __('Edit') }}
                </a>
                <a href="{{ route('admin.rentman.accounts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-dark text-white font-weight-bold">
                        {{ __('General Information') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="ps-0" style="width: 35%;">{{ __('Account Name') }}:</th>
                                <td><span class="badge bg-primary fs-6">{{ $account->account }}</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0">{{ __('API Base URL') }}:</th>
                                <td><code>{{ $account->url }}</code></td>
                            </tr>
                            <tr>
                                <th class="ps-0">{{ __('Created At') }}:</th>
                                <td>{{ $account->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">{{ __('Last Updated') }}:</th>
                                <td>{{ $account->updated_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-header bg-info text-dark font-weight-bold">
                        {{ __('API & Connectivity') }}
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small uppercase">{{ __('API Access Token') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-sm bg-white" value="{{ $account->api_token }}" readonly id="apiTokenField">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="toggleSecret('apiTokenField')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold text-muted small uppercase">{{ __('Webhook Secret') }}</label>
                            @if($account->webhook_token)
                            <div class="input-group">
                                <input type="password" class="form-control form-control-sm bg-white" value="{{ $account->webhook_token }}" readonly id="webhookTokenField">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="toggleSecret('webhookTokenField')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @else
                            <div class="alert alert-light border text-muted py-2 px-3 mb-0 small">
                                <i class="bi bi-info-circle me-1"></i> {{ __('No webhook token configured.') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-2">
            <div class="card border-danger">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="card-title text-danger mb-0">{{ __('Danger Zone') }}</h5>
                        <p class="card-text small text-muted mb-0">{{ __('Removing this account will stop all synchronization processes for this client.') }}</p>
                    </div>
                    <form action="{{ route('admin.rentman.accounts.destroy', $account->account) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> {{ __('Delete Account') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleSecret(fieldId) {
        const field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
        } else {
            field.type = "password";
        }
    }
</script>
@endpush