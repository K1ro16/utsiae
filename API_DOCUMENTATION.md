# Dokumentasi API Sistem Manajemen Mahasiswa

## Gambaran Umum
Sistem ini terdiri dari tiga layanan mikro:
- **Layanan Mahasiswa**: Mengelola informasi mahasiswa
- **Layanan Nilai**: Mengelola nilai mahasiswa
- **Layanan Mata Kuliah**: Mengelola mata kuliah dan pendaftaran

## Menjalankan Layanan
1. Mulai layanan Mahasiswa: `cd Student/laravel && php artisan serve --port=8001`
2. Mulai layanan Nilai: `cd Grade/laravel && php artisan serve --port=8003`
3. Mulai layanan Mata Kuliah: `cd UTS/laravel && php artisan serve --port=8002`

## Endpoint API

### Layanan Mahasiswa (port 8001)
- `GET /api/students`: Mendapatkan semua mahasiswa
- `GET /api/students/{id}`: Mendapatkan mahasiswa tertentu
- `POST /api/students`: Membuat mahasiswa baru
- `PUT /api/students/{id}`: Memperbarui data mahasiswa
- `GET /api/students/{id}/transcript`: Mendapatkan transkrip mahasiswa (menggunakan layanan Nilai)

### Layanan Nilai (port 8003)
- `GET /api/grades/{id}`: Mendapatkan nilai tertentu
- `GET /api/grades/student/{studentId}`: Mendapatkan semua nilai untuk seorang mahasiswa
- `POST /api/grades`: Membuat nilai baru (menggunakan layanan Mahasiswa dan Mata Kuliah untuk validasi)

### Layanan Mata Kuliah (port 8002)
- `GET /api/courses`: Mendapatkan semua mata kuliah
- `GET /api/courses/{id}`: Mendapatkan mata kuliah tertentu
- `POST /api/courses`: Membuat mata kuliah baru
- `PUT /api/courses/{id}`: Memperbarui mata kuliah
- `POST /api/enrollments`: Mendaftarkan mahasiswa ke mata kuliah
- `GET /api/courses/{id}/enrollments`: Mendapatkan semua pendaftaran untuk mata kuliah tertentu

## Format Permintaan/Respons

### Endpoint Mahasiswa
- Membuat/Memperbarui Mahasiswa:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "faculty": "Engineering",
    "major": "Computer Science"
  }
  ```

### Endpoint Nilai
- Membuat Nilai:
  ```json
  {
    "student_id": 1,
    "course_id": 1,
    "score": 85.5
  }
  ```

### Endpoint Mata Kuliah
- Membuat/Memperbarui Mata Kuliah:
  ```json
  {
    "name": "Introduction to Programming",
    "code": "CS101",
    "credits": 3
  }
  ```

- Pendaftaran:
  ```json
  {
    "student_id": 1,
    "course_id": 1
  }
  ```

## Kode Kesalahan
- `404`: Sumber daya tidak ditemukan
- `422`: Kesalahan validasi
- `500`: Kesalahan server atau kesalahan komunikasi layanan