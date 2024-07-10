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


    public function test_register()
    {
        // using sanctum
        //$this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        //$getOk = $this->get();
        //$getOk->assertStatus(200)->assertSee('Register');
        Artisan::call('migrate:fresh'); // i prefer this instead of refreshDatabase trait.
        
        $name = fake()->name;
        $email = fake()->unique()->safeEmail();
        $password = "pass12345678";

        $resp = $this->postJson(
            $this->baseUrl.'auth/register',
            [
                "name" => $name,
                "email" => $email,
                "password" => $password,
                //"password_confirmation" => $password
            ]
        );

        $resp->assertStatus(201);
        //$respBien->assertStatus(302)->assertRedirect('dashboard');
        //$respBien->assertDatabaseHas('users', ['name' => $name]);

    } //test_register

}//class
