# Setup Guide for Microservices System

## Initial Setup

1. Clone the repository
2. Configure the .env files in each service with database settings

## Database Setup

For each service, run:

```bash
php artisan migrate:fresh --seed
```

This will create the database tables and seed them with sample data.

## Running the Services

Open three separate terminals and run:

### Student Service (Terminal 1)
```bash
cd c:\Users\Acer\Desktop\Sem6\PPL\utsiae\Student\laravel
php artisan serve --port=8001
```

### Course Service (Terminal 2)
```bash
cd c:\Users\Acer\Desktop\Sem6\PPL\utsiae\UTS\laravel
php artisan serve --port=8002
```

### Grade Service (Terminal 3)
```bash
cd c:\Users\Acer\Desktop\Sem6\PPL\utsiae\Grade\laravel
php artisan serve --port=8003
```

## Accessing the UI

Open a browser and navigate to:
http://127.0.0.1:8001

This will open the main UI dashboard where you can view students, courses, grades, and transcripts.

## Testing API Endpoints

You can test the API endpoints using tools like Postman or curl:

### Student API Endpoints:
- GET http://127.0.0.1:8001/api/students
- GET http://127.0.0.1:8001/api/students/1
- POST http://127.0.0.1:8001/api/students

### Course API Endpoints:
- GET http://127.0.0.1:8002/api/courses
- GET http://127.0.0.1:8002/api/courses/1
- POST http://127.0.0.1:8002/api/courses

### Grade API Endpoints:
- GET http://127.0.0.1:8003/api/grades
- GET http://127.0.0.1:8003/api/grades/student/1
- POST http://127.0.0.1:8003/api/grades