<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('score', 5, 2);
            $table->string('grade', 2);
            $table->string('semester');
            $table->timestamps();
            
            $table->unique(['student_id', 'course_id', 'semester']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
};