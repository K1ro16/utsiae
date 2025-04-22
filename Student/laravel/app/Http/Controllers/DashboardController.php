<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $externalApiService;

    public function __construct(ExternalApiService $externalApiService)
    {
        $this->externalApiService = $externalApiService;
    }

    public function index()
    {
        $studentCount = Student::count();
        
        // Get course count from Course service
        try {
            $courseServiceUrl = env('COURSE_SERVICE_URL', 'http://127.0.0.1:8002');
            $courseResponse = Http::get($courseServiceUrl . '/api/courses');
            $courseCount = count($courseResponse->json());
        } catch (\Exception $e) {
            $courseCount = 'N/A';
        }
        
        // Get grade count from Grade service
        try {
            $gradeServiceUrl = env('GRADE_SERVICE_URL', 'http://127.0.0.1:8003');
            $gradeResponse = Http::get($gradeServiceUrl . '/api/grades');
            $gradeCount = count($gradeResponse->json());
        } catch (\Exception $e) {
            $gradeCount = 'N/A';
        }
        
        return view('dashboard', compact('studentCount', 'courseCount', 'gradeCount'));
    }

    public function students()
    {
        $students = Student::all();
        return view('students', compact('students'));
    }

    public function courses()
    {
        try {
            $courseServiceUrl = env('COURSE_SERVICE_URL', 'http://127.0.0.1:8002');
            $response = Http::get($courseServiceUrl . '/api/courses');
            $courses = $response->json();
        } catch (\Exception $e) {
            $courses = [];
        }
        
        return view('courses', compact('courses'));
    }

    public function grades()
    {
        try {
            $gradeServiceUrl = env('GRADE_SERVICE_URL', 'http://127.0.0.1:8003');
            $response = Http::get($gradeServiceUrl . '/api/grades');
            $grades = $response->json();
            
            // Enrich grades with student names
            foreach ($grades as &$grade) {
                $student = Student::find($grade['student_id']);
                $grade['student_name'] = $student ? $student->name : 'Unknown';
                
                // Get course details
                $courseServiceUrl = env('COURSE_SERVICE_URL', 'http://127.0.0.1:8002');
                $courseResponse = Http::get($courseServiceUrl . '/api/courses/' . $grade['course_id']);
                $course = $courseResponse->json();
                $grade['course_name'] = $course ? $course['name'] : 'Unknown';
            }
        } catch (\Exception $e) {
            $grades = [];
        }
        
        return view('grades', compact('grades'));
    }

    public function transcript($id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return redirect()->route('students')->with('error', 'Student not found');
        }
        
        $transcript = $this->externalApiService->getGradesForStudent($id);
        
        return view('transcript', compact('student', 'transcript'));
    }
}