@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-3">
                <a href="{{ route('production.project.timeregistration.index') }}" class="text-decoration-none text-muted">&larr; Terug naar overzicht</a>
                <h1 class="h3 mt-2 text-gray-800">Nieuwe Tijdregistratie</h1>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('production.project.timeregistration.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            {{-- Project Zoekveld --}}
                            <div class="col-sm-6">
                                <label for="project_search" class="form-label fw-bold">Project</label>
                                <input type="text" id="project_search" list="projectOptions" class="form-control @error('project_id') is-invalid @enderror" placeholder="Typ projectnaam..." autocomplete="off" required>
                                <input type="hidden" name="project_id" id="project_id">
                                @error('project_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Crewmember Zoekveld --}}
                            <div class="col-sm-6">
                                <label for="crew_search" class="form-label fw-bold">Crewmember</label>
                                <input type="text" id="crew_search" list="crewOptions" class="form-control @error('crewmember_id') is-invalid @enderror" placeholder="Typ naam crewlid..." autocomplete="off" required>
                                <input type="hidden" name="crewmember_id" id="crewmember_id">
                                @error('crewmember_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Budget Type --}}
                        <div class="mb-3">
                            <label for="budget_type" class="form-label">Budget Type</label>
                            <select name="budget_type" id="budget_type" class="form-select @error('budget_type') is-invalid @enderror" required>
                                <option value="" disabled selected>Kies een type...</option>
                                @foreach(['PM', 'Light', 'Sound', 'Rigging'] as $type)
                                <option value="{{ $type }}" {{ old('budget_type')==$type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('budget_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-3">

                            {{-- Starttijd (Nu VERPLICHT met een rood sterretje) --}}
                            <div class="col-sm-6">
                                <label for="start" class="form-label fw-bold">Starttijd <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start" id="start" value="{{ old('start', now()->format('Y-m-d\TH:i')) }}" class="form-control @error('start') is-invalid @enderror" required>
                                @error('start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Eindtijd (Optioneel) --}}
                            <div class="col-sm-6">
                                <label for="end" class="form-label">Eindtijd (Optioneel)</label>
                                <input type="datetime-local" name="end" id="end" value="{{ old('end') }}" class="form-control @error('end') is-invalid @enderror">
                                @error('end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            {{-- Duration (Gewijzigd naar text voor hh:mm invoer) --}}
                            <div class="col">
                                <label for="duration" class="form-label">Duur (u:mm)</label>
                                <input type="text" name="duration" id="duration" value="{{ old('duration') }}" placeholder="Bijv. 2:30 (2 uur en 30 minuten)" class="form-control @error('duration') is-invalid @enderror">
                                <small class="text-muted">Als je de eindtijd leeg laat, wordt deze automatisch berekend op basis van de duur.</small>
                                @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="remarks" class="form-label">Opmerkingen</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('production.project.timeregistration.index') }}" class="btn btn-light border">Annuleren</a>
                            <button type="submit" class="btn btn-primary">Opslaan</button>
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