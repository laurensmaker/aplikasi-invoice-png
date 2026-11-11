<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
      public function index()
    {
        $companies = Perusahaan::latest()->paginate(10);
        return view('backend.companies.index', compact('companies'));
    }

    /**
     * Menampilkan form tambah perusahaan.
     */
    public function create()
    {
        return view('backend.companies.create');
    }

    /**
     * Menyimpan data perusahaan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        Perusahaan::create([
            'name'    => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('perusahaan.index')
                         ->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit perusahaan.
     */
    public function edit(Perusahaan $perusahaan)
    {
        return view('backend.companies.edit', compact('perusahaan'));
    }

    /**
     * Menyimpan perubahan data perusahaan.
     */
    public function update(Request $request, Perusahaan $perusahaan)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $perusahaan->update([
            'name'    => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('perusahaan.index')
                         ->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Menghapus data perusahaan.
     */
    public function destroy(Perusahaan $perusahaan)
    {
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')
                         ->with('success', 'Perusahaan berhasil dihapus.');
    }
}
