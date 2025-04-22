<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    protected $externalApiService;

    public function __construct(ExternalApiService $externalApiService)
    {
        $this->externalApiService = $externalApiService;
    }

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'faculty' => 'required|string',
            'major' => 'required|string',
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
            $student = Student::find($studentId);
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            // Get grades from Grade service using our helper
            $grades = $this->externalApiService->getGradesForStudent($studentId);
            
            if ($grades === null) {
                return response()->json([
                    'message' => 'Failed to retrieve grades from GradeService'
                ], 500);
            }

            // Prepare transcript
            $transcript = [
                'student' => $student,
                'grades' => $grades
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
