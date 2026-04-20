<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PointageStabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_setup_route_initializes_default_accounts(): void
    {
        $response = $this->get('/setup');

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['username' => 'admin', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['username' => 'agent', 'role' => 'agent']);
        $this->assertDatabaseHas('users', ['cne' => 'CNE12345', 'role' => 'student']);
    }

    public function test_guest_is_redirected_from_protected_pointage_page(): void
    {
        $this->get('/pointage')->assertRedirect(route('login'));
    }

    public function test_student_cannot_access_staff_only_pointage_page(): void
    {
        $student = User::create([
            'username' => 'student_access',
            'password' => Hash::make('password'),
            'nom' => 'Student',
            'prenom' => 'Access',
            'role' => 'student',
            'cne' => 'CNE99999',
        ]);

        $this->actingAs($student)
            ->get('/pointage')
            ->assertForbidden();
    }

    public function test_admin_can_access_staff_only_pointage_page(): void
    {
        $admin = User::create([
            'username' => 'admin_access',
            'password' => Hash::make('password'),
            'nom' => 'Admin',
            'prenom' => 'Access',
            'role' => 'admin',
            'cne' => null,
        ]);

        $this->actingAs($admin)
            ->get('/pointage')
            ->assertOk();
    }
}
