<?php

namespace App\Http\Controllers;

use App\Models\Pengangkutan;
use App\Models\SuratJalan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratJalanController extends Controller
{
   public function index()
    {
        $suratJalans = SuratJalan::with([
            'invoice',
            'invoice.items',
            'invoice.packingList',
            'pengangkutan'
        ])->latest()->get();

        return view('backend.surat-jalan.index', compact('suratJalans'));
    }

    public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'invoice_id'         => 'required|exists:invoice,id',
        'surat_jalan_number' => 'required|string',
        'nomor_peb'          => 'required|string',
        'tanggal_peb'        => 'required|date',

        'nomor_polisi'       => 'required|array',
        'nama_supir'         => 'required|array',
        'jenis_kendaraan'    => 'required|array',
    ]);

    // SIMPAN SURAT JALAN
    $sj = SuratJalan::create([
        'invoice_id'         => $request->invoice_id,
        'surat_jalan_number' => $request->surat_jalan_number,
        'nomor_peb'          => $request->nomor_peb,
        'tanggal_peb'        => $request->tanggal_peb,
    ]);

    // SIMPAN KENDARAAN
    foreach ($request->nomor_polisi as $i => $nopol) {
        Pengangkutan::create([
            'surat_jalan_id' => $sj->id,
            'nomor_polisi'   => $nopol,
            'nama_supir'     => $request->nama_supir[$i],
            'jenis_kendaraan'=> $request->jenis_kendaraan[$i],
        ]);
    }

    return redirect()->route('invoice.index')
        ->with('success_add', "Surat Jalan $sj->surat_jalan_number berhasil dibuat.");
}

    public function print($id)
    {
        $suratJalan = SuratJalan::with(['invoice', 'invoice.items', 'pengangkutan'])->findOrFail($id);

        $filename = str_replace(['/', '\\'], '-', $suratJalan->surat_jalan_number);

        $pdf = Pdf::loadView('backend.surat-jalan.print', compact('suratJalan'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("SuratJalan-{$filename}.pdf");
    }

    public function destroy($id)
    {
        $suratJalan = SuratJalan::findOrFail($id);
        $suratJalan->delete();

        return redirect()->route('surat-jalan.index')
            ->with('deleted', 'Surat Jalan ' . $suratJalan->surat_jalan_number . ' berhasil dihapus.');
    }
}
