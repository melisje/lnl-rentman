@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Test Jef - 1</H1>
        @include('layouts.errors')


        <table class="table table-hover table-sm">
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
                if ($lastWeeks !== $model->weeks_until_start_plan)
                {
                $useAltBg = !$useAltBg;
                $lastWeeks = $model->weeks_until_start_plan;
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


                    @foreach(['projectmanager', 'light', 'sound', 'rigging'] as $category)
                    @php
                    $consumption = $model->budget_consumption[$category] ?? 0;
                    $budget = $model->budgets[$category] ?? 0;
                    $percent = $budget > 0 ? ($consumption / $budget) * 100 : 0;

                    // Kleurlogica op basis van jouw specifieke HEX-codes
                    if ($percent > 100)
                    {
                    $bgColor = '#0000FF'; // Blauw (>100%)
                    $textColor = 'white';
                    } elseif ($percent == 100)
                    {
                    $bgColor = '#00FF00'; // Groen (100%)
                    $textColor = 'black';
                    } elseif ($percent >= 75)
                    {
                    $bgColor = '#FFFF00'; // Geel (75-100%)
                    $textColor = 'black';
                    } elseif ($percent >= 50)
                    {
                    $bgColor = '#FF7700'; // Oranje (50-75%)
                    $textColor = 'white';
                    } else
                    {
                    $bgColor = '#FF0000'; // Rood (0-50%)
                    $textColor = 'white';
                    }
                    @endphp

                    <td class="text-center fw-bold" style="background-color: {{ $bgColor }}; color: {{ $textColor }}; border: 1px solid #dee2e6;">
                        {{ number_format($consumption, 2) }} / {{ number_format($budget, 2) }} {{ number_format($percent, 2) }}%
                        <div>{{ $model->euro_budgets[$category] ?? 0 }}€</div>
                    </td>
                    @endforeach

                    @php
                    $completed = $model->count_checklist_items_completed ?? 0;
                    $items = $model->count_checklist_items ?? 0;
                    $percent = $items > 0 ? ($completed / $items) * 100 : 0;

                    // Kleurlogica op basis van jouw specifieke HEX-codes
                    if ($percent > 100)
                    {
                    $bgColor = '#0000FF'; // Blauw (>100%)
                    $textColor = 'white';
                    } elseif ($percent == 100)
                    {
                    $bgColor = '#00FF00'; // Groen (100%)
                    $textColor = 'black';
                    } elseif ($percent >= 75)
                    {
                    $bgColor = '#FFFF00'; // Geel (75-100%)
                    $textColor = 'black';
                    } elseif ($percent >= 50)
                    {
                    $bgColor = '#FF7700'; // Oranje (50-75%)
                    $textColor = 'white';
                    } else
                    {
                    $bgColor = '#FF0000'; // Rood (0-50%)
                    $textColor = 'white';
                    }
                    @endphp
                    <td class="text-center fw-bold" style="background-color: {{ $bgColor }}; color: {{ $textColor }}; border: 1px solid #dee2e6;">
                        {{ $completed }} / {{ $items }} {{ number_format($percent, 2) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>
@endsection

@push('scripts')
@endpush