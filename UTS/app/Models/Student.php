<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nim', 'name', 'email', 'faculty', 'major', 'entry_year', 'status'
    ];
}
