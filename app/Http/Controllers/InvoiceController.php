<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        InvoiceItem::create($request->all());

        $invoice = Invoice::with('items')->find($request->invoice_id);

        return view('backend.invoice.create', compact('invoice'))
            ->with('success', 'Item ditambahkan!');
    }
}
