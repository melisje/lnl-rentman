@extends('layouts.app')

@push('styles')
<style testom>
</style>
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Test Tom</H1>
        @include('layouts.errors')

        {{ $project->projectfunctions->count()}}

        <table>
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