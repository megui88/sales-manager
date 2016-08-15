<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('transactional_id');
            $table->string('transactional_type');
            $table->integer('client_id');
            $table->integer('office_id');
            $table->uuid('operator_id');
            $table->uuid('supervisor_id')->nullable();
            $table->uuid('collector_id');
            $table->uuid('payer_id');
            $table->uuid('comment_id')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
