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
        Schema::create('current_movements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('current_id')->constrained('current_cards')->cascadeOnDelete();
    $table->date('departure_date');
    $table->decimal('amount', 12, 2);
    $table->string('movement_type');
    $table->text('explanation')->nullable();
    $table->foreignId('updated_by')->nullable()->constrained('users');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_movements');
    }
};
