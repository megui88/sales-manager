<?php

use App\Services\BusinessCore;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompanyHeadquartersUsersRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adherente = \App\Company::where('name', '=', 'Adherente')->first()->id;
        $retirado = \App\Company::where('name', '=', 'Retirado')->first()->id;
        $goya = \App\Headquarters::where('name', '=', 'Goya')->first()->id;

        \App\User::where('name','like','%goya%')->update([
            'headquarters_id' => $goya,
        ]);

        \App\User::where('name','like','%adherente%')->update([
            'company_id' => $adherente,
        ]);

        \App\User::where('name','like','%retirado%')->update([
            'company_id' => $retirado,
        ]);

        \App\User::where('name','like','%baja%')->update([
            'state' => BusinessCore::MEMBER_DISENROLLED,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $mp = \App\Company::where('name', '=', 'MP')->first()->id;
        $merlo = \App\Headquarters::where('name', '=', 'Merlo')->first()->id;
        \App\User::where('company_id','<>',0)->update([
            'company_id' => $mp,
            'headquarters_id' => $merlo,
        ]);


        \App\User::where('name','like','%baja%')->update([
            'state' => BusinessCore::MEMBER_AFFILIATE,
        ]);
    }
}
