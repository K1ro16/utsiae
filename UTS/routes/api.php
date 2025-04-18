<?php

use App\Http\Controllers\GradeController;

Route::get('/grades/{id}', [GradeController::class, 'show']);
Route::get('/grades/student/{studentId}', [GradeController::class, 'getStudentGrades']);
Route::post('/grades', [GradeController::class, 'store']);