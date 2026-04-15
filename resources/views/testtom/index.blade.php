@extends('layouts.app')

@push('styles')
<style testom>
</style>
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Weekoverzicht</H1>
        <table class="table table-sm table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="4">Huidige week ({{ now()->startOfWeek()->format('d/m') }} – {{ now()->endOfWeek()->format('d/m') }})</th>
                </tr>
                <tr>
                    <th>Nummer</th>
                    <th>Project</th>
                    <th>Van</th>
                    <th>Tot</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($thisWeekProjects as $project)
                <tr>
                    <td><a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->number }}</a></td>
                    <td><a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->name }}</a></td>
                    <td>{{ $project->planperiod_start?->format('d/m') }}</td>
                    <td>{{ $project->planperiod_end?->format('d/m') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-muted">Geen projecten deze week</td></tr>
                @endforelse
            </tbody>
            <thead class="table-secondary">
                <tr>
                    <th colspan="4">Week +1 ({{ now()->addWeek()->startOfWeek()->format('d/m') }} – {{ now()->addWeek()->endOfWeek()->format('d/m') }})</th>
                </tr>
                <tr>
                    <th>Nummer</th>
                    <th>Project</th>
                    <th>Van</th>
                    <th>Tot</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nextWeekProjects as $project)
                <tr>
                    <td><a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->number }}</a></td>
                    <td><a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->name }}</a></td>
                    <td>{{ $project->planperiod_start?->format('d/m') }}</td>
                    <td>{{ $project->planperiod_end?->format('d/m') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-muted">Geen projecten volgende week</td></tr>
                @endforelse
            </tbody>
        </table>
        <H2>Projecten zoeken</H2>
        @include('layouts.errors')

        <form method="GET" action="{{ route('testtom.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Zoek op naam of nummer..."
                    value="{{ $search ?? '' }}">
                <button class="btn btn-outline-secondary" type="submit">Zoeken</button>
                @if($search)
                <a href="{{ route('testtom.index') }}" class="btn btn-outline-danger">X</a>
                @endif
            </div>
        </form>


        <table class="table">
            <thead>
                <tr>
                    <th width="100px">Nummer</th>
                    <th>Naam</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                <tr>
                    <td><a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', [$project, 'search' => $search ?? '']) }}">{{ $project->number }}</a></td>
                    <td><a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', [$project, 'search' => $search ?? '']) }}">{{ $project->name }}</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $projects->links() }}

    </div>
</div>
@endsection

@push('scripts')
@endpush