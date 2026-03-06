<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidate::with(['election', 'position']);

        // Filter by election
        if ($request->has('election_id') && $request->election_id) {
            $query->where('election_id', $request->election_id);
        }

        // Filter by position
        if ($request->has('position_id') && $request->position_id) {
            $query->where('position_id', $request->position_id);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('party_affiliation', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.candidates.index', compact('candidates'));
    }

    public function create(Request $request)
    {
        $elections = Election::all();
        $positions = [];

        if ($request->has('election_id')) {
            $positions = Position::where('election_id', $request->election_id)->ordered()->get();
        }

        return view('admin.candidates.create', compact('elections', 'positions'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'election_id' => 'required|exists:elections,id',
                'position_id' => 'nullable|exists:positions,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'party_affiliation' => 'nullable|string|max:255',
                'manifesto' => 'nullable|string',
                'position' => 'nullable|integer|min:0',
                'order' => 'nullable|integer|min:0',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $photoUrl = null;

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('ImageCandidate'), $filename);
                $photoUrl = 'ImageCandidate/' . $filename;
            } else {
                // Assign random photo if no photo uploaded
                $photoUrl = $this->getRandomPhoto();
            }

            // Get order if not provided
            $order = $request->order ?? Candidate::where('election_id', $request->election_id)
                ->where('position_id', $request->position_id)
                ->max('order') + 1;

            Candidate::create([
                'election_id' => $request->election_id,
                'position_id' => $request->position_id,
                'name' => $request->name,
                'description' => $request->description,
                'party_affiliation' => $request->party_affiliation,
                'manifesto' => $request->manifesto,
                'photo_url' => $photoUrl,
                'position' => $request->position,
                'order' => $order,
            ]);

            // Check if this is an AJAX request or admin request
            $isAjax = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || str_contains($request->url(), 'admin');

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Candidate created successfully.',
                    'redirect' => route('admin.candidates.index')
                ]);
            }

            return redirect()->route('admin.candidates.index')
                ->with('success', 'Candidate created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the candidate.'
                ], 500);
            }
            throw $e;
        }
    }

    public function show(Candidate $candidate)
    {
        $candidate->load(['election', 'position']);
        return view('admin.candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate)
    {
        $elections = Election::all();
        $positions = Position::where('election_id', $candidate->election_id)->ordered()->get();
        return view('admin.candidates.edit', compact('candidate', 'elections', 'positions'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'position_id' => 'nullable|exists:positions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'party_affiliation' => 'nullable|string|max:255',
            'manifesto' => 'nullable|string',
            'position' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'election_id' => $request->election_id,
            'position_id' => $request->position_id,
            'name' => $request->name,
            'description' => $request->description,
            'party_affiliation' => $request->party_affiliation,
            'manifesto' => $request->manifesto,
            'position' => $request->position,
            'order' => $request->order,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if it's not a shared image
            if ($candidate->photo_url && !in_array($candidate->photo_url, [
                'ImageCandidate/joecel.jpg',
                'ImageCandidate/merlou.jpg',
                'ImageCandidate/ray.jpg',
                'ImageCandidate/Ren.jpg',
                'ImageCandidate/sherwin.jpg',
            ])) {
                $oldPhotoPath = public_path($candidate->photo_url);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('ImageCandidate'), $filename);
            $updateData['photo_url'] = 'ImageCandidate/' . $filename;
        }

        $candidate->update($updateData);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        // Delete photo if it's not a shared image
        $sharedImages = [
            'ImageCandidate/joecel.jpg',
            'ImageCandidate/merlou.jpg',
            'ImageCandidate/ray.jpg',
            'ImageCandidate/Ren.jpg',
            'ImageCandidate/sherwin.jpg',
        ];

        if ($candidate->photo_url && !in_array($candidate->photo_url, $sharedImages)) {
            Storage::disk('public')->delete($candidate->photo_url);
        }

        $candidate->delete();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }

    private function getRandomPhoto()
    {
        $photos = [
            'ImageCandidate/joecel.jpg',
            'ImageCandidate/merlou.jpg',
            'ImageCandidate/ray.jpg',
            'ImageCandidate/Ren.jpg',
            'ImageCandidate/sherwin.jpg',
        ];

        return $photos[array_rand($photos)];
    }

    // API Methods for AJAX functionality
    public function apiIndex(Request $request)
    {
        $query = Candidate::with(['election', 'position']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('party_affiliation', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by election
        if ($request->has('election_id') && !empty($request->election_id)) {
            $query->where('election_id', $request->election_id);
        }

        // Filter by position
        if ($request->has('position_id') && !empty($request->position_id)) {
            $query->where('position_id', $request->position_id);
        }

        $candidates = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $candidates->map(function($candidate) {
                return [
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'description' => $candidate->description,
                    'party_affiliation' => $candidate->party_affiliation,
                    'manifesto' => $candidate->manifesto,
                    'position' => $candidate->position,
                    'position_id' => $candidate->position_id,
                    'order' => $candidate->order,
                    'photo_url' => asset($candidate->photo_url),
                    'election' => $candidate->election,
                    'votes' => $candidate->votes()->count(),
                    'created_at' => $candidate->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    public function apiShow(Candidate $candidate)
    {
        $candidate->load(['election', 'position']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'description' => $candidate->description,
                'party_affiliation' => $candidate->party_affiliation,
                'manifesto' => $candidate->manifesto,
                'photo_url' => asset($candidate->photo_url),
                'position' => $candidate->position,
                'position_id' => $candidate->position_id,
                'order' => $candidate->order,
                'election' => $candidate->election,
                'votes' => $candidate->votes()->count(),
                'created_at' => $candidate->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Get positions for a specific election (for AJAX dropdown population)
     */
    public function getPositions($electionId)
    {
        $positions = Position::where('election_id', $electionId)->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $positions
        ]);
    }

    /**
     * Get candidates by position grouped by party
     */
    public function getByPosition($electionId)
    {
        $positions = Position::where('election_id', $electionId)
            ->with(['candidates' => function($q) {
                $q->orderBy('party_affiliation')->orderBy('order');
            }])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $positions->map(function($position) {
                return [
                    'id' => $position->id,
                    'name' => $position->name,
                    'seats_available' => $position->seats_available,
                    'election_type' => $position->election_type,
                    'candidates' => $position->candidates->map(function($candidate) {
                        return [
                            'id' => $candidate->id,
                            'name' => $candidate->name,
                            'party_affiliation' => $candidate->party_affiliation,
                            'photo_url' => asset($candidate->photo_url),
                            'votes' => $candidate->votes()->count()
                        ];
                    }),
                    'candidate_count' => $position->candidates->count()
                ];
            })
        ]);
    }
}
