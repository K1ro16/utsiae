<?php


use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

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
\
