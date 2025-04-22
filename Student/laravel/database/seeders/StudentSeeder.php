<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'faculty' => 'Engineering',
                'major' => 'Computer Science'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'faculty' => 'Business',
                'major' => 'Finance'
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'faculty' => 'Arts',
                'major' => 'Graphic Design'
            ],
            [
                'name' => 'Emily Brown',
                'email' => 'emily.brown@example.com',
                'faculty' => 'Science',
                'major' => 'Biology'
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael.johnson@example.com',
                'faculty' => 'Engineering',
                'major' => 'Mechanical Engineering'
            ]
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}