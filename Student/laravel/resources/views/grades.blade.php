@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Grades</h2>
    </div>
    <div class="card-body">
        @if(empty($grades))
            <div class="alert alert-warning">
                Unable to connect to Grade Service. Please ensure it is running.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Score</th>
                            <th>Letter Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                        <tr>
                            <td>{{ $grade['id'] }}</td>
                            <td>{{ $grade['student_name'] }}</td>
                            <td>{{ $grade['course_name'] }}</td>
                            <td>{{ $grade['score'] }}</td>
                            <td>
                                @php
                                    $score = $grade['score'];
                                    if ($score >= 90) {
                                        echo 'A';
                                    } elseif ($score >= 80) {
                                        echo 'B';
                                    } elseif ($score >= 70) {
                                        echo 'C';
                                    } elseif ($score >= 60) {
                                        echo 'D';
                                    } else {
                                        echo 'F';
                                    }
                                @endphp
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection