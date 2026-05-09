<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $fillable = [
        'transaksi_id',
        'product_id',
        'kuantitas',
        'harga_satuan',
    ];

    // Relasi balik ke Transaksi (Header)
    public function transaksi()
    {
        return $this->belongsTo(transaksi::class);
    }

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
