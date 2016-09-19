<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class MemberDisaffiliationTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $administrative;
    private $member;

    static $administrativePassword = '1234';
    static $memberPassword = '4321';

    public function setUp()
    {
        parent::setUp();
        $member = [
            'role' => \App\Services\BusinessCore::MEMBER_ROLE,
            'password' => bcrypt(self::$memberPassword),
            'enable' => true,
        ];
        $this->member = factory(\App\User::class)->create($member);
        $this->seeInDatabase('users', $member);
        $administrative = [
            'role' => \App\Services\BusinessCore::EMPLOYEE_ADMIN_ROLE,
            'password' => bcrypt(self::$administrativePassword),
            'enable' => true,
        ];
        $this->administrative = factory(\App\User::class)->create($administrative);
        $this->seeInDatabase('users', $administrative);
    }

    public function testHappyPass()
    {
        //when
        $this->visit('/')
            ->followRedirects()
            //then
            ->see('Ingreso al sistema')
            ->see('Ingresar');

        $inputs = [
            'email' => $this->administrative->email,
            'password' => self::$administrativePassword,
        ];

        //when
        $this->submitForm('Ingresar', $inputs)
            ->followRedirects()
            //then
            ->see('Bienvenido')
            ->see('Usuarios');

        //when
        $this->click('Usuarios')
            //then
            ->see('Listado');

        //when
        $this->submitForm('users_search',['filters[q]' => $this->member->last_name])
            //then
            ->see($this->member->code)
            ->see('profile_' . $this->member->code);

        //when
        $this->click('profile_' . $this->member->code)
            //then
            ->see('Usuario <strong>' . $this->member->code . '</strong>')
            ->see($this->member->name)
            ->see($this->member->las_name)
            ->see('Editar');

        //when
        $this->click('Editar')
            //then
            ->see($this->member->name)
            ->see($this->member->las_name)
            ->see('Imprimir Alta')
            ->see('Gestionar CBU')
            ->see('Cambiar E-mail')
            ->see('Cambiar Codigo')
            ->dontSee('Gastos Administrativos')
            ->see('Actualizar')
            ->seeLink('BAJA', '/users/disenrolled/' .  $this->member->id);

        //when
        $this->click('BAJA')
            //then
            ->see('BAJA de Usuario: <strong>' . $this->member->code . '</strong><br>')
            ->see('<p>Se realizara la baja de <strong>' . $this->member->last_name . ' ' . $this->member->name
                . '</strong> DNI <strong>' . $this->member->document . '</strong>:</p>')
            ->see('Confirmar con contraseña:');

        //when
        $this->submitForm('',['password'=>self::$administrativePassword])
            //then
            ->see('BAJA de Usuario: <strong>' . $this->member->code . '</strong> <br>');

        //when
        $this->visit('/profile/edit/' . $this->member->id)
            ->see('Imprimir baja')
            ->click('Imprimir baja')
            //then
            ->see('BAJA de Usuario: <strong>' . $this->member->code . '</strong> <br>');

        //when
        $this->visit('/profile/' . $this->member->id)
            //then
            ->see('<strong style="color:red">BAJA</strong>');
    }

}
