<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this -> post('/api/users',[
            'username' => 'amir',
            'password' => '1234',
            'name'  => 'amir izat'
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'username' => 'amir',
                    'name' => 'amir izat'
                ]
                ]);
    }

    public function testRegisterFailed()
    {
        $this -> post('/api/users',[
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "The username field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name'=>[
                        "The name field is required."
                    ]
                ]
                    ]);
    }

    public function testUsernameAlreadyExits()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users',[
            'username' => 'amir',
            'password' => '1234',
            'name' => 'amir izat'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username'=>[
                        "username already registered"
                    ]
                ]
                    ]);
    }
    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test'
                ]
            ]);

        $user = User::where('username', 'test')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }
}
