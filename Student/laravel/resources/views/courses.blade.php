@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Courses</h2>
    </div>
    <div class="card-body">
        @if(empty($courses))
            <div class="alert alert-warning">
                Unable to connect to Course Service. Please ensure it is running.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Credits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $course['id'] }}</td>
                            <td>{{ $course['code'] }}</td>
                            <td>{{ $course['name'] }}</td>
                            <td>{{ $course['credits'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection