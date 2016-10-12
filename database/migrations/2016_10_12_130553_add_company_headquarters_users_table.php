<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyHeadquartersUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('company_id')->default(\App\Company::where('name', '=', 'MP')->first()->id);
            $table->uuid('headquarters_id')->default(\App\Headquarters::where('name', '=', 'Merlo')->first()->id);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('headquarters_id');
        });
    }
}
