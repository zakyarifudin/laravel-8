<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;



class AuthControllerTest extends TestCase
{
    public static function passportLogin()
    {
        Passport::actingAs(
            User::first(),
            ['*']
        );
    }

    public function testUserAuthSuccess()
    {
        self::passportLogin();

        $this->getJson('api/user-auth')
            ->assertOk()
            ->assertJsonStructure([
                'id_user', 'name', 'email'
            ]);
    }

    public function testUserAuthFail()
    {
        $this->getJson('api/user-auth')
            ->assertStatus(401);
    }

    public function testUserLogoutSuccess()
    {
        self::passportLogin();

        $this->getJson('api/user-logout')
            ->assertOk()
            ->assertJsonStructure([
                'message', 'revoke',
            ]);
    }

    public function testUserLogoutFail()
    {
        $this->getJson('api/user-logout')
            ->assertStatus(401);
    }
}
