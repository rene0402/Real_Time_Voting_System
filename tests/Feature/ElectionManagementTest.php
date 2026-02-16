<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionManagementTest extends TestCase
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

    public function test_admin_can_create_election()
    {
        $electionData = [
            'title' => 'Test Election',
            'type' => 'single',
            'start_date' => now()->addDays(1)->format('Y-m-d\TH:i'),
            'end_date' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'description' => 'Test election description'
        ];

        $response = $this->actingAs($this->adminUser)->post('/admin/elections', $electionData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('elections', [
            'title' => 'Test Election',
            'type' => 'single'
        ]);
    }

    public function test_admin_can_update_election()
    {
        $election = Election::create([
            'title' => 'Original Title',
            'type' => 'single',
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(7),
            'status' => 'scheduled'
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'type' => 'multi',
            'start_date' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'end_date' => now()->addDays(8)->format('Y-m-d\TH:i'),
            'description' => 'Updated description',
            '_method' => 'PUT'
        ];

        $response = $this->actingAs($this->adminUser)->post("/admin/elections/{$election->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'title' => 'Updated Title',
            'type' => 'multi'
        ]);
    }

    public function test_election_update_validation_fails_with_invalid_data()
    {
        $election = Election::create([
            'title' => 'Test Election',
            'type' => 'single',
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(7),
            'status' => 'scheduled'
        ]);

        $invalidData = [
            'title' => '', // Empty title should fail
            'type' => 'invalid_type',
            'start_date' => 'invalid_date',
            'end_date' => now()->addDays(8)->format('Y-m-d\TH:i'),
            '_method' => 'PUT'
        ];

        $response = $this->actingAs($this->adminUser)->post("/admin/elections/{$election->id}", $invalidData);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $response->assertJsonStructure(['errors']);
    }
}
