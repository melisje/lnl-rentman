@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-3">
                <a href="{{ route('production.project.timeregistration.index') }}" class="text-decoration-none text-muted">&larr; Terug naar overzicht</a>
                <h1 class="h3 mt-2 text-gray-800">Registratie #{{ $timeregistration->id }} Bewerken</h1>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('production.project.timeregistration.update', $timeregistration->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            {{-- Project Zoekveld --}}
                            <div class="col-sm-6">
                                <label for="project_search" class="form-label fw-bold">Project</label>
                                <input type="text" id="project_search" list="projectOptions" value="{{ old('project_search', $timeregistration->project->full_displayname ?? '') }}" class="form-control" placeholder="Typ projectnaam..." autocomplete="off" required>
                                <input type="hidden" name="project_id" id="project_id" value="{{ old('project_id', $timeregistration->project_id) }}">
                            </div>

                            {{-- Crewmember Zoekveld (Gecorrigeerd naar displayname) --}}
                            <div class="col-sm-6">
                                <label for="crew_search" class="form-label fw-bold">Crewmember</label>
                                <input type="text" id="crew_search" list="crewOptions" value="{{ old('crew_search', $timeregistration->crewmember->displayname ?? '') }}" class="form-control" placeholder="Typ naam crewlid..." autocomplete="off" required>
                                <input type="hidden" name="crewmember_id" id="crewmember_id" value="{{ old('crewmember_id', $timeregistration->crewmember_id) }}">
                            </div>
                        </div>

                        {{-- Budget Type --}}
                        <div class="mb-3">
                            <label for="budget_type" class="form-label">Budget Type</label>
                            <select name="budget_type" id="budget_type" class="form-select" required>
                                @foreach(['PM', 'Light', 'Sound', 'Rigging'] as $type)
                                <option value="{{ $type }}" {{ strtolower(old('budget_type', $timeregistration->budget_type)) === strtolower($type) ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="start" class="form-label">Starttijd</label>
                                <input type="datetime-local" name="start" id="start" value="{{ old('start', $timeregistration->start ? $timeregistration->start->format('Y-m-d\TH:i') : '') }}" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label for="end" class="form-label">Eindtijd</label>
                                <input type="datetime-local" name="end" id="end" value="{{ old('end', $timeregistration->end ? $timeregistration->end->format('Y-m-d\TH:i') : '') }}" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Directe Duur (Uren)</label>
                            <input type="number" step="0.01" name="duration" id="duration" value="{{ old('duration', $timeregistration->duration) }}" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label for="remarks" class="form-label">Opmerkingen</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ old('remarks', $timeregistration->remarks) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('production.project.timeregistration.index') }}" class="btn btn-light border">Annuleren</a>
                            <button type="submit" class="btn btn-warning text-dark fw-semibold">Wijzigingen Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<datalist id="projectOptions">
    @foreach($projects as $project)
    <option data-id="{{ $project->id }}" value="{{ $project->full_displayname }}"></option>
    @endforeach
</datalist>

{{-- Gecorrigeerd naar displayname --}}
<datalist id="crewOptions">
    @foreach($crewmembers as $crew)
    <option data-id="{{ $crew->id }}" value="{{ $crew->displayname }}"></option>
    @endforeach
</datalist>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupDatalistLink(searchId, hiddenId, listId) {
            const searchInput = document.getElementById(searchId);
            if (!searchInput) return;

            searchInput.addEventListener('input', function(e) {
                const input = e.target;
                const options = document.querySelectorAll('#' + listId + ' option');
                const hiddenInput = document.getElementById(hiddenId);
                const inputValue = input.value;

                hiddenInput.value = "";

                for(let i = 0; i < options.length; i++) {
                    if(options[i].value === inputValue) {
                        hiddenInput.value = options[i].getAttribute('data-id');
                        break;
                    }
                }
            });
        }

        setupDatalistLink('project_search', 'project_id', 'projectOptions');
        setupDatalistLink('crew_search', 'crewmember_id', 'crewOptions');
    });
</script>
@endpush