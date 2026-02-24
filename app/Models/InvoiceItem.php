<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_item';

    protected $fillable = [
        'invoice_id',
        'qty',
        'description',
        'unit_price',
        'total_price',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
