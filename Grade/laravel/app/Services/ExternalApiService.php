<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    public function getStudentDetails($studentId)
    {
        try {
            $studentServiceUrl = env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8001');
            $response = Http::get($studentServiceUrl . '/api/students/' . $studentId);

            if ($response->failed()) {
                Log::error('Failed to retrieve student details', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'studentId' => $studentId
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error calling Student service', [
                'error' => $e->getMessage(),
                'studentId' => $studentId
            ]);
            return null;
        }
    }

    public function validateStudent($studentId)
    {
        $student = $this->getStudentDetails($studentId);
        return !is_null($student);
    }

    public function getCourseDetails($courseId)
    {
        try {
            $courseServiceUrl = env('COURSE_SERVICE_URL', 'http://127.0.0.1:8002');
            $response = Http::get($courseServiceUrl . '/api/courses/' . $courseId);

            if ($response->failed()) {
                Log::error('Failed to retrieve course details', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'courseId' => $courseId
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error calling Course service', [
                'error' => $e->getMessage(),
                'courseId' => $courseId
            ]);
            return null;
        }
    }
    
    public function validateCourse($courseId)
    {
        $course = $this->getCourseDetails($courseId);
        return !is_null($course);
    }
}