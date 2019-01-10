<?php

namespace Tests\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    private $user;

    // public function setUp()
    // {
        // $user = new User();
        // $user->setEmail('test@test.pl');
        // $user->setPassword('123456');
        // $user->save();
        //
        // $this->user = $user;
    // }

    public function testRegisterSuccessfully()
    {
        $payload = [
            'email' => 'test5@email.pl',
            'password' => 'test1234'
        ];

            $response = $this->json( 'POST', 'api/register', $payload);

        // $response
        //     ->assertStatus(200)
            // ->assertJson(["status" => 'success'])
        ;
        // dd($response->seeJson([]));
        // $verificatiobCode = DB::table('users_verification')->where('user_id', $response->)

        DB::table('users')->where('email','test5@email.pl')->delete();
    }

    public function testRegisterUnsuccessfully()
    {
        $firstPayload = [
            'password' => 'test1234'
        ];

        $firstResponse = $this->json('POST', 'api/register', $firstPayload);

        $firstResponse
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "email" => [
                        'Email is required'
                    ]
                ]
            ]);

        $secondPayload = [
            'email' => 'test5@email.pl',
        ];

        $secondResponse = $this->json('POST', 'api/register', $secondPayload);

        $secondResponse
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "password" => [
                        'Password is required'
                    ]
                ]
            ]);

        $thirdPayload = [
            'email' => 'test5@email.pl',
            'password' => 'test1'
        ];

        $thirdResponse = $this->json('POST', 'api/register', $thirdPayload);

        $thirdResponse
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "password" => [
                        'The password must be at least 6 characters.'
                    ]
                ]
            ]);

        $fourthPayload = [
            'email' => 'test5@email.pl',
            'password' => 'test1234'
        ];

        $this->json('POST', 'api/register', $fourthPayload);
        $responseFourth = $this->json('POST', 'api/register', $fourthPayload);

        $responseFourth
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "email" => [
                        'Your email already exists'
                    ]
                ]
            ]);

        DB::table('users')->where('email','test5@email.pl')->delete();
    }
}
