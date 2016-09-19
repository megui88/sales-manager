<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDateNullablePeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dateTime('closed_at')->nullable()->change();
            $table->string('operator_id_opened',36)->change();
            $table->string('operator_id_closed',36)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dateTime('closed_at')->change();
            $table->integer('operator_id_opened')->change();
            $table->integer('operator_id_closed')->change();
        });
    }
}
