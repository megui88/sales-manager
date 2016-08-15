<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('uid');
            $table->dateTime('due_date');
            $table->dateTime('closed_at');
            $table->integer('operator_id_opened');
            $table->integer('operator_id_closed');
            $table->timestamps();
            $table->primary('id');
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('periods');
    }
}
