<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::create([
            'username' => 'auth_admin',
            'password' => Hash::make('password'),
            'nom' => 'Auth',
            'prenom' => 'Admin',
            'role' => 'admin',
            'cne' => null,
        ]);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::create([
            'username' => 'auth_agent',
            'password' => Hash::make('password'),
            'nom' => 'Auth',
            'prenom' => 'Agent',
            'role' => 'agent',
            'cne' => null,
        ]);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::create([
            'username' => 'auth_student',
            'password' => Hash::make('password'),
            'nom' => 'Auth',
            'prenom' => 'Student',
            'role' => 'student',
            'cne' => 'CNE88888',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
