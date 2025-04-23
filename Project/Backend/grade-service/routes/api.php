<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradeController;

Route::prefix('api/v1')->group(function () {
    Route::get('/grades', [GradeController::class, 'index']);
    Route::get('/grades/{id}', [GradeController::class, 'show']);
    Route::post('/grades', [GradeController::class, 'store']);
    Route::put('/grades/{id}', [GradeController::class, 'update']);
    Route::delete('/grades/{id}', [GradeController::class, 'destroy']);
    
    // Grades by student
    Route::get('/grades/student/{student_id}', [GradeController::class, 'getByStudent']);
    // Grades by course
    Route::get('/grades/course/{course_id}', [GradeController::class, 'getByCourse']);
    // Generate transcript
    Route::get('/transcript/{student_id}', [GradeController::class, 'generateTranscript']);
});