<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoterTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $election;
    protected $candidates;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create([
            'user_type' => 'voter'
        ]);

        // Create a test election
        $this->election = Election::create([
            'title' => 'Test Election',
            'type' => 'single',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(7),
            'description' => 'Test election for candidates',
            'total_votes' => 0,
            'is_paused' => false,
            'results_locked' => false
        ]);

        // Create test candidates
        $this->candidates = collect([
            Candidate::create([
                'election_id' => $this->election->id,
                'name' => 'Candidate 1',
                'description' => 'First candidate',
                'photo_url' => null
            ]),
            Candidate::create([
                'election_id' => $this->election->id,
                'name' => 'Candidate 2',
                'description' => 'Second candidate',
                'photo_url' => null
            ])
        ]);
    }

    public function test_voter_can_access_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/voter-dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('electionsWithStatus');
    }

    public function test_voter_can_get_candidates_for_election()
    {
        $response = $this->actingAs($this->user)->get("/voter/candidates/{$this->election->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                [
                    'id' => $this->candidates[0]->id,
                    'name' => 'Candidate 1',
                    'description' => 'First candidate'
                ],
                [
                    'id' => $this->candidates[1]->id,
                    'name' => 'Candidate 2',
                    'description' => 'Second candidate'
                ]
            ]
        ]);
    }

    public function test_voter_can_submit_vote()
    {
        $response = $this->actingAs($this->user)->post("/voter/vote/{$this->election->id}", [
            'choices' => [$this->candidates[0]->id]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Vote recorded successfully'
        ]);

        $this->assertDatabaseHas('votes', [
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'choices' => json_encode([$this->candidates[0]->id])
        ]);

        // Check election total votes updated
        $this->election->refresh();
        $this->assertEquals(1, $this->election->total_votes);
    }

    public function test_voter_cannot_vote_twice_in_same_election()
    {
        // First vote
        $this->actingAs($this->user)->post("/voter/vote/{$this->election->id}", [
            'choices' => [$this->candidates[0]->id]
        ]);

        // Second vote attempt
        $response = $this->actingAs($this->user)->post("/voter/vote/{$this->election->id}", [
            'choices' => [$this->candidates[1]->id]
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'error' => 'You have already voted in this election'
        ]);
    }

    public function test_voter_cannot_vote_in_inactive_election()
    {
        // Make election inactive
        $this->election->update(['status' => 'closed']);

        $response = $this->actingAs($this->user)->post("/voter/vote/{$this->election->id}", [
            'choices' => [$this->candidates[0]->id]
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'error' => 'Election is not active'
        ]);
    }

    public function test_voter_cannot_vote_with_invalid_candidate()
    {
        $response = $this->actingAs($this->user)->post("/voter/vote/{$this->election->id}", [
            'choices' => [999] // Non-existent candidate ID
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Invalid candidate(s) selected'
        ]);
    }

    public function test_voter_can_get_active_elections()
    {
        $response = $this->actingAs($this->user)->get('/voter/active-elections');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'description',
                'start_date',
                'end_date',
                'status',
                'has_voted',
                'time_remaining'
            ]
        ]);
    }
}
