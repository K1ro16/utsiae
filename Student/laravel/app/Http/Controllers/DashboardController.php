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
            // Check if response is valid before counting
            $courseData = $courseResponse->json();
            $courseCount = is_array($courseData) ? count($courseData) : 'N/A';
        } catch (\Exception $e) {
            $courseCount = 'N/A';
        }
        
        // Get grade count from Grade service
        try {
            $gradeServiceUrl = env('GRADE_SERVICE_URL', 'http://127.0.0.1:8003');
            $gradeResponse = Http::get($gradeServiceUrl . '/api/grades');
            // Check if response is valid before counting
            $gradeData = $gradeResponse->json();
            $gradeCount = is_array($gradeData) ? count($gradeData) : 'N/A';
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
            // Ensure courses is an array
            $courses = is_array($courses) ? $courses : [];
        } catch (\Exception $e) {
            $courses = [];
        }
        
        return view('courses', compact('courses'));
    }

    public function grades()
    {
        try {
            $gradeServiceUrl = env('GRADE_SERVICE_URL', 'http://127.0.0.1:8003');
            $gradeResponse = Http::get($gradeServiceUrl . '/api/grades');
            // Check if response is valid before counting
            $gradeData = $gradeResponse->json();
            $gradeCount = is_array($gradeData) ? count($gradeData) : 'N/A';
            
            // Ensure grades is an array before processing
            if (is_array($gradeData)) {
                // Enrich grades with student names
                foreach ($gradeData as &$grade) {
                    $student = Student::find($grade['student_id']);
                    $grade['student_name'] = $student ? $student->name : 'Unknown';
                    
                    // Get course details
                    $courseServiceUrl = env('COURSE_SERVICE_URL', 'http://127.0.0.1:8002');
                    $courseResponse = Http::get($courseServiceUrl . '/api/courses/' . $grade['course_id']);
                    $course = $courseResponse->json();
                    $grade['course_name'] = $course ? $course['name'] : 'Unknown';
                }
            } else {
                $gradeData = [];
            }
        } catch (\Exception $e) {
            $gradeData = [];
        }
        
        return view('grades', compact('gradeData'));
    }

    public function transcript($id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return redirect()->route('students')->with('error', 'Student not found');
        }
        
        $transcript = $this->externalApiService->getGradesForStudent($id);
        // Ensure transcript is an array
        $transcript = is_array($transcript) ? $transcript : [];
        
        return view('transcript', compact('student', 'transcript'));
    }
}