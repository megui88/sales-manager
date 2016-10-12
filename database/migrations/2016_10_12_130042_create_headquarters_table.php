<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadquartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headquarters', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('location');
            $table->text('description');
            $table->timestamps();
            $table->primary('id');
        });

        \App\Headquarters::create([
            'name' => 'Merlo',
            'location' => 'Buenos Aires',
            'description' => 'Av.Bicentenario 1550',
        ]);

        \App\Headquarters::create([
            'name' => 'Goya',
            'location' => 'Corrientes',
            'description' => 'E Argentino 156',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('headquarters');
    }
}
