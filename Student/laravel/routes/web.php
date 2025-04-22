<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/students', [DashboardController::class, 'students'])->name('students');
Route::get('/courses', [DashboardController::class, 'courses'])->name('courses');
Route::get('/grades', [DashboardController::class, 'grades'])->name('grades');
Route::get('/students/{id}/transcript', [DashboardController::class, 'transcript'])->name('student.transcript');
