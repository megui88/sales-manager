<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class RegisterProviderTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(\App\User::class)->create(['code' => '1234']);
        $this->seeInDatabase('users', ['code' => '1234']);
    }

    public function testHappyPass()
    {
        $this->visit('/')
            ->see('Registro')
            ->see('Registrarme como proveedor');

        $this->click('Registrarme como proveedor')
            ->followRedirects()
            ->see('Alta');

        $inputs = [
                'name' => 'Gerardo',
                'last_name' => 'Gonzalez',
                'code' => '1111555',
                'code_confirmation' => '1111555',
                'email' => 'a@a.com',
                'email_confirmation' => 'a@a.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'document' => '22963753',
                'address' => 'Av siempre viva 1754',
                'location' => 'Merlo',
                'phone' => '02208529976',
                'internal_phone' => '16354',
                'cellphone' => '5411688963741',
                'birth_date' => '1963-09-04',
                'cuil_cuit' => '27559637895',
                'fantasy_name' => 'locos por la moda',
                'business_name' => 'Gerardo Gonzalez SE',
                'web' => '',
        ];
        $this->submitForm('register', $inputs)
            ->followRedirects()
            ->see('Usuario Inactivo')
            ->seeInDatabase('users', ['name' => 'Gerardo','last_name' => 'Gonzalez','code' => '1111555']);
    }

    public function testRepeatedCode()
    {

        $this->visit('/')
            ->see('Registro')
            ->see('Registrarme como proveedor');

        $this->click('Registrarme como proveedor')
            ->followRedirects()
            ->see('Alta');

        $inputs = [
            'name' => 'Gerardo',
            'last_name' => 'Gonzalez',
            'code' => $this->user->code,
            'code_confirmation' => $this->user->code,
            'email' => 'a@a.com',
            'email_confirmation' => 'a@a.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'document' => '22963753',
            'address' => 'Av siempre viva 1754',
            'location' => 'Merlo',
            'phone' => '02208529976',
            'internal_phone' => '16354',
            'cellphone' => '5411688963741',
            'birth_date' => '04/09/1963',
            'cuil_cuit' => '27559637895',
            'fantasy_name' => 'locos por la moda',
            'business_name' => 'Gerardo Gonzalez SE',
            'web' => '',
        ];
        $this->submitForm('register', $inputs)
            ->followRedirects()
            ->see('El elemento codigo ya está en uso.');
    }

    public function testRepeatedEmail()
    {

        $this->visit('/')
            ->see('Registro')
            ->see('Registrarme como proveedor');

        $this->click('Registrarme como proveedor')
            ->followRedirects()
            ->see('Alta');

        $inputs = [
            'name' => 'Gerardo',
            'last_name' => 'Gonzalez',
            'code' => '1111555',
            'code_confirmation' => '1111555',
            'email' => $this->user->email,
            'email_confirmation' => $this->user->email,
            'password' => '123456',
            'password_confirmation' => '123456',
            'document' => '22963753',
            'address' => 'Av siempre viva 1754',
            'location' => 'Merlo',
            'phone' => '02208529976',
            'internal_phone' => '16354',
            'cellphone' => '5411688963741',
            'birth_date' => '04/09/1963',
            'cuil_cuit' => '27559637895',
            'fantasy_name' => 'locos por la moda',
            'business_name' => 'Gerardo Gonzalez SE',
            'web' => '',
        ];
        $this->submitForm('register', $inputs)
            ->followRedirects()
            ->see('El elemento correo electronico ya está en uso.');
    }
}
