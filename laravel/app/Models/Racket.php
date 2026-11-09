<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Racket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'price_per_session',
        'stock',
        'image',
        'is_active',
    ];
}
