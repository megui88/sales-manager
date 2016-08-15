<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class AccessTokenTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $user;
    private $oauth_client;

    public function setUp()
    {
        parent::setUp();
        $this->oauth_client = factory(App\OauthClients::class)->create(['secret' => '1234']);
        $this->user = factory(\App\User::class)->create(['password' => '1234']);
        $this->seeInDatabase('users', ['password' => '1234']);
        $this->seeInDatabase($this->oauth_client->table, ['secret' => '1234']);
    }

    public function testGetCorrectAccessToken()
    {
        Auth::shouldReceive('once')
            ->once()
            ->andReturn(true);
        Auth::shouldReceive('user')
            ->once()
            ->andReturn($this->user);
        $authenticated = [
            'grant_type' => 'password',
            'client_id' => $this->oauth_client->id,
            'client_secret' => '1234',
            'username' => $this->user->email,
            'password' => '1234',
        ];

        $this->post('oauth/access_token', $authenticated)
            ->seeStatusCode(200)
            ->seeJson(['token_type' => 'Bearer']);
    }

    public function testGetAccessTokenSecretWrong()
    {
        $authenticated = [
            'grant_type' => 'password',
            'client_id' => $this->oauth_client->id,
            'client_secret' => '****',
            'username' => $this->user->email,
            'password' => '1234',
        ];

        $this->post('oauth/access_token', $authenticated)
            ->seeStatusCode(401)
            ->seeJson(['error_description' => 'Client authentication failed.']);
    }

    public function testGetAccessTokenPasswordWrong()
    {
        Auth::shouldReceive('once')
            ->once()
            ->andReturn(false);
        $authenticated = [
            'grant_type' => 'password',
            'client_id' => $this->oauth_client->id,
            'client_secret' => '1234',
            'username' => $this->user->email,
            'password' => '1234*',
        ];

        $this->post('oauth/access_token', $authenticated)
            ->seeStatusCode(401)
            ->seeJson(['error_description' => 'The user credentials were incorrect.']);
    }

    public function testGetAccessTokenGrantTypeWrong()
    {
        $authenticated = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->oauth_client->id,
            'client_secret' => '1234',
            'username' => $this->user->email,
            'password' => '1234',
        ];

        $this->post('oauth/access_token', $authenticated)
            ->seeStatusCode(400)
            ->seeJson(['error_description' => 'The request is missing a required parameter, includes an invalid'
                .' parameter value, includes a parameter more than once, or is otherwise malformed.'
                .' Check the "redirect_uri" parameter.', ]);
    }
}
