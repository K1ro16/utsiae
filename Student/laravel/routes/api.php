<?php

use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;

//Course
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{id}', [CourseController::class, 'update']);
Route::post('/enrollments', [CourseController::class, 'enroll']);
Route::get('/courses/{id}/enrollments', [CourseController::class, 'getEnrollments']);

//grades
Route::get('/grades/{id}', [GradeController::class, 'show']);
Route::get('/grades/student/{studentId}', [GradeController::class, 'getStudentGrades']);
Route::post('/grades', [GradeController::class, 'store']);


//Student
Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show']);
Route::post('/students', [StudentController::class, 'store']);
Route::put('/students/{id}', [StudentController::class, 'update']);
Route::get('/students/{id}/transcript', [StudentController::class, 'getTranscript']);
