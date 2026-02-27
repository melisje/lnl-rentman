<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\SupplierInvoiceRequest;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\SupplierInvoice;
use App\Services\Purchase\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {
        $invoices = SupplierInvoice::with('supplier')->paginate(15);
        return view('purchase.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = SupplierInvoice::with([
            'lines.poLine.internalItem',
            'lines.poLine.receiptLines'
        ])->findOrFail($id);

        return view('purchase.invoices.show', compact('invoice'));
    }

    public function create(Request $request)
    {
        // We halen PO's op die nog niet volledig gefactureerd zijn
        $purchaseOrders = PurchaseOrder::with('supplier')
            ->whereIn('status', ['sent', 'partial', 'received'])
            ->get();

        // Als er een PO is geselecteerd, laden we de lijnen voor het formulier
        $selectedPO = null;
        if ($request->has('purchase_order_id')) {
            $selectedPO = PurchaseOrder::with(['lines.internalItem', 'lines.receiptLines', 'lines.invoiceLines'])
                ->findOrFail($request->purchase_order_id);
        }

        return view('purchase.invoices.create', compact('purchaseOrders', 'selectedPO'));
    }

    public function store(SupplierInvoiceRequest $request)
    {
        try {
            $invoice = $this->invoiceService->createInvoice($request->validated());

            return redirect()->route('purchase.invoices.index')
                ->with('success', 'Factuur succesvol aangemaakt. Status: ' . $invoice->status);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Fout bij opslaan: ' . $e->getMessage());
        }
    }

}
