<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    /**
     * Display a listing of positions for an election.
     */
    public function index(Request $request, $electionId)
    {
        $positions = Position::where('election_id', $electionId)
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $positions
        ]);
    }

    /**
     * Store a newly created position.
     */
    public function store(Request $request, $electionId)
    {
        $election = Election::findOrFail($electionId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'seats_available' => 'required|integer|min:1',
            'election_type' => 'required|in:single_winner,multi_winner'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the next order number if not provided
        $order = $request->order ?? Position::where('election_id', $electionId)->max('order') + 1;

        $position = Position::create([
            'election_id' => $electionId,
            'name' => $request->name,
            'description' => $request->description,
            'order' => $order,
            'seats_available' => $request->seats_available,
            'election_type' => $request->election_type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Position created successfully',
            'data' => $position
        ]);
    }

    /**
     * Display the specified position.
     */
    public function show(string $id)
    {
        $position = Position::with(['election', 'candidates'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $position
        ]);
    }

    /**
     * Update the specified position.
     */
    public function update(Request $request, string $id)
    {
        $position = Position::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'seats_available' => 'required|integer|min:1',
            'election_type' => 'required|in:single_winner,multi_winner'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $position->update([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order,
            'seats_available' => $request->seats_available,
            'election_type' => $request->election_type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Position updated successfully',
            'data' => $position
        ]);
    }

    /**
     * Remove the specified position.
     */
    public function destroy(string $id)
    {
        $position = Position::findOrFail($id);

        // Check if position has candidates
        if ($position->candidates()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete position with associated candidates. Please remove candidates first.'
            ], 422);
        }

        $position->delete();

        return response()->json([
            'success' => true,
            'message' => 'Position deleted successfully'
        ]);
    }

    /**
     * Reorder positions for an election.
     */
    public function reorder(Request $request, $electionId)
    {
        $validator = Validator::make($request->all(), [
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:positions,id',
            'positions.*.order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->positions as $positionData) {
            Position::where('id', $positionData['id'])->update(['order' => $positionData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Positions reordered successfully'
        ]);
    }

    /**
     * Get positions with candidates for ballot preview.
     */
    public function ballotPreview($electionId)
    {
        $election = Election::with(['organization', 'positions.candidates'])->findOrFail($electionId);

        $ballot = $election->positions->map(function($position) {
            // Group candidates by party affiliation
            $candidates = $position->candidates->groupBy('party_affiliation');

            return [
                'id' => $position->id,
                'name' => $position->name,
                'description' => $position->description,
                'seats_available' => $position->seats_available,
                'election_type' => $position->election_type,
                'candidates' => $position->candidates->map(function($candidate) {
                    return [
                        'id' => $candidate->id,
                        'name' => $candidate->name,
                        'party_affiliation' => $candidate->party_affiliation ?? 'Independent',
                        'photo_url' => $candidate->photo_url,
                        'description' => $candidate->description,
                        'manifesto' => $candidate->manifesto
                    ];
                }),
                'candidate_count' => $position->candidates->count()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'election' => [
                    'id' => $election->id,
                    'title' => $election->title,
                    'organization' => $election->organization,
                    'voting_method' => $election->voting_method ?? 'fptp'
                ],
                'ballot' => $ballot,
                'total_positions' => $ballot->count(),
                'total_candidates' => $ballot->sum('candidate_count')
            ]
        ]);
    }
}

