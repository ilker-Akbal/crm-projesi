<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('username')->unique();

            // Sadece formda sunulan roller
            $table->enum('role', ['admin', 'manager', 'user'])
                  ->default('user');

            $table->boolean('active')->default(true);

            // Form şimdilik parola göndermediği için nullable
            $table->string('password')->nullable();
            $table->rememberToken();

            // Kullanıcı takibi (isteğe bağlı – nullable)
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
