<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'credits', 'schedule'];
    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}