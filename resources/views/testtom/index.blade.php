@extends('layouts.app')

@push('styles')
<style testom>
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <div class="row">
            <div class="col-8">
                <H1>Weekoverzicht</H1>
            </div>
            <div class="col-4 mt-1">
                <form method="GET" action="{{ route('testtom.search') }}" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Zoek op naam of nummer..."
                            value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit">Zoeken</button>

                    </div>
                </form>
            </div>
        </div>

        <table class="table table-sm table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="4">Huidige week ({{ now()->startOfWeek()->format('d/m') }} – {{ now()->endOfWeek()->format('d/m') }})</th>
                </tr>
                <tr>
                    <th>Project</th>
                    <th width="50px">Van</th>
                    <th width="50px">Tot</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($thisWeekProjects as $project)
                <tr>
                    <td>
                        [<a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->number }}</a>]
                        <a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->name }}</a>
                    </td>
                    <td>{{ $project->planperiod_start?->format('d/m') }}</td>
                    <td>{{ $project->planperiod_end?->format('d/m') }}</td>
                    <td>{{ $project->account }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-muted">Geen projecten deze week</td>
                </tr>
                @endforelse
            </tbody>
            <thead class="table-secondary">
                <tr>
                    <th colspan="4">Week +1 ({{ now()->addWeek()->startOfWeek()->format('d/m') }} – {{ now()->addWeek()->endOfWeek()->format('d/m') }})</th>
                </tr>
                <tr>
                    <th>Project</th>
                    <th>Van</th>
                    <th>Tot</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nextWeekProjects as $project)
                <tr>
                    <td>
                        [<a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->number }}</a>]
                        <a class="text-decoration-none" href="{{ route('testtom.projectfunctions.index', $project) }}">{{ $project->name }}</a>
                    </td>
                    <td>{{ $project->planperiod_start?->format('d/m') }}</td>
                    <td>{{ $project->planperiod_end?->format('d/m') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-muted">Geen projecten volgende week</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection

@push('scripts')
@endpush