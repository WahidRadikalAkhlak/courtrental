<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'field_id',
    'customer_name',
    'customer_email',
    'customer_phone',
    'booking_date',
    'start_time',
    'end_time',
    'racket_items',
    'racket_price',
    'total_price',
    'status',
];

protected $casts = [
    'booking_date' => 'date',
    'racket_items' => 'array',
    'racket_price' => 'integer',
    'total_price'  => 'integer',
];

    /**
     * Relasi: Booking dimiliki oleh User pemesan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Booking terhubung ke lapangan tertentu.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Relasi: Satu booking memiliki satu transaksi pembayaran.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
