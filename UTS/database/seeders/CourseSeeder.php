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
                'code' => 'CS101',
                'name' => 'Introduction to Programming',
                'description' => 'Learn the basics of programming using modern languages.',
                'credits' => 3,
                'semester' => 1,
                'faculty' => 'Computer Science',
                'status' => 'active',
            ],
            [
                'code' => 'CS202',
                'name' => 'Data Structures and Algorithms',
                'description' => 'Understand data structures and algorithms for problem-solving.',
                'credits' => 4,
                'semester' => 2,
                'faculty' => 'Computer Science',
                'status' => 'active',
            ],
            [
                'code' => 'CS303',
                'name' => 'Database Systems',
                'description' => 'Learn about relational databases and SQL.',
                'credits' => 3,
                'semester' => 3,
                'faculty' => 'Computer Science',
                'status' => 'active',
            ],
            [
                'code' => 'CS404',
                'name' => 'Web Development',
                'description' => 'Build modern web applications using frameworks.',
                'credits' => 4,
                'semester' => 4,
                'faculty' => 'Computer Science',
                'status' => 'active',
            ],
            [
                'code' => 'CS505',
                'name' => 'Artificial Intelligence',
                'description' => 'Explore the fundamentals of AI and machine learning.',
                'credits' => 3,
                'semester' => 5,
                'faculty' => 'Computer Science',
                'status' => 'active',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}