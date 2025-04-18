<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->bigInteger('course_id');
            $table->string('academic_year');
            $table->enum('semester', ['odd', 'even']);
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->timestamps();
            
            $table->unique(['student_id', 'course_id', 'academic_year', 'semester']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}