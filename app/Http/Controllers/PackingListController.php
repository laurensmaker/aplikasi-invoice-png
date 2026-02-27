<?php

namespace App\Http\Controllers;

use App\Models\PackingList;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PackingListController extends Controller
{
    public function index()
    {
        $packingLists = PackingList::with(['invoice', 'invoice.items'])->latest()->get();
        return view('backend.packing-list.index', compact('packingLists'));
    }
   

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'invoice_id'     => 'required|exists:invoice,id',
            'packing_date'   => 'required|date',
            'packing_number' => 'required|string',
        ]);

        PackingList::create([
            'invoice_id'     => $request->invoice_id,
            'packing_date'   => $request->packing_date,
            'packing_number' => $request->packing_number,
        ]);

        return redirect()->route('invoice.index')->with('success_add', 'Packing List berhasil disimpan!');
    }

    public function destroy($id)
    {
        $packing = PackingList::findOrFail($id);
        $packing->delete();

        return redirect()->route('packing-list.index')
            ->with('deleted', 'Packing List ' . $packing->packing_number . ' berhasil dihapus.');
    }

    public function printPdf($id)
    {
        $packing = PackingList::with(['invoice', 'invoice.items'])->findOrFail($id);
        
        $filename = str_replace(['/', '\\'], '-', $packing->packing_number);
        
        $pdf = Pdf::loadView('backend.packing-list.print', compact('packing'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("PackingList-{$filename}.pdf");
    }
}
