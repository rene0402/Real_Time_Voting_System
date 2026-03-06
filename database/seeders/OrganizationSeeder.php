<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'Student Supreme Government',
                'short_name' => 'SSG',
                'description' => 'The highest student governing body in the university. Represents all students and organizes major campus activities.',
                'color' => '#004d00',
                'status' => 'active'
            ],
            [
                'name' => 'Freshman Leadership Program',
                'short_name' => 'FLP',
                'description' => 'A leadership development program for freshman students to develop their skills and serve the campus community.',
                'color' => '#6f42c1',
                'status' => 'active'
            ],
            [
                'name' => 'Engineering Society',
                'short_name' => 'EngSoc',
                'description' => 'The official organization for engineering students, promoting technical excellence and professional development.',
                'color' => '#f39c12',
                'status' => 'active'
            ],
            [
                'name' => 'College of Arts and Sciences Student Council',
                'short_name' => 'CASS',
                'description' => 'Represents students in the College of Arts and Sciences, organizing academic and cultural activities.',
                'color' => '#28a745',
                'status' => 'active'
            ],
            [
                'name' => 'Business Management Student Association',
                'short_name' => 'BMSA',
                'description' => 'An organization for business management students focusing on entrepreneurship and leadership.',
                'color' => '#3498db',
                'status' => 'active'
            ],
            [
                'name' => 'Education Student Alliance',
                'short_name' => 'ESA',
                'description' => 'Supporting future educators with teaching practices and community outreach programs.',
                'color' => '#e74c3c',
                'status' => 'active'
            ],
            [
                'name' => 'Computer Science Society',
                'short_name' => 'CSS',
                'description' => 'A community for CS students to collaborate on tech projects and host programming competitions.',
                'color' => '#2c3e50',
                'status' => 'active'
            ],
            [
                'name' => 'Agricultural Sciences Guild',
                'short_name' => 'ASG',
                'description' => 'Promoting agricultural education and sustainable farming practices among students.',
                'color' => '#27ae60',
                'status' => 'active'
            ]
        ];

        foreach ($organizations as $org) {
            Organization::create($org);
        }
    }
}

