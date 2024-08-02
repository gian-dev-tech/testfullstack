<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id', 'barang_id', 'qty', 'harga_bandrol', 'diskon_pct', 'diskon_nilai', 'harga_diskon', 'total'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}

