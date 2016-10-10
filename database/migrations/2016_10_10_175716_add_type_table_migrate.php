<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeTableMigrate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('migrates', function (Blueprint $table) {
            $table->string('type')->default(\App\Migrate::PHARMACY_TYPE);
            $table->index('type', 'migrate_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('migrates', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
