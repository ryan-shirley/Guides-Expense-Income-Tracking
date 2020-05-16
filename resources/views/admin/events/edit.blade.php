@extends('layouts.main')

@section('page-title', 'Edit Event')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Edit Event</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.events.update', $event->id )}}">
                    @method('PATCH')
                    @csrf


                    <div class="form-group">
                        <label for="amount">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ old( 'title', $event->title) }}">
                        <small class="form-text text-muted">The title for this event.</small>
                        <div class="text-danger">{{ $errors->first('title') }}</div>
                    </div>
                    <!-- /.Title -->

                    <div class="form-group">
                        <label for="date">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="{{ old( 'start_date', $event->start_date) }}">
                        <small class="form-text text-muted">The start date for this event.</small>
                        <div class="text-danger">{{ $errors->first('start_date') }}</div>
                    </div>
                    <!-- /.Start Date -->

                    <div class="form-group">
                        <label for="date">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{ old( 'end_date', $event->end_date) }}">
                        <small class="form-text text-muted">The end date for this event.</small>
                        <div class="text-danger">{{ $errors->first('end_date') }}</div>
                    </div>
                    <!-- /.End Date -->

                    <button class="btn btn-primary" type="submit" value="Store">Update</button>
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