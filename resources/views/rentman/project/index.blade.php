@extends('layouts.app') {{-- Pas dit aan naar jouw base template --}}

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Projecten</h2>
        <a href="#" class="btn btn-primary">Nieuw Project</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('rentman.projects.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Zoek op projectnaam..." value="{{ $search }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-secondary">Filteren</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover border">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Account</th>
                    <th>Naam</th>
                    <th>Number</th>
                    <th>Status</th>
                    <th>AM</th>
                    <th>PM</th>
                    <th>Aangemaakt op</th>
                    <th class="text-end">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->account }}</td>
                    <td><strong>{{ $project->name }}</strong></td>
                    <td><strong>{{ $project->number }}</strong></td>
                    <td><strong>{{ $project->status_name }}</strong></td>
                    <td><strong>{{ $project->am_name }}</strong></td>
                    <td><strong>{{ $project->pm_name }}</strong></td>
                    <td>{{ $project->created_at->format('d-m-Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('rentman.projects.show', $project->id) }}" class="btn btn-sm btn-info">Bekijk</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Geen projecten gevonden.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $projects->links() }}
    </div>
</div>
@endsection