<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Introduction to Programming',
                'code' => 'CS101',
                'credits' => 3
            ],
            [
                'name' => 'Data Structures and Algorithms',
                'code' => 'CS202',
                'credits' => 4
            ],
            [
                'name' => 'Database Systems',
                'code' => 'CS303',
                'credits' => 3
            ],
            [
                'name' => 'Web Development',
                'code' => 'CS404',
                'credits' => 4
            ],
            [
                'name' => 'Artificial Intelligence',
                'code' => 'CS505',
                'credits' => 3
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}