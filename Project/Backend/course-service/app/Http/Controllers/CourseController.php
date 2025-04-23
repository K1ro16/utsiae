<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return response()->json(['data' => $courses]);
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json(['data' => $course]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:courses,code',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'schedule' => 'required|string',
        ]);

        $course = Course::create($validated);
        return response()->json(['data' => $course], 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $validated = $request->validate([
            'code' => 'string|unique:courses,code,' . $id,
            'name' => 'string|max:255',
            'credits' => 'integer|min:1',
            'schedule' => 'string',
        ]);

        $course->update($validated);
        return response()->json(['data' => $course]);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(null, 204);
    }
}
