<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response()->json(['data' => $students]);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        
        // Mengambil data nilai dari GradeService
        try {
            $grades = Http::get('http://127.0.0.1:8003/api/v1/grades/student/' . $id);
            $student->grades = $grades->json()['data'];
        } catch (\Exception $e) {
            $student->grades = 'Service tidak tersedia';
        }
        
        return response()->json(['data' => $student]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:students,nim',
            'email' => 'required|email|unique:students,email',
            'program' => 'required|string'
        ]);

        $student = Student::create($validated);
        return response()->json(['data' => $student], 201);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'nim' => 'string|unique:students,nim,' . $id,
            'email' => 'email|unique:students,email,' . $id,
            'program' => 'string'
        ]);

        $student->update($validated);
        return response()->json(['data' => $student]);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json(null, 204);
    }
}