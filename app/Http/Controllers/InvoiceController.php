<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with('items')->latest()->get();
        return view('backend.invoice.index', compact('invoices'));
    }

    public function create(): View
    {
        return view('backend.invoice.create');
    }

    public function storeHeader(Request $request)
    {
        $invoice = Invoice::create($request->all());

        return view('backend.invoice.create', compact('invoice'));
    }

    public function storeItem(Request $request)
    {
        // dd($request->all());
        InvoiceItem::create($request->all());

        $invoice = Invoice::with('items')->find($request->invoice_id);

        return view('backend.invoice.create', compact('invoice'))
            ->with('success', 'Item ditambahkan!');
    }
    
    public function edit($id): View
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return view('backend.invoice.edit', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->only([
            'invoice_date', 'invoice_number',
            'from_company', 'from_address',
            'to_company', 'to_address'
        ]));

        return redirect()->route('invoice.edit', $id)
            ->with('success', 'Invoice berhasil diperbarui!');
    }

    public function destroyItem($id)
    {
        $item = InvoiceItem::findOrFail($id);
        $invoiceId = $item->invoice_id;
        $item->delete();

        return redirect()->route('invoice.edit', $invoiceId)
            ->with('success', 'Item berhasil dihapus!');
    }
   

    public function printPdf($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // ✅ Bersihkan karakter yang tidak boleh ada di nama file
        $filename = str_replace(['/', '\\'], '-', $invoice->invoice_number);
        
        $pdf = Pdf::loadView('backend.invoice.print', compact('invoice'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("Invoice-{$filename}.pdf");
    }
}
