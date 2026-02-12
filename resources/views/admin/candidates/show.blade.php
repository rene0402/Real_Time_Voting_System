@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Candidate Details</h2>
                    <a href="{{ route('admin.candidates.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Candidates
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <img src="{{ asset($candidate->photo_url) }}" alt="{{ $candidate->name }}" class="w-full h-64 object-cover rounded-lg">
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Name</h3>
                        <p class="text-gray-700 mb-4">{{ $candidate->name }}</p>

                        <h3 class="text-lg font-medium text-gray-900 mb-2">Election</h3>
                        <p class="text-gray-700 mb-4">{{ $candidate->election->title }}</p>

                        <h3 class="text-lg font-medium text-gray-900 mb-2">Position</h3>
                        <p class="text-gray-700 mb-4">{{ $candidate->position ?? 'N/A' }}</p>

                        <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 mb-4">{{ $candidate->description ?? 'No description provided.' }}</p>

                        <h3 class="text-lg font-medium text-gray-900 mb-2">Created At</h3>
                        <p class="text-gray-700">{{ $candidate->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('admin.candidates.edit', $candidate) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Candidate
                    </a>
                    <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this candidate?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete Candidate
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
