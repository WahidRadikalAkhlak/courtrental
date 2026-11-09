<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('rackets', function (Blueprint $table) {
        $table->id();$table->string('name');             // Contoh: "Babolat Counter Vertuo" atau "Head Evo Sanyo"
        $table->string('brand')->nullable();$table->integer('price_per_session'); // Harga sewa per sesi/jam (misal: 35000)
        $table->integer('stock')->default(10);$table->string('image')->nullable();
        $table->boolean('is_active')->default(true);$table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('rackets');
}
};
