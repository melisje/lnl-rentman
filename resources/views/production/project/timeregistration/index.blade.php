@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Project Tijdregistraties</h1>
        <a href="{{ route('production.project.timeregistration.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg"></i> + Nieuwe Registratie
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">ID</th>
                        <th scope="col">Project</th>
                        <th scope="col">Crewmember</th>
                        <th scope="col">Budget Type</th>
                        <th scope="col">Duur (Uur)</th>
                        <th scope="col" class="text-end pe-4">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                    <tr>
                        <td class="ps-4">{{ $registration->id }}</td>
                        <td class="fw-bold text-dark">{{ $registration->project->full_displayname ?? $registration->project_id }}</td>
                        <td>{{ $registration->crewmember->displayname ?? $registration->crewmember_id }}</td>
                        <td>
                            <span class="badge
                                    {{ $registration->budget_type === 'PM' ? 'bg-dark text-purple-dark' : '' }}
                                    {{ $registration->budget_type === 'light' ? 'bg-warning text-dark' : '' }}
                                    {{ $registration->budget_type === 'sound' ? 'bg-info text-dark' : '' }}
                                    {{ $registration->budget_type === 'rigging' ? 'bg-danger text-white' : '' }}
                                ">
                                {{ $registration->budget_type }}
                            </span>
                        </td>
                        <td>
                            {{ $registration->duration ?? 'Niet berekend' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('production.project.timeregistration.show', $registration->id) }}" class="btn btn-outline-primary">Bekijk</a>
                                <a href="{{ route('production.project.timeregistration.edit', $registration->id) }}" class="btn btn-outline-warning">Bewerk</a>

                                <form action="{{ route('production.project.timeregistration.destroy', $registration->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Weet je zeker dat je deze registratie wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">Verwijder</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Er zijn nog geen tijdregistraties gevonden.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Optionele CSS toevoeging in je stylesheet voor de paarse badge die Bootstrap standaard mist: --}}
<style>
    .bg-purple {
        bg-color: #e0cffc;
        color: #6f42c1;
    }
</style>
@endsection