@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Academic Transcript</h2>
        <a href="{{ route('students') }}" class="btn btn-primary">Back to Students</a>
    </div>
    <div class="card-body">
        <h3 class="mb-4">{{ $student->name }}'s Transcript</h3>

        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Student ID</th>
                        <td>{{ $student->id }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $student->email }}</td>
                    </tr>
                    <tr>
                        <th>Faculty</th>
                        <td>{{ $student->faculty }}</td>
                    </tr>
                    <tr>
                        <th>Major</th>
                        <td>{{ $student->major }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if(empty($transcript))
            <div class="alert alert-info">
                No grades available for this student.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Score</th>
                            <th>Letter Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPoints = 0;
                            $totalCredits = 0;
                        @endphp
                        @foreach($transcript as $grade)
                        <tr>
                            <td>{{ $grade['course_code'] ?? 'N/A' }}</td>
                            <td>{{ $grade['course_name'] }}</td>
                            <td>{{ $grade['score'] }}</td>
                            <td>
                                @php
                                    $score = $grade['score'];
                                    if ($score >= 90) {
                                        echo 'A';
                                        $gradePoints = 4.0;
                                    } elseif ($score >= 80) {
                                        echo 'B';
                                        $gradePoints = 3.0;
                                    } elseif ($score >= 70) {
                                        echo 'C';
                                        $gradePoints = 2.0;
                                    } elseif ($score >= 60) {
                                        echo 'D';
                                        $gradePoints = 1.0;
                                    } else {
                                        echo 'F';
                                        $gradePoints = 0.0;
                                    }
                                    
                                    $credits = $grade['credits'] ?? 3; // Default to 3 if not provided
                                    $totalPoints += $gradePoints * $credits;
                                    $totalCredits += $credits;
                                @endphp
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <th colspan="3" class="text-end">GPA:</th>
                            <th>
                                @if($totalCredits > 0)
                                    {{ number_format($totalPoints / $totalCredits, 2) }}
                                @else
                                    N/A
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection