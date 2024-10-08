@extends('layouts.main')

@section('page-title', 'Add Event')

@php
    // Extract the year from the URL
    $year = request()->segment(2); // Adjust the segment index based on your URL structure
    // Get the current date with the extracted year
    $defaultDate = date('Y-m-d', strtotime($year . '-01-01'));
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Add Event</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.events.store', $year )}}">
                    @csrf

                    <div class="form-group">
                        <label for="amount">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ old( 'title') }}">
                        <small class="form-text text-muted">The title for this event.</small>
                        <div class="text-danger">{{ $errors->first('title') }}</div>
                    </div>
                    <!-- /.Title -->

                    <div class="form-group">
                        <label for="date">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="{{ old( 'start_date', $defaultDate) }}">
                        <small class="form-text text-muted">The start date for this event.</small>
                        <div class="text-danger">{{ $errors->first('start_date') }}</div>
                    </div>
                    <!-- /.Start Date -->

                    <div class="form-group">
                        <label for="date">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{ old( 'end_date', $defaultDate) }}">
                        <small class="form-text text-muted">The end date for this event.</small>
                        <div class="text-danger">{{ $errors->first('end_date') }}</div>
                    </div>
                    <!-- /.End Date -->

                    <button class="btn btn-primary" type="submit" value="Store">Add</button>
                </form>
                <!-- /.Form -->
            </div>
            <!-- /.Card Body -->
        </div>
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
</div>
@endsection