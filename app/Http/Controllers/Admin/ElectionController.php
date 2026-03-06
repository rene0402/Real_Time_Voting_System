<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionSettings;
use App\Models\VoterEligibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Election::with('organization');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by organization
        if ($request->has('organization_id') && $request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('organization', function($org) use ($search) {
                      $org->where('name', 'like', "%{$search}%")
                          ->orWhere('short_name', 'like', "%{$search}%");
                  });
            });
        }

        $elections = $query->orderBy('created_at', 'desc')->get();

        // Add computed fields and action buttons for each election
        $elections->transform(function ($election) {
            $election->total_positions = $election->positions()->count();
            $election->total_candidates = $election->candidates()->count();

            $actions = '';

            // View button for all elections
            $actions .= '<button class="action-btn btn-view" onclick="viewElection(' . $election->id . ')">View</button>';

            // Edit button for scheduled and active elections
            if (in_array($election->status, ['scheduled', 'active'])) {
                $actions .= '<button class="action-btn btn-edit" onclick="editElection(' . $election->id . ')">Edit</button>';
            }

            // Status-specific buttons
            switch ($election->status) {
                case 'scheduled':
                    $actions .= '<button class="action-btn btn-approve" onclick="activateElection(' . $election->id . ')">Activate</button>';
                    break;
                case 'active':
                    $actions .= '<button class="action-btn btn-approve" onclick="closeElection(' . $election->id . ')">Close</button>';
                    break;
                case 'closed':
                    $actions .= '<button class="action-btn btn-approve" onclick="publishResults(' . $election->id . ')">Publish</button>';
                    break;
            }

            $election->actions = $actions;
            return $election;
        });

        return response()->json([
            'success' => true,
            'data' => $elections
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'organization_id' => 'nullable|exists:organizations,id',
            'type' => 'required|in:single,multi,referendum',
            'start_date' => 'required|date_format:Y-m-d\TH:i|after:now',
            'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
            'description' => 'nullable|string',
            'voting_method' => 'nullable|in:fptp,ranked_choice,approval',
            'voter_eligibility' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create election with basic settings
        $election = Election::create([
            'title' => $request->title,
            'organization_id' => $request->organization_id,
            'type' => $request->type,
            'status' => 'scheduled',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'voting_method' => $request->voting_method ?? 'fptp',
            'voter_eligibility' => $request->voter_eligibility ? json_encode($request->voter_eligibility) : null
        ]);

        // Create default settings
        ElectionSettings::create([
            'election_id' => $election->id,
            'allow_write_in' => false,
            'show_results_live' => true,
            'max_votes_per_voter' => 1,
            'require_2fa' => false,
            'allow_absentee' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Election created successfully',
            'data' => $election->load(['organization', 'settings'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $election = Election::with(['organization', 'positions.candidates', 'settings', 'voterEligibility'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $election
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $election = Election::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'organization_id' => 'nullable|exists:organizations,id',
            'type' => 'required|in:single,multi,referendum',
            'start_date' => 'required|date_format:Y-m-d\TH:i',
            'end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:start_date',
            'description' => 'nullable|string',
            'voting_method' => 'nullable|in:fptp,ranked_choice,approval',
            'voter_eligibility' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $election->update([
            'title' => $request->title,
            'organization_id' => $request->organization_id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'voting_method' => $request->voting_method,
            'voter_eligibility' => $request->voter_eligibility ? json_encode($request->voter_eligibility) : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Election updated successfully',
            'data' => $election->load(['organization', 'settings'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $election = Election::findOrFail($id);

        if ($election->status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete an active election'
            ], 422);
        }

        $election->delete();

        return response()->json([
            'success' => true,
            'message' => 'Election deleted successfully'
        ]);
    }

    /**
     * Activate an election
     */
    public function activate(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        if ($election->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled elections can be activated'
            ], 422);
        }

        // Check if election has at least one position with candidates
        $hasCandidates = $election->positions()->whereHas('candidates')->exists();
        if (!$hasCandidates) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot activate election without at least one position and candidate'
            ], 422);
        }

        $election->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Election activated successfully'
        ]);
    }

    /**
     * Close an election
     */
    public function close(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        if ($election->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Only active elections can be closed'
            ], 422);
        }

        $election->update(['status' => 'closed']);

        return response()->json([
            'success' => true,
            'message' => 'Election closed successfully'
        ]);
    }

    /**
     * Pause an election
     */
    public function pause(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        if ($election->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Only active elections can be paused'
            ], 422);
        }

        $election->update(['is_paused' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Election paused successfully'
        ]);
    }

    /**
     * Resume a paused election
     */
    public function resume(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        if (!$election->is_paused) {
            return response()->json([
                'success' => false,
                'message' => 'Election is not paused'
            ], 422);
        }

        $election->update(['is_paused' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Election resumed successfully'
        ]);
    }

    /**
     * Force close an election
     */
    public function forceClose(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        if ($election->status === 'closed') {
            return response()->json([
                'success' => false,
                'message' => 'Election is already closed'
            ], 422);
        }

        $election->update([
            'status' => 'closed',
            'is_paused' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Election force closed successfully'
        ]);
    }

    /**
     * Lock election results
     */
    public function lockResults(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        if ($election->status !== 'closed') {
            return response()->json([
                'success' => false,
                'message' => 'Only closed elections can have results locked'
            ], 422);
        }

        $election->update(['results_locked' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Election results locked successfully'
        ]);
    }

    /**
     * Get election statistics
     */
    public function stats()
    {
        $stats = [
            'total_elections' => Election::count(),
            'active_elections' => Election::active()->count(),
            'scheduled_elections' => Election::scheduled()->count(),
            'closed_elections' => Election::closed()->count(),
            'total_votes' => Election::sum('total_votes'),
            'total_organizations' => Election::whereNotNull('organization_id')->distinct()->count('organization_id'),
            'avg_participation' => 68.5 // This would be calculated based on actual voter data
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get ballot preview for an election
     */
    public function ballotPreview($id)
    {
        $election = Election::with(['organization', 'positions.candidates', 'settings'])->findOrFail($id);

        $ballot = $election->ballot_structure;

        return response()->json([
            'success' => true,
            'data' => [
                'election' => [
                    'id' => $election->id,
                    'title' => $election->title,
                    'organization' => $election->organization,
                    'voting_method' => $election->voting_method ?? 'fptp',
                    'start_date' => $election->start_date,
                    'end_date' => $election->end_date
                ],
                'ballot' => $ballot,
                'total_positions' => $ballot->count(),
                'total_candidates' => $ballot->sum(function($p) { return $p['candidates']->count(); })
            ]
        ]);
    }

    /**
     * Update election settings
     */
    public function updateSettings(Request $request, $id)
    {
        $election = Election::findOrFail($id);
        $settings = $election->settings ?? new ElectionSettings(['election_id' => $id]);

        $validator = Validator::make($request->all(), [
            'allow_write_in' => 'nullable|boolean',
            'show_results_live' => 'nullable|boolean',
            'max_votes_per_voter' => 'nullable|integer|min:1',
            'require_2fa' => 'nullable|boolean',
            'allow_absentee' => 'nullable|boolean',
            'custom_rules' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $settings->fill($request->all())->save();

        return response()->json([
            'success' => true,
            'message' => 'Election settings updated successfully',
            'data' => $settings
        ]);
    }

    /**
     * Set voter eligibility for an election
     */
    public function setVoterEligibility(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'eligibility_type' => 'required|in:email_domain,student_ids,manual,all_members',
            'allowed_domains' => 'nullable|array',
            'allowed_student_ids' => 'nullable|array',
            'organization_id' => 'nullable|exists:organizations,id',
            'require_verification' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $eligibility = VoterEligibility::updateOrCreate(
            ['election_id' => $id],
            [
                'eligibility_type' => $request->eligibility_type,
                'allowed_domains' => json_encode($request->allowed_domains ?? []),
                'allowed_student_ids' => json_encode($request->allowed_student_ids ?? []),
                'organization_id' => $request->organization_id,
                'require_verification' => $request->require_verification ?? true
            ]
        );

        // Also update the voter_eligibility JSON field on election
        $election->update([
            'voter_eligibility' => json_encode([
                'type' => $request->eligibility_type,
                'domains' => $request->allowed_domains ?? [],
                'require_verification' => $request->require_verification ?? true
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voter eligibility configured successfully',
            'data' => $eligibility
        ]);
    }

    /**
     * Get dashboard stats (for the main dashboard)
     */
    public function dashboardStats()
    {
        $stats = [
            'total_voters' => \App\Models\User::where('user_type', 'voter')->count(),
            'votes_cast' => \App\Models\Vote::count(),
            'participation_rate' => 0,
            'active_elections' => Election::active()->count(),
            'voting_progress' => 0
        ];

        if ($stats['total_voters'] > 0) {
            $stats['participation_rate'] = round(($stats['votes_cast'] / $stats['total_voters']) * 100, 1);
            $stats['voting_progress'] = round(($stats['votes_cast'] / $stats['total_voters']) * 100, 1);
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
