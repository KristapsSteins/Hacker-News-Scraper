<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserTest extends TestCase
{
    // use RefreshDatabase;

    public function testRegisterUser()
    {
        $userData = User::factory()->make()->toArray();

        $userData['password'] = 'password';

        $response = $this->post(route('register'), $userData);

        $response->assertStatus(200);

        $this->assertTrue(User::where('email', $userData['email'])->exists());
    }

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create();
    
        $this->actingAs($user);
    
        $response = $this->post(route('logout'));
    
        $response->assertStatus(302);
        $this->assertGuest();
    }
}