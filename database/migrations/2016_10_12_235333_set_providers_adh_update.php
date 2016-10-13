<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetProvidersAdhUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adherente = \App\Company::where('name', '=', 'Adherente')->first()->id;
        \App\User::where('role','=',\App\Services\BusinessCore::VENDOR_ROLE)->update([
            'company_id' =>$adherente,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
