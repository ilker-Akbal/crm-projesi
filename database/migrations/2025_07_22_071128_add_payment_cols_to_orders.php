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
    $t->boolean('is_paid')->default(false)->after('situation');
    $t->timestamp('paid_at')->nullable()->after('is_paid');
    $t->foreignId('payment_movement_id')
      ->nullable()->constrained('current_movements')
      ->nullOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
