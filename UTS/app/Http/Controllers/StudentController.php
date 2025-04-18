<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // Provider Methods
    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json($student);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|unique:students',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'faculty' => 'required|string',
            'major' => 'required|string',
            'entry_year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student = Student::create($request->all());

        return response()->json($student, 201);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:students,email,'.$id,
            'faculty' => 'string',
            'major' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student->update($request->all());

        return response()->json($student);
    }

    // Consumer Method
    public function getTranscript($studentId)
    {
        try {
            $gradeServiceUrl = env('GRADE_SERVICE_URL', 'http://127.0.0.1:8003');
            $response = Http::get($gradeServiceUrl . '/api/grades/student/' . $studentId);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Failed to retrieve grades from GradeService',
                    'error' => $response->body()
                ], $response->status());
            }

            $student = Student::find($studentId);
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            $transcript = [
                'student' => $student,
                'grades' => $response->json()
            ];

            return response()->json($transcript);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Service communication error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
