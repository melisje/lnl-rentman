@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Test Jef - 1</H1>
        @include('layouts.errors')


        <table class="table table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th>Days(P)</th>
                    <th>Weeks(P)</th>
                    <th>id</th>
                    <th>Project Type</th>
                    <th>account</th>
                    <th>rm_id</th>
                    <th>planned start</th>
                    <th>usage start</th>
                    <th>Project</th>
                    <th>AM</th>
                    <th>PM</th>
                    <th>#subprojs</th>
                    <th>Status</th>
                    <th class="text-center">PM (U)</th>
                    <th class="text-center">Light (U)</th>
                    <th class="text-center">Sound (U)</th>
                    <th class="text-center">Rigging (U)</th>
                    <th class="text-center">Checklist (tasks)</th>
                </tr>
            </thead>
            <tbody>

                @php
                $lastWeeks = null;
                $useAltBg = false;
                @endphp

                @foreach($models as $key => $model)

                @php
                // Als de waarde anders is dan de vorige, wisselen we van kleur
                if ($lastWeeks !== $model->weeks_until_start_usage)
                {
                $useAltBg = !$useAltBg;
                $lastWeeks = $model->weeks_until_start_usage;
                }

                // Bepaal de class op basis van de toggle
                $rowClass = $useAltBg ? 'table-primary' : 'table-white';
                @endphp

                <tr class="{{ $rowClass }}">
                    <td><span class="badge {{ $useAltBg ? 'bg-primary' : 'bg-secondary' }}">{{ $model->days_until_start_plan }}d</span></td>
                    <td><span class="badge {{ $useAltBg ? 'bg-primary' : 'bg-secondary' }}">{{ $model->weeks_until_start_plan }}w</span></td>
                    <td class="text-muted">{{ $model->id }}</td>
                    <td class="text-muted">{{ $model->projectType->name ?? $model->project_type }}</td>
                    <td class="text-muted">{{ $model->account }}</td>
                    <td class="text-muted">{{ $model->rm_id }}</td>
                    <td class="text-muted">{{ $model->planperiod_start?->toDateString() }}</td>
                    <td class="text-muted">{{ $model->usageperiod_start->toDateString() }}</td>
                    <td class="text-muted">{{ $model->full_display_name }}</td>
                    <td class="text-muted">{{ $model->am_name }}</td>
                    <td class="text-muted">{{ $model->pm_name }}</td>
                    <td class="text-muted">{{ $model->nr_of_subprojects }}</td>
                    <td class="text-muted">{{ $model->calculated_status }}</td>
                    <td class="text-muted text-center">{{ number_format($model->budget_consumption['projectmanager'] ?? 0, 2) }}/{{ number_format($model->budgets['projectmanager'] ?? 0, 2) }}</td>
                    <td class="text-muted text-center">{{ number_format($model->budget_consumption['light'] ?? 0, 2) }}/{{ number_format($model->budgets['light'] ?? 0, 2) }}</td>
                    <td class="text-muted text-center">{{ number_format($model->budget_consumption['sound'] ?? 0, 2) }}/{{ number_format($model->budgets['sound'] ?? 0, 2) }}</td>
                    <td class="text-muted text-center">{{ number_format($model->budget_consumption['rigging'] ?? 0, 2) }}/{{ number_format($model->budgets['rigging'] ?? 0, 2) }}</td>
                    <td class="text-muted text-center">{{ $model->count_checklist_items_completed }}/{{ $model->count_checklist_items }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>
@endsection

@push('scripts')
@endpush