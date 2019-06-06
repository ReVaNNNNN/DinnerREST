<?php

namespace Tests\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_user_can_register()
    {
        $this->withoutExceptionHandling();

        $email = $this->faker->email;
        $password = $this->faker->password;

        $payload = [
            'email' => $email,
            'password' => $password
        ];

        $this->post('/api/register', $payload);

        $this->assertDatabaseHas('users', ['email' => $email]);
    }
}
