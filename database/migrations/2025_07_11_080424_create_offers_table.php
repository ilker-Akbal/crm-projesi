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
        Schema::create('offers', function (Blueprint $table) {
    $table->id();

    $table->foreignId('customer_id')->constrained('customers');
    $table->foreignId('order_id')->nullable()
          ->constrained('orders')->nullOnDelete();

    $table->date('offer_date');
    $table->date('valid_until')->nullable();
    $table->string('status');

    // ------------- eklenen kolon -------------
    $table->decimal('total_amount', 15, 2)->default(0);

    $table->foreignId('created_by')->nullable()
          ->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()
          ->constrained('users')->nullOnDelete();

    $table->timestamps();
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
