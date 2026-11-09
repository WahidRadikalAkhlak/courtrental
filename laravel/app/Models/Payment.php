<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'transaction_id',   // ID transaksi dari payment gateway (misal Midtrans)
        'payment_type',     // qris, bank_transfer, gopay, dll.
        'amount',
        'status',           // pending, settlement, failed, expired
        'payload',          // JSON respon mentah dari gateway
    ];

    protected $casts = [
        'amount'  => 'integer',
        'payload' => 'array',
    ];

    /**
     * Relasi: Pembayaran mengarah ke booking terkait.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
