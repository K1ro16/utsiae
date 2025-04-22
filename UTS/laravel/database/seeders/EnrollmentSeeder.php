<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = [
            [
                'student_id' => 1,
                'course_id' => 1
            ],
            [
                'student_id' => 1,
                'course_id' => 2
            ],
            [
                'student_id' => 2,
                'course_id' => 1
            ],
            [
                'student_id' => 2,
                'course_id' => 3
            ],
            [
                'student_id' => 3,
                'course_id' => 2
            ],
            [
                'student_id' => 3,
                'course_id' => 4
            ],
            [
                'student_id' => 4,
                'course_id' => 3
            ],
            [
                'student_id' => 5,
                'course_id' => 4
            ]
        ];

        foreach ($enrollments as $enrollment) {
            Enrollment::create($enrollment);
        }
    }
}