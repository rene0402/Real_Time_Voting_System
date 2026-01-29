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
}
