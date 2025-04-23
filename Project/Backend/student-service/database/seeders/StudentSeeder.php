<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        Student::truncate();
        
        // Buat data mahasiswa
        $students = [
            [
                'name' => 'Ahmad Fauzi',
                'nim' => '101010001',
                'email' => 'ahmad.fauzi@example.com',
                'program' => 'Teknik Informatika'
            ],
            [
                'name' => 'Budi Santoso',
                'nim' => '101010002',
                'email' => 'budi.santoso@example.com',
                'program' => 'Sistem Informasi'
            ],
            [
                'name' => 'Cindy Wijaya',
                'nim' => '101010003',
                'email' => 'cindy.wijaya@example.com',
                'program' => 'Teknik Informatika'
            ],
            [
                'name' => 'Dian Putri',
                'nim' => '101010004',
                'email' => 'dian.putri@example.com',
                'program' => 'Sistem Informasi'
            ],
            [
                'name' => 'Eko Prasetyo',
                'nim' => '101010005',
                'email' => 'eko.prasetyo@example.com',
                'program' => 'Ilmu Komputer'
            ],
        ];
        
        foreach ($students as $student) {
            Student::create($student);
        }
    }
}