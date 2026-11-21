<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_blocked_after_five_failed_attempts(): void
    {
        User::factory()->create(['email' => 'petugas@sekolah.test']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login'), ['email' => 'petugas@sekolah.test', 'password' => 'salah']);
        }

        $this->post(route('login'), ['email' => 'petugas@sekolah.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_spoofed_forwarded_ip_does_not_bypass_the_email_limit(): void
    {
        User::factory()->create(['email' => 'petugas@sekolah.test']);

        for ($i = 0; $i < 20; $i++) {
            $this->withServerVariables(['HTTP_X_FORWARDED_FOR' => "10.0.0.$i"])
                ->post(route('login'), ['email' => 'petugas@sekolah.test', 'password' => 'salah']);
        }

        $this->withServerVariables(['HTTP_X_FORWARDED_FOR' => '10.9.9.9'])
            ->post(route('login'), ['email' => 'petugas@sekolah.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_successful_login_resets_the_counter(): void
    {
        User::factory()->create(['email' => 'petugas@sekolah.test']);

        for ($i = 0; $i < 3; $i++) {
            $this->post(route('login'), ['email' => 'petugas@sekolah.test', 'password' => 'salah']);
        }

        $this->post(route('login'), ['email' => 'petugas@sekolah.test', 'password' => 'password'])
            ->assertRedirect();
        $this->assertAuthenticated();
        $this->assertSame(0, RateLimiter::attempts('petugas@sekolah.test|127.0.0.1'));
    }
}
