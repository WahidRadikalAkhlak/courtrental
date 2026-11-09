<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->json('racket_items')->nullable()->after('end_time'); // Menyimpan rincian ID raket & qty$table->integer('racket_price')->default(0)->after('racket_items'); // Subtotal sewa raket
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {$table->dropColumn(['racket_items', 'racket_price']);
    });
}
};
