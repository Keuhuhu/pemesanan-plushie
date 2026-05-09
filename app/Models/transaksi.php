<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\userplush;

class transaksi extends Model
{
    protected $fillable = [
        'user_id',
        'invoice',
        'status',
        'total_harga',
    ];

    public function user()
    {
        // 'user_id' adalah nama foreign key di tabel transaksis
        return $this->belongsTo(userplush::class, 'user_id', 'id');
    }

    

    public function details()
{
    return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
}
}
