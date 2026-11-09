<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'customer_name',
        'rating',
        'comment',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}
