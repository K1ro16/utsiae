<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->bigInteger('course_id');
            $table->enum('grade', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E']);
            $table->string('academic_year');
            $table->enum('semester', ['odd', 'even']);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'course_id', 'academic_year', 'semester']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
}