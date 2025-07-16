// database/migrations/2025_07_16_000000_make_customers_user_fks_nullable.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Doctrine DBAL gerekir: composer require doctrine/dbal
        Schema::table('customers', function (Blueprint $table) {
            // Önce eski foreign key'i düşür
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            // Sütunu nullable olarak değiştir
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            // Yeni FK’leri ON DELETE SET NULL ile tanımla
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->unsignedBigInteger('created_by')->nullable(false)->change();
            $table->unsignedBigInteger('updated_by')->nullable(false)->change();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }
};
