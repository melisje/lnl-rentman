@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Test Magazijn overzicht</H1>
        @include('layouts.errors')


        <table class="table table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th>project</th>
                    <th>subproject</th>
                    <th>status</th>
                    <th>planstart</th>
                </tr>
            </thead>
            <tbody>
                @foreach($models as $model)
                <tr class="">
                    <td class="text-muted">{{ $model->parentProject->full_display_name ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $model->name ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $model->status_name ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $model->planperiod_start->toDateString() ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $model->vrijgave_voor_warehouse ?? 'N/A' }}</td>
                    {{-- <td class="text-muted">{{ $model }}</td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
@endpush