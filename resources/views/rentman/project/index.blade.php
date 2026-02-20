@extends('layouts.app') {{-- Pas dit aan naar jouw base template --}}

@section('content')
<div class="container-fluid mt-1">
    <div class="p-5 mb-4 bg-body-secondary rounded-3 ">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ __('projects') }} </h2>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('rentman.projects.index') }}" method="GET" class="row g-3 align-items-end">

                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Account</label>
                        <select name="account_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all" {{ $accountFilter=='all' ? 'selected' : '' }}>Alle</option>
                            <option value="llstageservice" {{ $accountFilter=='llstageservice' ? 'selected' : '' }}>LL Stage</option>
                            <option value="ledvisions" {{ $accountFilter=='ledvisions' ? 'selected' : '' }}>Ledvisions</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Zoeken</label>
                        <input type="text" name="search" class="form-control form-select-sm" placeholder="Naam of nummer..." value="{{ $search }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Items</label>
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach([2, 10, 15, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ $perPage==$size ? 'selected' : '' }}>{{ $size }} per pag.</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('rentman.projects.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover border ">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Account</th>
                        <th>Number</th>
                        <th>Naam</th>
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
                        <td>{{ $project->rm_id }}</td>
                        <td>{{ $project->account }}</td>
                        <td><strong>{{ $project->number }}</strong></td>
                        <td><strong>{{ $project->name }}</strong></td>
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