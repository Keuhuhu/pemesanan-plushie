<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userplush extends Model
{

protected $table = 'users';
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];
}
