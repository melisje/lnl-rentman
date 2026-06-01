@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-3">
                <a href="{{ route('production.project.timeregistration.index') }}" class="text-decoration-none text-muted">&larr; Terug naar overzicht</a>
                <h1 class="h3 mt-2 text-gray-800">Details Registratie #{{ $timeregistration->id }}</h1>
            </div>

            <div class="card shadow-sm overflow-hidden">
                <div class="card-body p-4">
                    <div class="row g-3 border-b mb-3 pb-3">
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block">Project</small>
                            <span class="fs-5 text-dark">{{ $timeregistration->project->full_displayname ?? $timeregistration->project_id }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block">Crewmember</small>
                            <span class="fs-5 text-dark">{{ $timeregistration->crewmember->displayname ?? $timeregistration->crewmember_id }}</span>
                        </div>
                    </div>

                    <div class="row g-3 border-b mb-3 pb-3">
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block mb-1">Budget Type</small>
                            <span class="badge bg-primary fs-7">{{ $timeregistration->budget_type }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block">Geregistreerde Duur</small>
                            <span class="fs-5 fw-bold text-dark">{{ $timeregistration->duration ?? 'Onbekend' }} uur</span>
                        </div>
                    </div>

                    <div class="row g-3 border-b mb-3 pb-3">
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block">Starttijd</small>
                            <span class="text-dark">{{ $timeregistration->start ? $timeregistration->start->format('d-m-Y H:i') : 'Nvt' }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block">Eindtijd</small>
                            <span class="text-dark">{{ $timeregistration->end ? $timeregistration->end->format('d-m-Y H:i') : 'Nvt' }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted text-uppercase fw-semibold d-block">Opmerkingen</small>
                        <p class="text-dark bg-light p-3 rounded mt-1 text-break" style="white-space: pre-line;">
                            {{ $timeregistration->remarks ?? 'Geen opmerkingen ingevoerd.' }}
                        </p>
                    </div>

                    <div class="row g-2 bg-light rounded p-2 text-muted" style="font-size: 0.75rem;">
                        <div class="col-sm-6">Aangemaakt: {{ $timeregistration->created_at ? $timeregistration->created_at->format('d-m-Y H:i:s') : 'Onbekend' }}</div>
                        <div class="col-sm-6 text-sm-end">Gewijzigd: {{ $timeregistration->modified_at ? $timeregistration->modified_at->format('d-m-Y H:i:s') : 'Nvt' }}</div>
                    </div>
                </div>

                <div class="card-footer bg-light px-4 py-3 d-flex justify-content-end">
                    <a href="{{ route('production.project.timeregistration.edit', $timeregistration->id) }}" class="btn btn-warning text-dark fw-semibold">
                        Bewerken
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-b {
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endsection