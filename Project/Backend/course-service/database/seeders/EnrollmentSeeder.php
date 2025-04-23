<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        // Enrollment::truncate();
        
        // Periksa ketersediaan StudentService
        try {
            $response = Http::get('http://127.0.0.1:8001/api/v1/students');
            if ($response->successful()) {
                $students = $response->json()['data'];
                
                // Ambil ID course
                $courses = Course::all();
                
                // Generate enrollment untuk kombinasi student-course
                foreach ($students as $student) {
                    // Setiap mahasiswa mengambil 2-3 mata kuliah
                    $courseCount = rand(2, 3);
                    $selectedCourses = $courses->random($courseCount);
                    
                    foreach ($selectedCourses as $course) {
                        Enrollment::create([
                            'student_id' => $student['id'],
                            'course_id' => $course->id
                        ]);
                    }
                }
                
                $this->command->info('Enrollment data berhasil dibuat!');
            } else {
                $this->command->error('StudentService tidak tersedia. Jalankan StudentService terlebih dahulu!');
            }
        } catch (\Exception $e) {
            $this->command->error('StudentService tidak tersedia. Jalankan StudentService terlebih dahulu!');
        }
    }
}
