@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Test Jef - 1</H1>
        @include('layouts.errors')



        <div class="container-fluid mt-4">
            <div class="row bg-dark text-white py-2 fw-bold border-bottom">
                <div class="col col-auto">Weeks</div>
                <div class="col-auto">id</div>
                <div class="col-auto">Project Type</div>
                <div class="col-auto">account</div>
                <div class="col-auto">rm_id</div>
                <div class="col-auto">usage start</div>
                <div class="col">Project</div>
                <div class="col-auto text-center">Light (Uur)</div>
                <div class="col-auto text-center">Sound (Uur)</div>
                <div class="col-auto text-center">Rigging (Uur)</div>
                <div class="col-auto text-center">Checklist (tasks)</div>
            </div>


            @foreach($models as $key => $model)

            <div class="row py-2 border-bottom align-items-center bg-light">
                <div class="col col-auto"><span class="badge bg-secondary">{{ $model->weeks_until_start }}</span></div>
                <div class="col-auto text-muted">{{ $model->id }}</div>
                <div class="col-auto text-muted">{{ $model->projectType->name ?? $model->project_type }}</div>
                <div class="col-auto text-muted">{{ $model->account }}</div>
                <div class="col-auto text-muted">{{ $model->rm_id }}</div>
                <div class="col-auto text-muted">{{ $model->usageperiod_start }}</div>
                <div class="col text-muted">{{ $model->full_display_name }}</div>
                <div class="col-auto text-muted text-center">{{ number_format($model->budgets['light'] ?? 0, 2) }}</div>
                <div class="col-auto text-muted text-center">{{ number_format($model->budgets['sound'] ?? 0, 2) }}</div>
                <div class="col-auto text-muted text-center">{{ number_format($model->budgets['rigging'] ?? 0, 2) }}</div>
                <div class="col-auto text-muted text-center">24/7 (todo)</div>
            </div>

            @endforeach

        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush