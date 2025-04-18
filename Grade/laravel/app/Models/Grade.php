<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'course_id', 'grade', 'academic_year', 'semester', 'notes'
    ];
}