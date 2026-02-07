<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $elections = Election::orderBy('created_at', 'desc')->get();
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
            'type' => 'required|in:single,multi,referendum',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $election = Election::create([
            'title' => $request->title,
            'type' => $request->type,
            'status' => 'scheduled',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Election created successfully',
            'data' => $election
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $election = Election::findOrFail($id);
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
            'type' => 'required|in:single,multi,referendum',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string'
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
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Election updated successfully',
            'data' => $election
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
            'total_votes' => Election::sum('total_votes'),
            'avg_participation' => 68.5 // This would be calculated based on actual voter data
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
