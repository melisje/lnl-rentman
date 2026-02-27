@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Factuur: {{ $invoice->invoice_number }}</h1>

        {{-- Status Badge op basis van de Service logica --}}
        @if($invoice->status === 'approved')
        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">✓ Match Succesvol</span>
        @elseif(in_array($invoice->status, ['price_mismatch', 'quantity_mismatch']))
        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">⚠️ Mismatch Gevonden</span>
        @else
        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $invoice->status }}</span>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Item</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Besteld (PO)</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Ontvangen (GRN)</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Gefactureerd</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->lines as $line)
                @php
                $poLine = $line->poLine;
                $received = $poLine->receiptLines->sum('received_quantity');
                $isQuantityError = $line->invoiced_quantity > $received;
                $isPriceError = $line->unit_price > $poLine->ordered_unit_price;
                @endphp
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-5 py-4 text-sm">
                        <p class="text-gray-900 font-medium">{{ $poLine->internalItem->description }}</p>
                        <p class="text-gray-500 text-xs">{{ $poLine->internalItem->internal_reference }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm text-center">
                        {{ number_format($poLine->qty_ordered, 2) }} @ {{ number_format($poLine->unit_price, 2) }}
                    </td>
                    <td class="px-5 py-4 text-sm text-center text-blue-600 font-semibold">
                        {{ number_format($received, 2) }}
                    </td>
                    <td class="px-5 py-4 text-sm text-center {{ $isQuantityError ? 'text-red-600 font-bold' : '' }}">
                        {{ number_format($line->invoiced_quantity, 2) }} @ {{ number_format($line->unit_price, 2) }}
                    </td>
                    <td class="px-5 py-4 text-sm text-right">
                        @if($isQuantityError)
                        <span class="text-red-500 text-xs italic">Te veel gefactureerd</span>
                        @endif
                        @if($isPriceError)
                        <span class="text-red-500 text-xs italic block font-bold">Prijs te hoog!</span>
                        @endif
                        @if(!$isQuantityError && !$isPriceError)
                        <span class="text-green-500 text-xs font-bold">OK</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Totaalbedrag sectie --}}
    <div class="mt-6 flex justify-end">
        <div class="text-right">
            <p class="text-gray-600">Totaal Excl. BTW: <span class="font-bold">€ {{ number_format($invoice->total_amount, 2, ',', '.') }}</span></p>
            <p class="text-sm text-gray-500 italic">Gebaseerd op Belgische BTW regels van de PO lines.</p>
        </div>
    </div>
</div>
@endsection