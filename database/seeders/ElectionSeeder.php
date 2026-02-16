<?php

namespace Database\Seeders;

use App\Models\Election;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Election::create([
            'title' => 'Presidential Election 2024',
            'type' => 'single',
            'status' => 'active',
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(10),
            'description' => 'Vote for the next President of the organization. Candidates have presented their platforms for digital transformation and member welfare.',
            'total_votes' => 1247
        ]);

        Election::create([
            'title' => 'Board of Directors Election',
            'type' => 'multi',
            'status' => 'active',
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(13),
            'description' => 'Elect 5 members to the Board of Directors. Each voter can select up to 5 candidates.',
            'total_votes' => 892
        ]);

        Election::create([
            'title' => 'Student Council Election',
            'type' => 'multi',
            'status' => 'scheduled',
            'start_date' => now()->addDays(15),
            'end_date' => now()->addDays(30),
            'description' => 'Elect student representatives for the upcoming academic year.',
            'total_votes' => 0
        ]);

        Election::create([
            'title' => 'Constitutional Referendum',
            'type' => 'referendum',
            'status' => 'closed',
            'start_date' => now()->subDays(20),
            'end_date' => now()->subDays(10),
            'description' => 'Vote on proposed amendments to the organization\'s constitution regarding membership criteria.',
            'total_votes' => 2156
        ]);

        Election::create([
            'title' => 'Faculty Senate Election',
            'type' => 'multi',
            'status' => 'scheduled',
            'start_date' => now()->addDays(45),
            'end_date' => now()->addDays(60),
            'description' => 'Elect faculty representatives to the senate.',
            'total_votes' => 0
        ]);
    }
}
