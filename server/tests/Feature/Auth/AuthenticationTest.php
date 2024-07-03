<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // public function testUsersCanAuthenticateUsingTheLoginScreen()
    // {
    //     $user = User::factory()->create([
    //         'password' => bcrypt($password = 'password'),
    //     ]);

    //     $response = $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => $password,
    //     ]);

    //     $this->assertAuthenticated();
    //     $response->assertNoContent();
    // }

    // public function testUsersCannotAuthenticateWithInvalidPassword()
    // {
    //     $user = User::factory()->create();

    //     $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => 'wrong-password',
    //     ]);

    //     $this->assertGuest();
    // }

    // public function testUsersCanLogout()
    // {
    //     $user = User::factory()->create();

    //     $this->actingAs($user)->post('/logout');

    //     $this->assertGuest();
    // }
}