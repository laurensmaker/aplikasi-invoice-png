<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengangkutan extends Model
{
    protected $table = 'pengangkutan';

    protected $fillable = [
        'nomor_polisi',
        'nama_supir',
        'jenis_kendaraan',
        'surat_jalan_id'
    ];

    public function suratJalan()
    {
        return $this->belongsTo(SuratJalan::class, 'surat_jalan_id');
    }
}
