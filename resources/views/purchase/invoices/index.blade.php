@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Inkomende Facturen</h1>
        <a href="{{ route('purchase.invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
            + Nieuwe Factuur
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Leverancier & Nummer</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">PO Referentie</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Datum</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Totaal (Excl.)</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Match Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-5 py-4 text-sm">
                        <p class="text-gray-900 font-bold">{{ $invoice->supplier->name }}</p>
                        <p class="text-gray-500 text-xs">Factuur: {{ $invoice->invoice_number }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-mono">
                            {{ $invoice->purchaseOrder->po_number }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-center text-gray-600">
                        {{ $invoice->invoice_date->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-4 text-sm text-right font-semibold">
                        € {{ number_format($invoice->total_amount, 2, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 text-sm text-center">
                        @switch($invoice->status)
                        @case('approved')
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">✓ OK</span>
                        @break
                        @case('price_mismatch')
                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-bold" title="Prijs wijkt af van PO">⚠️ Prijsfout</span>
                        @break
                        @case('quantity_mismatch')
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold" title="Gefactureerd > Ontvangen">❌ Aantalfout</span>
                        @break
                        @default
                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-bold">In behandeling</span>
                        @endswitch
                    </td>
                    <td class="px-5 py-4 text-sm text-right">
                        <a href="{{ route('purchase.invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination indien nodig --}}
    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
</div>
@endsection