<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    protected $table = 'surat_jalan';

    protected $fillable = [
        'surat_jalan_number',
        'nomor_peb',
        'tanggal_peb',
        'nomor_polisi',
        'nama_supir',
        'jenis_kendaraan',
        'invoice_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
