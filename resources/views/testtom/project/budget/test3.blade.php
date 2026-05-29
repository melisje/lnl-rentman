@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Demo project budget & consumption</H1>
        @include('layouts.errors')

        <hr>
        <h3>Budgets:</h3>
        <code>
            $project->budgets =
            {{ $model->budgets }}
        </code>

        <hr>
        <h3>Budget Consumption:</h3>
        <code>
            $project->budget_consumption =
            {{ $model->budget_consumption }}
        </code>

        <hr>
        <h3>Budget vs Consumption:</h3>
        <code>
            $project->budget_versus_consumption =
            {{ $model->budget_versus_consumption }}
        </code>

        <hr>
        <div class="bg-light rounded-3 p-3">
            @foreach($model->budget_versus_consumption as $budgetType => $values)
            <p>{{ $budgetType }}: </p>
            <ul>
                @foreach ($values as $key => $value)

                <li>{{ $key }}: {{ $value }}</li>


                @endforeach
            </ul>
        </div>
        @endforeach

    </div>

</div>
@endsection

@push('scripts')
@endpush