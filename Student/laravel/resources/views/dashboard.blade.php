@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Dashboard</h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Students</h5>
                        <h2 class="display-4">{{ $studentCount }}</h2>
                        <a href="{{ route('students') }}" class="btn btn-light mt-3">View Students</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Courses</h5>
                        <h2 class="display-4">{{ $courseCount }}</h2>
                        <a href="{{ route('courses') }}" class="btn btn-light mt-3">View Courses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Grades</h5>
                        <h2 class="display-4">{{ $gradeCount }}</h2>
                        <a href="{{ route('grades') }}" class="btn btn-light mt-3">View Grades</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3>API Microservices</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>URL</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Student Service</td>
                        <td>http://127.0.0.1:8001</td>
                        <td><span class="badge bg-success">Active</span></td>
                    </tr>
                    <tr>
                        <td>Course Service</td>
                        <td>http://127.0.0.1:8002</td>
                        <td>
                            @if($courseCount !== 'N/A')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Grade Service</td>
                        <td>http://127.0.0.1:8003</td>
                        <td>
                            @if($gradeCount !== 'N/A')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection