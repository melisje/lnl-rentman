@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container">
  <div class="mx-2 p-3 bg-light rounded-3">
    <H1>Title</H1>
    @include('layouts.errors')

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-9">

          @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif

          @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Fout:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif

          <div class="card shadow border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Webhook Simulator</h5>
              <span class="badge bg-info text-dark">Local Test Mode</span>
            </div>
            <div class="card-body">
              <form action="{{ route('test.webhook.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                  <label for="payload" class="form-label fw-bold">JSON Payload</label>
                  <textarea name="payload" id="payload" class="form-control font-monospace shadow-sm" rows="12" placeholder='{ "event": "order.completed", "data": { "id": 123 } }' style="font-size: 0.9rem; background-color: #f8f9fa;">{{ old('payload') ?? $rawPayload ?? null }}</textarea>
                  <div class="form-text mt-2 text-muted">
                    <i class="bi bi-info-circle"></i> Plak hier de rauwe JSON data die je wilt testen.
                  </div>
                </div>

                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-fill"></i> Verwerk Test Webhook
                  </button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center text-muted small">
              Vergeet niet je CSRF en Middleware te checken als je echte externe calls doet.
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@if(isset($payload) && $payload)
<div class="mt-4">
  <h5>Geparsed resultaat:</h5>
  @dump($payload)
</div>
@endif
@endsection

@push('scripts')
@endpush