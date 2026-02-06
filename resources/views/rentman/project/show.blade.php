@extends('layouts.app')

@section('content')
<div class="container">
    <div class="bg-light s rounded-2 p-3">
        <h1>{{ $project->number }} - {{ $project->name }}</h1>

        <table class="table table-sm table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr class="">
                    <th scope="col">{{ __('name') }}</th>
                    <th scope="col">{{ __('value') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach (json_decode($project) as $key => $value)
                <tr>
                    <td scope="row">{{ $key }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection