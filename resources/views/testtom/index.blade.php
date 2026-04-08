@extends('layouts.app')

@push('styles')
<style testom>
</style>
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Projecten</H1>
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