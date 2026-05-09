<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\userplush; 
use App\Models\product;


class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'kuantitas',
    ];

    public function user()
    {
        // 'user_id' adalah nama foreign key di tabel transaksis
        return $this->belongsTo(userplush::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }
}
