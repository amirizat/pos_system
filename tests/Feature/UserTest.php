<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
}
