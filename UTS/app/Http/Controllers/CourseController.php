<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // Provider Methods
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }
    
    public function show($id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        
        return response()->json($course);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:courses',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'credits' => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
            'faculty' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $course = Course::create($request->all());
        
        return response()->json($course, 201);
    }
    
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'credits' => 'integer|min:1',
            'semester' => 'integer|min:1',
            'faculty' => 'string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $course->update($request->all());
        
        return response()->json($course);
    }
    
    // Enrollment Methods
    public function enroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'academic_year' => 'required|string',
            'semester' => 'required|in:odd,even',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Check if course exists
        $course = Course::find($request->course_id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        
        // Validate student exists
        $studentValid = $this->validateStudent($request->student_id);
        if (!$studentValid) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        
        // Check if already enrolled
        $existingEnrollment = Enrollment::where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->where('academic_year', $request->academic_year)
            ->where('semester', $request->semester)
            ->first();
            
        if ($existingEnrollment) {
            return response()->json(['message' => 'Student already enrolled in this course'], 422);
        }
        
        // Create enrollment
        $enrollment = Enrollment::create($request->all());
        
        return response()->json($enrollment, 201);
    }
    
    public function getEnrollments($courseId)
    {
        $course = Course::find($courseId);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        
        $enrollments = Enrollment::where('course_id', $courseId)->get();
        $enrolledStudents = [];
        
        foreach ($enrollments as $enrollment) {
            try {
                $studentServiceUrl = env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8001');
                $response = Http::get($studentServiceUrl . '/api/students/' . $enrollment->student_id);
                
                if (!$response->failed()) {
                    $enrolledStudents[] = [
                        'enrollment' => $enrollment,
                        'student' => $response->json()
                    ];
                }
            } catch (\Exception $e) {
                // Log error but continue with other students
                continue;
            }
        }
        
        return response()->json([
            'course' => $course,
            'enrollments' => $enrolledStudents
        ]);
    }
    
    // Consumer Method
    protected function validateStudent($studentId)
    {
        try {
            $studentServiceUrl = env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8001');
            $response = Http::get($studentServiceUrl . '/api/students/' . $studentId);
            
            return !$response->failed();
            
        } catch (\Exception $e) {
            return false;
        }
    }
}