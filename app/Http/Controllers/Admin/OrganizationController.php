<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the organizations.
     */
    public function index(Request $request)
    {
        $query = Organization::query();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_name', 'like', "%{$search}%");
            });
        }

        $organizations = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    /**
     * Store a newly created organization.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50|unique:organizations',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'status' => 'nullable|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $organization = Organization::create([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'color' => $request->color ?? '#004d00',
            'status' => $request->status ?? 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Organization created successfully',
            'data' => $organization
        ]);
    }

    /**
     * Display the specified organization.
     */
    public function show(string $id)
    {
        $organization = Organization::with(['elections', 'voters'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $organization
        ]);
    }

    /**
     * Update the specified organization.
     */
    public function update(Request $request, string $id)
    {
        $organization = Organization::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50|unique:organizations,short_name,' . $id,
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'status' => 'nullable|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $organization->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'color' => $request->color,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Organization updated successfully',
            'data' => $organization
        ]);
    }

    /**
     * Remove the specified organization.
     */
    public function destroy(string $id)
    {
        $organization = Organization::findOrFail($id);

        // Check if organization has active elections
        if ($organization->elections()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete organization with associated elections'
            ], 422);
        }

        $organization->delete();

        return response()->json([
            'success' => true,
            'message' => 'Organization deleted successfully'
        ]);
    }

    /**
     * Get active organizations for dropdowns
     */
    public function getActiveOrganizations()
    {
        $organizations = Organization::active()->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }
}

