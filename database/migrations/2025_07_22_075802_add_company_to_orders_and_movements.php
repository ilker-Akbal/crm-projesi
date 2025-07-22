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
       Schema::table('orders', function (Blueprint $t) {
    $t->foreignId('company_id')
      ->nullable()->constrained()->nullOnDelete()
      ->after('customer_id');
});

Schema::table('current_movements', function (Blueprint $t) {
    $t->foreignId('company_id')
      ->nullable()->constrained()->nullOnDelete()
      ->after('current_id');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders_and_movements', function (Blueprint $table) {
            //
        });
    }
};
