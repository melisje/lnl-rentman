@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-5 mb-4 bg-body-secondary rounded-3 ">
        <div class="my-2">
            add code for showing a list of invoices


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">number</th>
                        <th scope="col">displayname</th>
                        <th scope="col">customer</th>
                        <th scope="col">accountmanager</th>
                    </tr>
                </thead>
                @foreach($invoices as $invoice)
                <tr>
                    <td>
                        <a href="{{ route('invoices.show', $invoice->id) }}">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                    </td>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->displayname }}</td>
                    <td>{{ $invoice->customer }}</td>
                    <td>{{ $invoice->account_manager }}</td>
                    {{-- <td>{{ $invoice }}</td> --}}
                </tr>
                @endforeach
            </table>

            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection