<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $fillable = [
        'invoice_date',
        'invoice_number',
        'from_company',
        'from_address',
        'to_company',
        'to_address',
        'total_amount',
        'signature',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
}
