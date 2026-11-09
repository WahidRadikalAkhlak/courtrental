<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'racket_items')) {
                $table->json('racket_items')->nullable()->after('end_time');
            }
            if (!Schema::hasColumn('bookings', 'racket_price')) {
                $table->integer('racket_price')->default(0)->after('end_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $dropColumns = [];
            if (Schema::hasColumn('bookings', 'racket_items')) {
                $dropColumns[] = 'racket_items';
            }
            if (Schema::hasColumn('bookings', 'racket_price')) {
                $dropColumns[] = 'racket_price';
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
