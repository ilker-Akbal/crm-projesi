<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // index silmek istemiyorsan bu satırı kaldır:
            // $table->dropUnique(['serial_number']);

            // kolonun silinmesini istemiyorsan dropColumn’u kaldır
            // ve nullable(false)->change() ile güncelle
            $table->string('serial_number', 255)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // rollback için eski haline döndür
            $table->string('serial_number', 255)->nullable()->change();
            $table->dropUnique(['serial_number']);
        });
    }
};
