<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function testLogout()
    {
        $user = User::factory()->create();

        $this->be($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/login');

        $this->assertFalse(Auth::check());
    }

    public function testGuestCannotAccessLogout()
    {
        $response = $this->post('/logout');
        $response->assertRedirect('/login');
    }

    public function testUserCannotAccessHomeAfterLogout()
    {
        $user = User::factory()->create();
        $this->be($user);

        $this->post('/logout');

        $response = $this->get('/home');
        $response->assertStatus(404);
    }
}