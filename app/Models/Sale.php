<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_no',
        'invoice_token',
        'sale_date',
        'sub_total',
        'discount',
        'tax',
        'grand_total',
    ];

    // 🔥 IMPORTANT FIX
    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
