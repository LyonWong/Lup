<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSignInAccount()
    {
        $response = $this->put('/sign-account', [
            'identity' => 'testuser',
            'password' => 'userpass'
        ]);

        $response->assertStatus(302);
    }
}
