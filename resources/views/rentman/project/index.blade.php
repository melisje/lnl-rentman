@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Projects</H1>

        <table class="table table-striped">
            <thead></thead>
            <tbody>
                <tr>
                    <td colspan="99">{{ $projects[0] }} </td>
                </tr>
                @foreach($projects as $key => $project)
                <tr>
                    <td>
                        <a href="{{ route('projects.show', $project->id) }}">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                    </td>
                    <td>{{ $project->id }} </td>
                    <td>{{ $project->name }} </td>
                    <td>{{ $project->displayname }} </td>
                    <td>{{ $project->project_type }} </td>

                    <td>{{ $project->location }} </td>
                    <td>{{ $project->planperiod_start }} </td>
                    <td>{{ $project->planperiod_end }} </td>
                    <td>{{ $project->usageperiod_start }} </td>
                    <td>{{ $project->usageperiod_end }} </td>
                    <td>{{ $project->equipment_period_from }} </td>
                    <td>{{ $project->equipment_period_to }} </td>
                    <td>{{ $project->_created }} </td>
                    <td>{{ $project->_updated }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection