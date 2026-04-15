@extends('layouts.app')

@push('styles')
<style testom>
</style>
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>{{ $project->name }}</H1>
        @include('layouts.errors')

        <div class="row">
            <div class="col-6">
                <a href="{{ route('testtom.index') }}">Terug naar overzicht</a>
            </div>
            <div class="col-6">
                Aantal functies: {{ $project->projectfunctions->count()}}
            </div>
        </div>


        <table class="table mt-2">
            <thead>
                <tr>
                    <th>{{ __('Function') . __('Name')}}</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projectfunctions as $projectfunction)
                <tr>
                    <td>{{ $projectfunction->name }}</td>
                    <td>{{ $projectfunction->duration }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
@endpush