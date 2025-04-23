<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
        $validated = $request->validate([
            'student_id' => 'required|integer',
        ]);
        
        // Verifikasi student dari StudentService
        try {
            $response = Http::get('http://127.0.0.1:8001/api/v1/students/' . $validated['student_id']);
            
            if ($response->failed()) {
                return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
            }
            
            // Cek jika sudah terdaftar
            $enrollment = Enrollment::where('course_id', $course_id)
                                   ->where('student_id', $validated['student_id'])
                                   ->first();
            
            if ($enrollment) {
                return response()->json(['message' => 'Mahasiswa sudah terdaftar di mata kuliah ini'], 422);
            }
            
            // Buat enrollment baru
            $enrollment = Enrollment::create([
                'course_id' => $course_id,
                'student_id' => $validated['student_id'],
            ]);
            
            return response()->json(['data' => $enrollment], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'StudentService tidak tersedia'], 503);
        }
    }
    
    public function students($course_id)
    {
        $course = Course::findOrFail($course_id);
        $enrollments = Enrollment::where('course_id', $course_id)->get();
        
        $students = [];
        foreach ($enrollments as $enrollment) {
            try {
                $response = Http::get('http://127.0.0.1:8001/api/v1/students/' . $enrollment->student_id);
                if ($response->successful()) {
                    $students[] = $response->json()['data'];
                }
            } catch (\Exception $e) {
                // Skip jika service tidak tersedia
            }
        }
        
        return response()->json(['data' => $students]);
    }
}
