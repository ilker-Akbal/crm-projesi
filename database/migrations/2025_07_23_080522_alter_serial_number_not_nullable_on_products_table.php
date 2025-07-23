<?php

// database/migrations/XXXX_XX_XX_alter_serial_number_not_nullable_on_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // existing unique index'i kaldır (eğer otomatik isimlendirmeyse kontrol edin)
            $table->dropUnique(['serial_number']);
            // sütunu NOT NULL ve unique olarak değiştir
            $table->string('serial_number', 255)
                  ->unique()
                  ->nullable(false)
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // geri alırken tekrar nullable + unique
            $table->dropUnique(['serial_number']);
            $table->string('serial_number', 255)
                  ->unique()
                  ->nullable()
                  ->change();
        });
    }
};
