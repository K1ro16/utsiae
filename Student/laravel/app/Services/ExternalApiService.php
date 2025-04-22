<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    public function getGradesForStudent($studentId)
    {
        try {
            $gradeServiceUrl = env('GRADE_SERVICE_URL', 'http://127.0.0.1:8003');
            $courseServiceUrl = env('COURSE_SERVICE_URL', 'http://127.0.0.1:8002');
            
            $response = Http::get($gradeServiceUrl . '/api/grades/student/' . $studentId);

            if ($response->failed()) {
                Log::error('Failed to retrieve grades', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'studentId' => $studentId
                ]);
                return [];
            }

            $grades = $response->json();
            
            // Enrich grades with course information
            foreach ($grades as &$grade) {
                // Get course details
                $courseResponse = Http::get($courseServiceUrl . '/api/courses/' . $grade['course_id']);
                if (!$courseResponse->failed()) {
                    $course = $courseResponse->json();
                    $grade['course_name'] = $course['name'];
                    $grade['course_code'] = $course['code'];
                    $grade['credits'] = $course['credits'];
                } else {
                    $grade['course_name'] = 'Unknown Course';
                    $grade['course_code'] = 'N/A';
                    $grade['credits'] = 3; // Default value
                }
            }

            return $grades;
        } catch (\Exception $e) {
            Log::error('Error calling Grade service', [
                'error' => $e->getMessage(),
                'studentId' => $studentId
            ]);
            return [];
        }
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
}