<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'cust_id', 'tgl', 'subtotal', 'diskon', 'ongkir', 'total_bayar','kode',
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id');
    }
}

