<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('field_id')->constrained()->cascadeOnDelete();
    $table->date('booking_date');
    $table->time('start_time');         // Contoh: "16:00:00"
    $table->time('end_time');           // Contoh: "17:00:00"
    $table->integer('total_price');
    $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
    $table->timestamps();

    // Mencegah double-booking di level database
    $table->unique(['field_id', 'booking_date', 'start_time']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
