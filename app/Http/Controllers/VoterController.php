<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VoterController extends Controller
{
    public function dashboard()
    {
        $elections = Election::active()->get();
        $user = Auth::user();

        // Get voting status for each election
        $electionsWithStatus = $elections->map(function ($election) use ($user) {
            $hasVoted = $election->votes()->where('user_id', $user->id)->exists();
            $election->has_voted = $hasVoted;
            return $election;
        });

        return view('voter.voter-dashboard', compact('electionsWithStatus'));
    }

    public function vote(Request $request, Election $election)
    {
        // Check if election is active
        if (!$election->is_active) {
            return response()->json(['error' => 'Election is not active'], 403);
        }

        // Check if user has already voted
        if ($election->votes()->where('user_id', Auth::id())->exists()) {
            return response()->json(['error' => 'You have already voted in this election'], 403);
        }

        // Validate the vote
        $request->validate([
            'choices' => 'required|array|min:1'
        ]);

        // Get valid candidate IDs for this election
        $validCandidateIds = $election->candidates()->pluck('id')->toArray();

        // Check if all choices are valid candidates
        $invalidChoices = array_diff($request->choices, $validCandidateIds);
        if (!empty($invalidChoices)) {
            return response()->json(['error' => 'Invalid candidate(s) selected'], 400);
        }

        // Create the vote
        $vote = Vote::create([
            'user_id' => Auth::id(),
            'election_id' => $election->id,
            'choices' => $request->choices,
            'reference_code' => 'VREF-' . strtoupper(Str::random(8)),
            'voted_at' => now()
        ]);

        // Update election total votes
        $election->increment('total_votes');

        return response()->json([
            'success' => true,
            'reference_code' => $vote->reference_code,
            'message' => 'Vote recorded successfully'
        ]);
    }

    public function getActiveElections()
    {
        $elections = Election::active()->get();
        $user = Auth::user();

        $electionsData = $elections->map(function ($election) use ($user) {
            return [
                'id' => $election->id,
                'title' => $election->title,
                'description' => $election->description,
                'start_date' => $election->start_date->format('M d, Y H:i'),
                'end_date' => $election->end_date->format('M d, Y H:i'),
                'status' => $election->status,
                'has_voted' => $election->votes()->where('user_id', $user->id)->exists(),
                'time_remaining' => $election->time_remaining
            ];
        });

        return response()->json($electionsData);
    }

    public function getCandidates(Election $election)
    {
        // Check if election is active
        if (!$election->is_active) {
            return response()->json(['error' => 'Election is not active'], 403);
        }

        $candidates = $election->candidates()->get();

        $candidatesData = $candidates->map(function ($candidate) {
            $photoUrl = $candidate->getOriginal('photo_url');
            if (!$photoUrl) {
                $photoFiles = glob(public_path('ImageCandidate') . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                if (!empty($photoFiles)) {
                    $photoUrl = 'ImageCandidate/' . basename($photoFiles[array_rand($photoFiles)]);
                }
            }
            return [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'description' => $candidate->description,
                'photo_url' => $photoUrl,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $candidatesData
        ]);
    }
}
