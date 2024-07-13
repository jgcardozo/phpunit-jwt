<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected string $baseUrl, $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = env('API_BASEURL');
        //$this->token = \Cache::get('access_token');
    }


    public function test_register_user_new(): void
    {
        // using sanctum
        //$this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class); // guard web

        //\Artisan::call('migrate:fresh');
        $name = fake()->name;
        $email = fake()->unique()->safeEmail();
        $password = "pass12345678";

        $resp = $this->postJson(
            $this->baseUrl . 'auth/register',
            [
                "name" => $name,
                "email" => $email,
                "password" => $password
            ]
        );
        $resp->assertStatus(201);
        $this->assertDatabaseHas('users', ['name' => $name]);

    } //test_register

    public function test_register_user_taken(): void
    {
        $userArr = [
            "name" => "zboncak birdie",
            "email" => "zboncak.birdie@example.org",
            "password" => "pass12345678"
        ];
        $response = $this->postJson($this->baseUrl . 'auth/register', $userArr);
        $response->assertStatus(422);
    }

    public function test_user_see_profile()
    {
        $token = \Cache::get('access_token');
        $this->assertNotNull($token, 'Token is not set in the cache');

        $response = $this->postJson($this->baseUrl . 'auth/me', [], ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'email']);
    }



    public function test_user_can_login()
    {
        $userArr = [
            "email" => "zboncak.birdie@example.org",
            "password" => "pass12345678"
        ];
        $resp = $this->postJson($this->baseUrl . 'auth/login', $userArr);
        $resp->assertStatus(200);
        $resp->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

        $this->token = $resp->json('access_token');
        \Cache::put('access_token', $this->token, 3600); //to use outSide this class, ie: userTest
    }


    public function test_user_logout(): void
    {
        $arrHeaders = ["Authorization" => "Bearer " . \Cache::get('access_token')];
        $resp = $this->postJson($this->baseUrl . 'auth/logout', [], $arrHeaders);
        $resp->assertJson([
            'message' => 'Successfully logged out'
        ]);
    }

}//class
