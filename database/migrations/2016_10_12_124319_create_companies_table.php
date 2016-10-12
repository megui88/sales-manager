<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->text('description');
            $table->decimal('quote');
            $table->timestamps();
            $table->primary('id');
        });

        \App\Company::create([
            'name' => 'MP',
            'quote' => 80,
            'description' => 'Massalin Particulares',
        ]);

        \App\Company::create([
            'name' => 'Adherente',
            'quote' => 0,
            'description' => 'Adherente a la Mutual 7 Marzo',
        ]);

        \App\Company::create([
            'name' => 'Retirado',
            'quote' => 80,
            'description' => 'Retirados Massalin Particulares',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companies');
    }
}
