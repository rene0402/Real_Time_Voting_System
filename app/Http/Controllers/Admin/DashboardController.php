<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Election;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch voters
        $voters = User::where('user_type', 'voter')->orderBy('created_at', 'desc')->get();

        // Fetch candidates
        $candidates = Candidate::with('election')->orderBy('created_at', 'desc')->get();

        // Fetch elections for filter
        $elections = Election::all();

        // Calculate stats
        $totalVoters = $voters->count();
        $votesCast = \App\Models\Vote::count(); // Assuming Vote model exists
        $participationRate = $totalVoters > 0 ? round(($votesCast / $totalVoters) * 100, 1) : 0;
        $activeElections = Election::where('status', 'active')->count();

        return view('admin.admin-dashboard', compact('voters', 'candidates', 'elections', 'totalVoters', 'votesCast', 'participationRate', 'activeElections'));
    }
}
