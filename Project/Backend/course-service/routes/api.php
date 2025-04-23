<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;

Route::prefix('api/v1')->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    
    // Enrollment routes
    Route::post('/courses/{course_id}/enroll', [EnrollmentController::class, 'enroll']);
    Route::get('/courses/{course_id}/students', [EnrollmentController::class, 'students']);
});