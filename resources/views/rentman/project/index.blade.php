@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Projects</H1>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">xxxx</th>
                        <th scope="col">number</th>
                        <th scope="col">name</th>
                        <th scope="col">Reference</th>
                        <th scope="col">project type</th>
                        <th scope="col">location</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $key => $project)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('projects.show', $project->id) }}">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </td>
                        <td class="text-center">{{ $project->number }} </td>
                        <td>{{ $project->name }} </td>
                        <td>{{ $project->reference }} </td>
                        <td>{{ $project->project_type }} </td>
                        <td>{{ $project->location }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection