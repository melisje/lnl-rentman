@extends('layouts.app')

@php
/**
* Genereert de URL voor sortering op een specifieke kolom.
* De richting wordt omgekeerd als de kolom al de huidige sorteerkolom is.
*/
function sortableLink($column, $currentSortBy, $currentDirection) {
// Bepaal de nieuwe richting: als het de huidige kolom is, keer om; anders ASC
$newDirection = ($column === $currentSortBy && $currentDirection === 'asc') ? 'desc' : 'asc';

// Gebruik de route en voeg de query parameters toe
return route('invoices.index', [
'sort' => $column,
'direction' => $newDirection
]);
}
@endphp

@section('content')
<div class="container">
    <div class="p-5 mb-4 bg-body-secondary rounded-3 ">
        <h1>Invoices</h1>
        <div class="my-2 table-responsive">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" scope="col">id</th>
                            <th class="text-center" scope="col">
                                <a class="table-dark text-decoration-none" href="{{ sortableLink('number', $sortBy, $sortDirection) }}">
                                    Number
                                    {{-- Show arrow only if table is sorted on this column --}}
                                    @if ($sortBy === 'number')
                                    {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a class="table-dark text-decoration-none" href="{{ sortableLink('displayname', $sortBy, $sortDirection) }}">
                                    DisplayName
                                    {{-- Show arrow only if table is sorted on this column --}}
                                    @if ($sortBy === 'displayname')
                                    {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a class="table-dark text-decoration-none" href="{{ sortableLink('customer', $sortBy, $sortDirection) }}">
                                    Customer
                                    {{-- Show arrow only if table is sorted on this column --}}
                                    @if ($sortBy === 'customer')
                                    {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a class="table-dark text-decoration-none" href="{{ sortableLink('account_manager', $sortBy, $sortDirection) }}">
                                    Account Manager
                                    {{-- Show arrow only if table is sorted on this column --}}
                                    @if ($sortBy === 'account_manager')
                                    {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('invoices.show', $invoice->id) }}">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </td>
                        <td class="text-center">{{ $invoice->number }}</td>
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
        <div class="px-3 py-1 rounded-2 hstack gap-2">
            <div class="badge text-bg-secondary">SortBy: {{ $sortBy}}</div>
            <div class="badge text-bg-secondary">SortDirection: {{ $sortDirection }}</div>
            <div class="badge text-bg-secondary">SortableLink: {{ sortableLink('number', $sortBy, $sortDirection) }}</div>
        </div>
    </div>
    @endsection