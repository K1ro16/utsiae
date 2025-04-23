<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        // Course::truncate();
        
        // Buat data mata kuliah
        $courses = [
            [
                'code' => 'CS101',
                'name' => 'Pengantar Ilmu Komputer',
                'credits' => 3,
                'schedule' => 'Senin, 08:00 - 10:30'
            ],
            [
                'code' => 'CS102',
                'name' => 'Algoritma dan Pemrograman',
                'credits' => 4,
                'schedule' => 'Selasa, 13:00 - 16:30'
            ],
            [
                'code' => 'CS201',
                'name' => 'Struktur Data',
                'credits' => 3,
                'schedule' => 'Rabu, 08:00 - 10:30'
            ],
            [
                'code' => 'CS202',
                'name' => 'Basis Data',
                'credits' => 4,
                'schedule' => 'Kamis, 10:30 - 14:00'
            ],
            [
                'code' => 'CS301',
                'name' => 'Pemrograman Web',
                'credits' => 3,
                'schedule' => 'Jumat, 13:00 - 15:30'
            ],
        ];
        
        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}