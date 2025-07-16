<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/…add_customer_id_to_users.php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('customer_id')
              ->nullable()
              ->constrained('customers')
              ->cascadeOnDelete()
              ->after('id');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropConstrainedForeignId('customer_id');
    });
}

};
