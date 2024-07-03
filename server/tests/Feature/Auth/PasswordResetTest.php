<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    // use RefreshDatabase;

    // public function testResetPasswordLinkCanBeRequested()
    // {
    //     Notification::fake();

    //     $user = User::factory()->create();

    //     $this->post('/forgot-password', ['email' => $user->email]);

    //     Notification::assertSentTo($user, ResetPassword::class);
    // }

    // public function testPasswordCanBeResetWithValidToken()
    // {
    //     Notification::fake();

    //     $user = User::factory()->create();

    //     $this->post('/forgot-password', ['email' => $user->email]);

    //     Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
    //         $response = $this->post('/reset-password', [
    //             'token' => $notification->token,
    //             'email' => $user->email,
    //             'password' => 'new-password',
    //             'password_confirmation' => 'new-password',
    //         ]);

    //         $response->assertSessionHasNoErrors();

    //         return true;
    //     });
    // }
}