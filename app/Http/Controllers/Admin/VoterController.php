<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class VoterController extends Controller
{
    public function index()
    {
        // Get all users with voter role (assuming user_type = 'voter')
        $voters = User::where('user_type', 'voter')->paginate(15);

        return view('admin.voter-management', compact('voters'));
    }

    public function show($id)
    {
        $voter = User::findOrFail($id);

        return view('admin.voter-detail', compact('voter'));
    }

    public function edit($id)
    {
        $voter = User::findOrFail($id);

        return view('admin.voter-edit', compact('voter'));
    }

    public function update(Request $request, $id)
    {
        $voter = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($voter->id)],
            'user_type' => ['required', 'in:voter,admin'],
        ]);

        $updateData = $request->only(['name', 'email', 'user_type']);

        // Handle email verification checkbox properly
        if ($request->has('email_verified') && $request->email_verified == '1') {
            $updateData['email_verified_at'] = now();
        } else {
            $updateData['email_verified_at'] = null;
        }

        $voter->update($updateData);

        return redirect()->route('admin.voter-management.index')->with('success', 'Voter updated successfully');
    }

    public function approve($id)
    {
        $voter = User::findOrFail($id);

        // Mark as verified
        $voter->update([
            'email_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Voter approved successfully');
    }

    public function block($id)
    {
        $voter = User::findOrFail($id);

        // Block the voter (you might want to add a status field to users table)
        // For now, we'll just update the user_type or add a blocked_at timestamp
        $voter->update([
            'blocked_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Voter blocked successfully');
    }

    public function unblock($id)
    {
        $voter = User::findOrFail($id);

        $voter->update([
            'blocked_at' => null,
        ]);

        return redirect()->back()->with('success', 'Voter unblocked successfully');
    }

    public function destroy($id)
    {
        $voter = User::findOrFail($id);
        $voter->delete();

        return redirect()->route('admin.voter-management.index')->with('success', 'Voter deleted successfully');
    }

    public function create()
    {
        return view('admin.voter-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:voter,admin'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        return redirect()->route('admin.voter-management.index')->with('success', 'Voter created successfully');
    }

    // API Methods for AJAX functionality
    public function apiIndex(Request $request)
    {
        $query = User::where('user_type', 'voter');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $voters = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $voters->map(function($voter) {
                return [
                    'id' => $voter->id,
                    'name' => $voter->name,
                    'email' => $voter->email,
                    'status' => $this->getVoterStatus($voter),
                    'verified' => $voter->email_verified_at ? 'Yes' : 'No',
                    'voted' => $this->hasVoted($voter) ? 'Yes' : 'No',
                    'last_login' => $voter->last_login_at ? $voter->last_login_at->format('Y-m-d H:i') : 'Never',
                    'actions' => $this->getActionButtons($voter)
                ];
            })
        ]);
    }

    public function apiApprove($id)
    {
        $voter = User::findOrFail($id);

        $voter->update([
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voter approved successfully'
        ]);
    }

    public function apiBlock($id)
    {
        $voter = User::findOrFail($id);

        $voter->update([
            'blocked_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voter blocked successfully'
        ]);
    }

    public function apiUnblock($id)
    {
        $voter = User::findOrFail($id);

        $voter->update([
            'blocked_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voter unblocked successfully'
        ]);
    }

    public function apiDestroy($id)
    {
        $voter = User::findOrFail($id);
        $voter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voter deleted successfully'
        ]);
    }

    // Helper methods
    private function getVoterStatus($voter)
    {
        if ($voter->blocked_at) {
            return 'Blocked';
        } elseif ($voter->email_verified_at) {
            return 'Verified';
        } else {
            return 'Pending';
        }
    }

    private function hasVoted($voter)
    {
        // This would need to be implemented based on your voting system
        // For now, return false
        return false;
    }

    private function getActionButtons($voter)
    {
        $buttons = [];

        if (!$voter->email_verified_at) {
            $buttons[] = '<button class="action-btn btn-approve" onclick="approveVoter(' . $voter->id . ')"><i class="fas fa-check"></i></button>';
        }

        $buttons[] = '<button class="action-btn btn-view" onclick="viewVoter(' . $voter->id . ')"><i class="fas fa-eye"></i></button>';
        $buttons[] = '<button class="action-btn btn-edit" onclick="editVoter(' . $voter->id . ')"><i class="fas fa-edit"></i></button>';

        if ($voter->blocked_at) {
            $buttons[] = '<button class="action-btn btn-approve" onclick="unblockVoter(' . $voter->id . ')"><i class="fas fa-unlock"></i></button>';
        } else {
            $buttons[] = '<button class="action-btn btn-delete" onclick="blockVoter(' . $voter->id . ')"><i class="fas fa-ban"></i></button>';
        }

        $buttons[] = '<button class="action-btn btn-delete" onclick="deleteVoter(' . $voter->id . ')"><i class="fas fa-times"></i></button>';

        return implode(' ', $buttons);
    }
}
