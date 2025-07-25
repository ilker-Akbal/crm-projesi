<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_serials', function (Blueprint $table) {
            if (!Schema::hasColumn('product_serials', 'order_id')) {
                $table->foreignId('order_id')
                    ->nullable()
                    ->constrained()
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('product_serials', 'status')) {
                $table->string('status')->default('free')->after('serial_number');
            }

            // Serial numara zaten unique mi kontrolü zor olabilir.
            // Gerekirse önce terminalden manuel bak: SHOW INDEX FROM product_serials;
        });
    }

    public function down(): void
    {
        Schema::table('product_serials', function (Blueprint $table) {
            if (Schema::hasColumn('product_serials', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }

            if (Schema::hasColumn('product_serials', 'status')) {
                $table->dropColumn('status');
            }

            // dropUnique için index adını belirt
            $table->dropUnique('product_serials_serial_number_unique');
        });
    }
};
