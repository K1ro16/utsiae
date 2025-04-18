<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    // Provider Methods
    public function show($id)
    {
        $grade = Grade::find($id);
        
        if (!$grade) {
            return response()->json(['message' => 'Grade not found'], 404);
        }
        
        // Get student and course data to enrich response
        $studentData = $this->getStudentData($grade->student_id);
        $courseData = $this->getCourseData($grade->course_id);
        
        return response()->json([
            'grade' => $grade,
            'student' => $studentData,
            'course' => $courseData
        ]);
    }
    
    public function getStudentGrades($studentId)
    {
        // Validate student exists
        $studentData = $this->getStudentData($studentId);
        if (!$studentData) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        
        $grades = Grade::where('student_id', $studentId)->get();
        $enrichedGrades = [];
        
        foreach ($grades as $grade) {
            $courseData = $this->getCourseData($grade->course_id);
            $enrichedGrades[] = [
                'grade' => $grade,
                'course' => $courseData
            ];
        }
        
        // Calculate GPA
        $totalCredits = 0;
        $totalGradePoints = 0;
        
        foreach ($enrichedGrades as $item) {
            if (isset($item['course']['credits'])) {
                $credits = $item['course']['credits'];
                $gradeValue = $this->letterToPoint($item['grade']['grade']);
                
                $totalCredits += $credits;
                $totalGradePoints += ($credits * $gradeValue);
            }
        }
        
        $gpa = ($totalCredits > 0) ? round($totalGradePoints / $totalCredits, 2) : 0;
        
        return response()->json([
            'student' => $studentData,
            'grades' => $enrichedGrades,
            'gpa' => $gpa
        ]);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'grade' => 'required|string|in:A,A-,B+,B,B-,C+,C,D,E',
            'academic_year' => 'required|string',
            'semester' => 'required|in:odd,even',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Validate student exists
        $studentData = $this->getStudentData($request->student_id);
        if (!$studentData) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        
        // Validate course exists
        $courseData = $this->getCourseData($request->course_id);
        if (!$courseData) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        
        // Check if grade already exists
        $existingGrade = Grade::where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->where('academic_year', $request->academic_year)
            ->where('semester', $request->semester)
            ->first();
            
        if ($existingGrade) {
            return response()->json(['message' => 'Grade already exists for this student and course'], 422);
        }
        
        // Create grade
        $grade = Grade::create($request->all());
        
        return response()->json([
            'grade' => $grade,
            'student' => $studentData,
            'course' => $courseData
        ], 201);
    }
    
    // Consumer Methods
    protected function getStudentData($studentId)
    {
        try {
            $studentServiceUrl = env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8001');
            $response = Http::get($studentServiceUrl . '/api/students/' . $studentId);
            
            if ($response->failed()) {
                return null;
            }
            
            return $response->json();
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    protected function getCourseData($courseId)
    {
        try {
            $courseServiceUrl = env('COURSE_SERVICE_URL', 'http://127.0.0.1:8002');
            $response = Http::get($courseServiceUrl . '/api/courses/' . $courseId);
            
            if ($response->failed()) {
                return null;
            }
            
            return $response->json();
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    // Helper method to convert letter grades to numeric points
    protected function letterToPoint($letter)
    {
        $gradePoints = [
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
        ];
        
        return $gradePoints[$letter] ?? 0;
    }
}