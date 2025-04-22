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
            $table->bigInteger('student_id');
            $table->bigInteger('course_id');
            $table->decimal('score', 5, 2);
            $table->string('academic_year')->nullable();
            $table->enum('semester', ['odd', 'even'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'course_id', 'academic_year', 'semester']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
};