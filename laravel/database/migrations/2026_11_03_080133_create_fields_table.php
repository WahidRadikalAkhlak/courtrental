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
        Schema::create('fields', function (Blueprint $table) {
    $table->id();
    $table->string('name');              // Contoh: "Court 1 - Panoramic"
    $table->enum('type', ['indoor', 'outdoor']);
    $table->integer('price_per_hour');  // Satuan Rupiah (misal: 250000)
    $table->boolean('is_active')->default(true);
    $table->string('image')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
