<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return response()->json(['data' => $grades]);
    }

    public function show($id)
    {
        $grade = Grade::findOrFail($id);
        return response()->json(['data' => $grade]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'score' => 'required|numeric|min:0|max:100',
            'grade' => 'required|string|max:2',
            'semester' => 'required|string',
        ]);

        // Verifikasi student dari StudentService
        try {
            $studentResponse = Http::get('http://127.0.0.1:8001/api/v1/students/' . $validated['student_id']);
            if ($studentResponse->failed()) {
                return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'StudentService tidak tersedia'], 503);
        }

        // Verifikasi course dari CourseService
        try {
            $courseResponse = Http::get('http://127.0.0.1:8002/api/v1/courses/' . $validated['course_id']);
            if ($courseResponse->failed()) {
                return response()->json(['message' => 'Mata kuliah tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'CourseService tidak tersedia'], 503);
        }

        // Cek jika nilai sudah ada
        $existingGrade = Grade::where('student_id', $validated['student_id'])
                             ->where('course_id', $validated['course_id'])
                             ->where('semester', $validated['semester'])
                             ->first();
                             
        if ($existingGrade) {
            return response()->json(['message' => 'Nilai untuk mata kuliah ini sudah ada'], 422);
        }

        $grade = Grade::create($validated);
        return response()->json(['data' => $grade], 201);
    }

    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);
        $validated = $request->validate([
            'score' => 'numeric|min:0|max:100',
            'grade' => 'string|max:2',
            'semester' => 'string',
        ]);

        $grade->update($validated);
        return response()->json(['data' => $grade]);
    }

    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();
        return response()->json(null, 204);
    }
    
    public function getByStudent($student_id)
    {
        // Verifikasi student dari StudentService
        try {
            $studentResponse = Http::get('http://127.0.0.1:8001/api/v1/students/' . $student_id);
            if ($studentResponse->failed()) {
                return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'StudentService tidak tersedia'], 503);
        }
        
        $grades = Grade::where('student_id', $student_id)->get();
        
        // Tambahkan info course untuk setiap grade
        foreach ($grades as $grade) {
            try {
                $courseResponse = Http::get('http://127.0.0.1:8002/api/v1/courses/' . $grade->course_id);
                if ($courseResponse->successful()) {
                    $grade->course = $courseResponse->json()['data'];
                }
            } catch (\Exception $e) {
                $grade->course = 'CourseService tidak tersedia';
            }
        }
        
        return response()->json(['data' => $grades]);
    }
    
    public function getByCourse($course_id)
    {
        // Verifikasi course dari CourseService
        try {
            $courseResponse = Http::get('http://127.0.0.1:8002/api/v1/courses/' . $course_id);
            if ($courseResponse->failed()) {
                return response()->json(['message' => 'Mata kuliah tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'CourseService tidak tersedia'], 503);
        }
        
        $grades = Grade::where('course_id', $course_id)->get();
        
        // Tambahkan info student untuk setiap grade
        foreach ($grades as $grade) {
            try {
                $studentResponse = Http::get('http://127.0.0.1:8001/api/v1/students/' . $grade->student_id);
                if ($studentResponse->successful()) {
                    $grade->student = $studentResponse->json()['data'];
                }
            } catch (\Exception $e) {
                $grade->student = 'StudentService tidak tersedia';
            }
        }
        
        return response()->json(['data' => $grades]);
    }
    
    public function generateTranscript($student_id)
    {
        // Verifikasi student dari StudentService
        try {
            $studentResponse = Http::get('http://127.0.0.1/api/v1/students/' . $student_id);
            if ($studentResponse->failed()) {
                return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
            }
            $student = $studentResponse->json()['data'];
        } catch (\Exception $e) {
            return response()->json(['message' => 'StudentService tidak tersedia'], 503);
        }
        
        $grades = Grade::where('student_id', $student_id)->get();
        $totalCredits = 0;
        $totalPoints = 0;
        
        // Format transcript data
        $transcriptData = [];
        
        foreach ($grades as $grade) {
            try {
                $courseResponse = Http::get('http://127.0.0.1:8002/api/v1/courses/' . $grade->course_id);
                if ($courseResponse->successful()) {
                    $course = $courseResponse->json()['data'];
                    $credits = $course['credits'];
                    
                    // Konversi grade ke bobot
                    $gradePoint = 0;
                    switch ($grade->grade) {
                        case 'A': $gradePoint = 4.0; break;
                        case 'B+': $gradePoint = 3.5; break;
                        case 'B': $gradePoint = 3.0; break;
                        case 'C+': $gradePoint = 2.5; break;
                        case 'C': $gradePoint = 2.0; break;
                        case 'D': $gradePoint = 1.0; break;
                        case 'E': $gradePoint = 0.0; break;
                    }
                    
                    $points = $credits * $gradePoint;
                    $totalCredits += $credits;
                    $totalPoints += $points;
                    
                    $transcriptData[] = [
                        'semester' => $grade->semester,
                        'course_code' => $course['code'],
                        'course_name' => $course['name'],
                        'credits' => $credits,
                        'score' => $grade->score,
                        'grade' => $grade->grade,
                        'grade_point' => $gradePoint,
                        'points' => $points
                    ];
                }
            } catch (\Exception $e) {
                // Skip jika service tidak tersedia
            }
        }
        
        // Hitung IPK
        $gpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
        
        return response()->json([
            'student' => $student,
            'courses' => $transcriptData,
            'total_credits' => $totalCredits,
            'gpa' => $gpa
        ]);
    }
}