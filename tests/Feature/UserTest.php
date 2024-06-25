<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess()
    {
        $this->post('/api/v1/users', [
            'name' => 'Budi Man',
            'email' => 'budi.man@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ])->assertStatus(201)
            ->assertJson([
                "data"=>[
                    "name"=>"Budi Man",
                    "email"=>"budi.man@gmail.com"
                ]
            ]);
    }
    public function testRegisterFailed()
    {
        $this->post('/api/v1/users', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => ''
        ])->assertStatus(400)
            ->assertJson([
                "errors"=>[
                    "name"=>[
                        "The name field is required."
                    ],
                    "email"=>[
                        "The email field is required."
                    ],
                    "password"=>[
                        "The password field is required."
                    ]
                ]
            ]);
    }
    public function testRegisterFailedValidation()
    {
        $this->testRegisterSuccess();   
        $this->post('/api/v1/users', [
            'name' => 'Agus Tina',
            'email' => 'budi.man@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ])->assertStatus(400)
            ->assertJson([
                "errors"=>[
                    "email"=>[
                        "The email has already been taken."
                    ]
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->testRegisterSuccess();   
        $this->post('/api/v1/users/login', [
            'email' => 'budi.man@gmail.com',
            'password' => '123456789'
        ])->assertStatus(200)
            ->assertJson([
                "data"=>[
                    "name"=>"Budi Man",
                    "email"=>"budi.man@gmail.com"
                ]
            ]);
        
        $user = User::where('email','budi.man@gmail.com')->first();
        self::assertNotNull($user->remember_token);


    }

    public function testLoginFailedEmail()
    {
        $this->post('/api/v1/users/login', [
            'email' => 'budi.man@gmail.com',
            'password' => '123456789'
        ])->assertStatus(401)
            ->assertJson([
                "errors"=>[
                    "message"=>['username or password wrong']
                ]
            ]);
    }
  
    public function testLoginFailedPassword()
    {
        $this->testRegisterSuccess();
        $this->post('/api/v1/users/login', [
            'email' => 'budi.man@gmail.com',
            'password' => 'password123456789'
        ])->assertStatus(401)
            ->assertJson([
                "errors"=>[
                    "message"=>['username or password wrong']
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);
        

        $this->get('/api/v1/users/profile', [
            'Authorization' => '1234-5678'
        ])->assertStatus(200)
            ->assertJson([
                "data"=>[
                    "email"=>'agustina@gmail.com',
                    "name"=>'Agus Tina',
                ]
            ]);
    }

    public function testGetFailedUnauthorized()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);

        $this->get('/api/v1/users/profile')
            ->assertStatus(401)
            ->assertJson([
                "error" => [
                    'message' => ['Unauthorized']
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);

        $this->get('/api/v1/users/profile', [
            'Authorization' => '8765-4321'
        ])->assertStatus(401)
            ->assertJson([
                "error" => [
                    'message' => ['Unauthorized']
                ]
            ]);
    }

    public function testUpdateNameSuccess()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);
        $oldUser = User::where('email','agustina@gmail.com')->first();
        

        $this->patch('/api/v1/users/profile', 
            [
                'name' => 'Agus Tina Updated'
            ],
            [
                'Authorization' => '1234-5678'
            ]
        )->assertStatus(200)
            ->assertJson([
                "data"=>[
                    "email"=>'agustina@gmail.com',
                    "name"=>'Agus Tina Updated',
                ]
            ]);

        $newUser = User::where('email','agustina@gmail.com')->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdateNamePassword()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);
        $oldUser = User::where('email','agustina@gmail.com')->first();
        

        $this->patch('/api/v1/users/profile', 
            [
                'password' => 'agustina12345678',
            ],
            [
                'Authorization' => '1234-5678'
            ]
        )->assertStatus(200)
            ->assertJson([
                "data"=>[
                    "email"=>'agustina@gmail.com',
                    "name"=>'Agus Tina',
                ]
            ]);

        $newUser = User::where('email','agustina@gmail.com')->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateFailed()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);
        $this->patch('/api/v1/users/profile', 
            [
                'name' => 'Agus Tina Updated, but failed because of name validation failed, Agus Tina Updated, but failed because of name validation failed'
            ],
            [
                'Authorization' => '1234-5678'
            ]
        )->assertStatus(400)
            ->assertJson([
                "errors"=>[
                    "name"=>['The name field must not be greater than 100 characters.',]
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);
        

        $this->delete(uri: '/api/v1/users/logout', headers:[
            'Authorization' => '1234-5678'
        ])->assertStatus(200)
            ->assertJson([
                "data"=>true
            ]);

        $user = User::where('email','agustina@gmail.com')->first();
        self::assertNull($user->remember_token);
    }

    public function testLogoutFailed()
    {
        $user = User::create([
            'name' => 'Agus Tina',
            'email' => 'agustina@gmail.com',
            'password' => Hash::make('12345678'),
            'remember_token' => '1234-5678'
        ]);

        $this->delete(uri:'/api/v1/users/logout',  headers: [
                'Authorization' => 'Wrong-Authorization-Token'
        ])->assertStatus(401)
            ->assertJson([
                "error"=>[
                    "message"=>['Unauthorized']
                ]
            ]);
    }


}
 