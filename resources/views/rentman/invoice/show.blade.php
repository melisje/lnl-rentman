@extends('layouts.app')

@section('content')
<div class="container">
    <div class="bg-light rounded-2 p-3">
        <h1>{{ $invoice->displayname }}</h1>

        <table class="table table-sm table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr class="">
                    <th scope="col">Name</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach (json_decode($invoice) as $key => $value)
                <tr>
                    <td scope="row">{{ $key }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5 bg-light rounded-2 p-3">
        <h1>{{ $invoice->displayname }}</h1>

        <table class="table table-sm table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr class="">
                    <th scope="col">Name</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach (json_decode($invoice) as $key => $value)
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