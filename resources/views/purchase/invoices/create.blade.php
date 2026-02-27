@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Nieuwe Factuur Inboeken</h1>

    <form action="{{ route('purchase.invoices.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 shadow rounded-lg border">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Stap 1: Kies Bestelling --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Selecteer Bestelling (PO)</label>
                    <select name="purchase_order_id" onchange="window.location.href='?purchase_order_id=' + this.value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Kies een openstaande PO --</option>
                        @foreach($purchaseOrders as $po)
                        <option value="{{ $po->id }}" {{ request('purchase_order_id')==$po->id ? 'selected' : '' }}>
                            {{ $po->po_number }} - {{ $po->supplier->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stap 2: Factuurdetails --}}
                @if($selectedPO)
                <input type="hidden" name="supplier_id" value="{{ $selectedPO->supplier_id }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Factuurnummer Leverancier</label>
                    <input type="text" name="invoice_number" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Factuurdatum</label>
                    <input type="date" name="invoice_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Totaalbedrag (Factuur)</label>
                    <input type="number" step="0.01" name="total_amount" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                @endif
            </div>
        </div>

        @if($selectedPO)
        <div class="bg-white shadow rounded-lg border overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Besteld / Ontvangen</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aantal op Factuur</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Prijs per stuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($selectedPO->lines as $index => $line)
                    @php
                    $received = $line->receiptLines->sum('received_quantity');
                    $invoiced = $line->invoiceLines->sum('invoiced_quantity');
                    $toInvoice = $received - $invoiced;
                    @endphp
                    <tr>
                        <td class="px-6 py-4">
                            <input type="hidden" name="lines[{{$index}}][po_line_id]" value="{{ $line->id }}">
                            <div class="text-sm font-medium text-gray-900">{{ $line->internalItem->description }}</div>
                            <div class="text-xs text-gray-500">Ref: {{ $line->internalItem->internal_reference }}</div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                            PO: {{ $line->qty_ordered }} | Ontvangen: <strong>{{ $received }}</strong>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" step="0.01" name="lines[{{$index}}][invoiced_quantity]" value="{{ $toInvoice > 0 ? $toInvoice : 0 }}" class="w-full border-gray-300 rounded-md shadow-sm text-center">
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" step="0.0001" name="lines[{{$index}}][unit_price]" value="{{ $line->unit_price }}" class="w-full border-gray-300 rounded-md shadow-sm text-center">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow">
                Factuur Opslaan & Controleren
            </button>
        </div>
        @endif
    </form>
</div>
@endsection