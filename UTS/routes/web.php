<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{id}', [CourseController::class, 'update']);
Route::post('/enrollments', [CourseController::class, 'enroll']);
Route::get('/courses/{id}/enrollments', [CourseController::class, 'getEnrollments']);

Route::get('/', function () {
    return view('welcome');
});
