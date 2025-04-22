<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    protected $externalApiService;

    public function __construct(ExternalApiService $externalApiService)
    {
        $this->externalApiService = $externalApiService;
    }

    public function index()
    {
        $grades = Grade::all();
        return response()->json($grades);
    }

    public function show($id)
    {
        $grade = Grade::find($id);

        if (!$grade) {
            return response()->json(['message' => 'Grade not found'], 404);
        }

        return response()->json($grade);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'score' => 'required|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate that student exists via Student API
        if (!$this->externalApiService->validateStudent($request->student_id)) {
            return response()->json(['message' => 'Student does not exist'], 422);
        }

        // Validate that course exists via Course API
        if (!$this->externalApiService->validateCourse($request->course_id)) {
            return response()->json(['message' => 'Course does not exist'], 422);
        }

        $grade = Grade::create($request->all());

        return response()->json($grade, 201);
    }

    public function getStudentGrades($studentId)
    {
        // Validate that student exists
        if (!$this->externalApiService->validateStudent($studentId)) {
            return response()->json(['message' => 'Student does not exist'], 404);
        }

        $grades = Grade::where('student_id', $studentId)->get();

        // Enrich grades with course information
        $enrichedGrades = $grades->map(function ($grade) {
            $courseDetails = $this->externalApiService->getCourseDetails($grade->course_id);
            
            return [
                'id' => $grade->id,
                'student_id' => $grade->student_id,
                'course_id' => $grade->course_id,
                'course_name' => $courseDetails ? $courseDetails['name'] : 'Unknown Course',
                'score' => $grade->score,
                'created_at' => $grade->created_at,
                'updated_at' => $grade->updated_at,
            ];
        });

        return response()->json($enrichedGrades);
    }
}