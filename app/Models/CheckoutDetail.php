<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutDetail extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom untuk diisi secara massal
    protected $guarded = [];

    // Opsional: Relasi balik ke transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
