<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Eğer isim farklıysa index adını kontrol edin:
            $table->dropUnique(['serial_number']);
            $table->dropColumn('serial_number');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('serial_number', 255)
                  ->unique()
                  ->nullable(); // İlk başta nullable bırakıyoruz, sonra yeniden göç edebilirsiniz
        });
    }
};
