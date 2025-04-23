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
                'course_id' => 1,
                'academic_year' => '2024/2025',
                'semester' => 'odd',
                'status' => 'active',
            ],
            [
                'student_id' => 1,
                'course_id' => 2,
                'academic_year' => '2024/2025',
                'semester' => 'odd',
                'status' => 'active',
            ],
            [
                'student_id' => 2,
                'course_id' => 3,
                'academic_year' => '2024/2025',
                'semester' => 'even',
                'status' => 'active',
            ],
            [
                'student_id' => 3,
                'course_id' => 4,
                'academic_year' => '2024/2025',
                'semester' => 'odd',
                'status' => 'active',
            ],
            [
                'student_id' => 4,
                'course_id' => 5,
                'academic_year' => '2024/2025',
                'semester' => 'even',
                'status' => 'active',
            ],
        ];

        foreach ($enrollments as $enrollment) {
            Enrollment::create($enrollment);
        }
    }
}