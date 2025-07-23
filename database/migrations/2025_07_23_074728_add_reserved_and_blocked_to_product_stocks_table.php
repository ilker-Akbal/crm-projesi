<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->unsignedInteger('blocked_stock')
                  ->default(0)
                  ->after('stock_quantity');

            $table->unsignedInteger('reserved_stock')
                  ->default(0)
                  ->after('blocked_stock');
        });
    }

    public function down(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropColumn(['blocked_stock', 'reserved_stock']);
        });
    }
};
