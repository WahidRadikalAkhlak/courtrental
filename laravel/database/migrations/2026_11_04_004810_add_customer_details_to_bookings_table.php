<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        // Izinkan user_id bernilai null jika pemesan adalah tamu (guest)
        $table->foreignId('user_id')->nullable()->change();$table->string('customer_name')->after('field_id');
        $table->string('customer_email')->after('customer_name');$table->string('customer_phone')->after('customer_email');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {$table->dropColumn(['customer_name', 'customer_email', 'customer_phone']);
    });
}
};
