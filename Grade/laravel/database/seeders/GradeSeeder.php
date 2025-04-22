<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            [
                'student_id' => 1,
                'course_id' => 1,
                'score' => 85.5
            ],
            [
                'student_id' => 1,
                'course_id' => 2,
                'score' => 92.0
            ],
            [
                'student_id' => 2,
                'course_id' => 1,
                'score' => 78.5
            ],
            [
                'student_id' => 2,
                'course_id' => 3,
                'score' => 88.0
            ],
            [
                'student_id' => 3,
                'course_id' => 2,
                'score' => 91.5
            ],
            [
                'student_id' => 3,
                'course_id' => 4,
                'score' => 76.0
            ],
            [
                'student_id' => 4,
                'course_id' => 3,
                'score' => 95.0
            ],
            [
                'student_id' => 5,
                'course_id' => 4,
                'score' => 82.5
            ]
        ];

        foreach ($grades as $grade) {
            Grade::create($grade);
        }
    }
}