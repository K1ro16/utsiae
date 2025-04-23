<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        Grade::truncate();
        
        // Cek ketersediaan StudentService dan CourseService
        try {
            $studentResponse = Http::get('http://127.0.0.1:8001/api/v1/students');
            $courseResponse = Http::get('http://127.0.0.1:8002/api/v1/courses');
            
            if ($studentResponse->successful() && $courseResponse->successful()) {
                $students = $studentResponse->json()['data'];
                $courses = $courseResponse->json()['data'];
                
                // Semester saat ini
                $currentSemester = 'Genap 2024/2025';
                
                // Coba ambil data enrollment dari CourseService
                try {
                    $enrollmentData = [];
                    
                    foreach ($courses as $course) {
                        $enrollmentResponse = Http::get('http://127.0.0.1:8002/api/v1/courses/' . $course['id'] . '/students');
                        if ($enrollmentResponse->successful()) {
                            $students = $enrollmentResponse->json()['data'];
                            
                            foreach ($students as $student) {
                                $enrollmentData[] = [
                                    'student_id' => $student['id'],
                                    'course_id' => $course['id']
                                ];
                            }
                        }
                    }
                    
                    // Jika berhasil mendapatkan data enrollment, gunakan itu
                    if (count($enrollmentData) > 0) {
                        foreach ($enrollmentData as $enrollment) {
                            $this->createRandomGrade($enrollment['student_id'], $enrollment['course_id'], $currentSemester);
                        }
                        
                        $this->command->info('Grade data berhasil dibuat berdasarkan enrollment!');
                        return;
                    }
                } catch (\Exception $e) {
                    $this->command->error('Gagal mengambil data enrollment. Membuat grade dengan data acak...');
                }
                
                // Fallback: Buat data nilai secara acak
                foreach ($students as $student) {
                    $courseCount = rand(2, 4);
                    $selectedCourses = collect($courses)->random($courseCount);
                    
                    foreach ($selectedCourses as $course) {
                        $this->createRandomGrade($student['id'], $course['id'], $currentSemester);
                    }
                }
                
                $this->command->info('Grade data berhasil dibuat!');
            } else {
                $this->command->error('StudentService atau CourseService tidak tersedia. Jalankan semua service terlebih dahulu!');
            }
        } catch (\Exception $e) {
            $this->command->error('Komunikasi dengan service lain gagal: ' . $e->getMessage());
        }
    }
    
    /**
     * Buat nilai acak untuk student dan course
     */
    private function createRandomGrade($studentId, $courseId, $semester)
    {
        $score = rand(55, 100);
        $grade = $this->scoreToGrade($score);
        
        Grade::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'score' => $score,
            'grade' => $grade,
            'semester' => $semester
        ]);
    }
    
    /**
     * Konversi nilai numerik ke grade huruf
     */
    private function scoreToGrade($score)
    {
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'C+';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'E';
    }
}