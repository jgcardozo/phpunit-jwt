<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserTest extends TestCase
{
    //use RefreshDatabase;

    protected string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = 'http://localhost:8000/api/';
    }

/*     public function test_register_route(){
        $resp = 
    } */

    public function test_register_user_new():void
    {
        // using sanctum
        //$this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        //$getOk = $this->get();
        //$getOk->assertStatus(200)->assertSee('Register');
        //$respBien->assertStatus(302)->assertRedirect('dashboard');
        //Artisan::call('migrate:fresh'); // i prefer this instead of refreshDatabase trait.

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

    public function test_register_user_taken():void
    {
        $userArr = [
            "name" => "zboncak birdie",
            "email" => "zboncak.birdie@example.org",
            "password" => "pass12345678"
        ];

        $response = $this->postJson($this->baseUrl . 'auth/register', $userArr);
        $response->assertStatus(422);

    }

}//class
