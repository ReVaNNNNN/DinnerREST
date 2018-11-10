<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    public function testRegisterSuccessfully()
    {
        $payload = [
            'email' => 'test5@email.pl',
            'password' => 'test1234'
        ];

        $this->json('POST', 'api/register', $payload)
            ->assertStatus(201);
    }
}
