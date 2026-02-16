<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->adminUser = User::factory()->create([
            'user_type' => 'admin'
        ]);
    }

    public function test_admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.admin-dashboard');
    }

    public function test_admin_dashboard_contains_expected_elements()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Real-Time Voting');
        $response->assertSee('Dashboard Overview');
    }

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $voterUser = User::factory()->create([
            'user_type' => 'voter'
        ]);

        $response = $this->actingAs($voterUser)->get('/admin/dashboard');

        // Should redirect or deny access, depending on middleware
        $response->assertStatus(403); // Assuming middleware blocks access
    }
}
