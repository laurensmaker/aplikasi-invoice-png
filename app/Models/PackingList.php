<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingList extends Model
{
    protected $table = 'packinglist';

    protected $fillable = [
        'invoice_id',
        'packing_date',
        'packing_number',
    ];

     public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // Akses items lewat invoice
    public function items()
    {
        return $this->hasManyThrough(
            InvoiceItem::class,
            Invoice::class,
            'id',        // FK di invoice
            'invoice_id', // FK di invoice_item
            'invoice_id', // LK di packinglist
            'id'          // LK di invoice
        );
    }
}
