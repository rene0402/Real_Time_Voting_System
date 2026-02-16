<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the actual election IDs from the database
        $presidentialElection = \App\Models\Election::where('title', 'Presidential Election 2024')->first();
        $boardElection = \App\Models\Election::where('title', 'Board of Directors Election')->first();

        if ($presidentialElection) {
            // Candidates for Presidential Election
            Candidate::create([
                'election_id' => $presidentialElection->id,
                'name' => 'John Smith',
                'description' => 'Focus on digital transformation and member welfare. 10 years experience in leadership.',
                'photo_url' => 'ImageCandidate/joecel.jpg'
            ]);

            Candidate::create([
                'election_id' => $presidentialElection->id,
                'name' => 'Jane Doe',
                'description' => 'Advocate for transparency and financial reform. Former finance committee chair.',
                'photo_url' => 'ImageCandidate/merlou.jpg'
            ]);

            Candidate::create([
                'election_id' => $presidentialElection->id,
                'name' => 'Robert Johnson',
                'description' => 'Running on platform of innovation and youth engagement. Tech entrepreneur.',
                'photo_url' => 'ImageCandidate/ray.jpg'
            ]);
        }

        if ($boardElection) {
            // Candidates for Board of Directors Election
            Candidate::create([
                'election_id' => $boardElection->id,
                'name' => 'Maria Garcia',
                'description' => '15 years in organizational management. Focus on sustainable growth.',
                'photo_url' => 'ImageCandidate/Ren.jpg'
            ]);

            Candidate::create([
                'election_id' => $boardElection->id,
                'name' => 'David Chen',
                'description' => 'Tech professional bringing modern solutions to traditional challenges.',
                'photo_url' => 'ImageCandidate/sherwin.jpg'
            ]);

            Candidate::create([
                'election_id' => $boardElection->id,
                'name' => 'Sarah Wilson',
                'description' => 'Community advocate with strong background in volunteer coordination.',
                'photo_url' => 'ImageCandidate/1770640579_merlou.jpg'
            ]);

            Candidate::create([
                'election_id' => $boardElection->id,
                'name' => 'Michael Brown',
                'description' => 'Financial expert specializing in non-profit management and fundraising.',
                'photo_url' => 'ImageCandidate/joecel.jpg'
            ]);

            Candidate::create([
                'election_id' => $boardElection->id,
                'name' => 'Lisa Anderson',
                'description' => 'Education specialist focused on professional development programs.',
                'photo_url' => 'ImageCandidate/merlou.jpg'
            ]);
        }
    }
}
