<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTaxAndPhoneColumnsInCompaniesTable extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('tax_number', 11)->unique()->nullable()->change();
            $table->string('phone_number', 11)->unique()->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['tax_number']);
            $table->dropUnique(['phone_number']);
            $table->string('tax_number')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
        });
    }
}
