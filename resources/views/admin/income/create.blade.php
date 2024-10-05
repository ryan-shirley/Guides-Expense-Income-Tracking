@extends('layouts.main')

@section('page-title', 'Add Income')

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
                        <h2 class="mb-0">Add Incoming Money</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.incomes.store', $year )}}">
                    @csrf

                    <div class="form-group">
                        <label for="user_id">Event</label>
                        <select class="form-control" name="event_id" id="eventsList">
                            <option value="0"></option>
                            @foreach ($events as $e)
                                <option value="{{ $e->id }}" {{ (old('event_id') == $e->id) ? "selected" : "" }}>{{ $e->title }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Event where this income took place</small>
                        <div class="text-danger">{{ $errors->first('event_id') }}</div>
                    </div>
                    <!-- /.Event -->

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter title" value="{{ old( 'title') }}">
                        <small class="form-text text-muted">Describe where the income came from.</small>
                        <div class="text-danger">{{ $errors->first('title') }}</div>
                    </div>
                    <!-- /.Title -->

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" step="0.01" placeholder="Enter amount" value="{{ old( 'amount') }}">
                        <small class="form-text text-muted">The amount for this income</small>
                        <div class="text-danger">{{ $errors->first('amount') }}</div>
                    </div>
                    <!-- /.Amount -->

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="date" value="{{ old( 'date', $defaultDate) }}">
                        <small class="form-text text-muted">The date for this income</small>
                        <div class="text-danger">{{ $errors->first('date') }}</div>
                    </div>
                    <!-- /.Date -->

                    <div class="form-group">
                        <label for="is_cash">Payment Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_cash" id="is_cash1" value="1" {{ (old('is_cash') == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_cash1">
                                Cash & Cheques 
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_cash" id="is_cash2" value="0" {{ (old('is_cash') == '0') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_cash2">
                                Online Payments
                            </label>
                        </div>
                        <small class="form-text text-muted">Please pick the type of income.</small>
                        <div class="text-danger">{{ $errors->first('is_cash') }}</div>
                    </div>
                    <!-- /.Is Cash -->

                    <div class="form-group">
                        <label for="user_id">Analysis Code</label>
                        <select class="form-control" name="code" id="codes">
                                <option value="1" {{ (old('code') == 1) ? "selected" : "" }}>Subs</option>
                                <option value="2" {{ (old('code') == 2) ? "selected" : "" }}>IGG Membership Fees</option>
                                <option value="3" {{ (old('code') == 3) ? "selected" : "" }}>Camps / Outings / Events</option>
                                <option value="4" {{ (old('code') == 4) ? "selected" : "" }}>Grants Generally</option>
                                <option value="5" {{ (old('code') == 5) ? "selected" : "" }}>Refund of Training Fees</option>
                                <option value="6" {{ (old('code') == 6) ? "selected" : "" }}>Interest</option>
                                <option value="7" {{ (old('code') == 7) ? "selected" : "" }}>Thinking Day</option>
                                <option value="8" {{ (old('code') == 8) ? "selected" : "" }}>Fundraising</option>
                                <option value="9" {{ (old('code') == 9) ? "selected" : "" }}>Miscellaneous</option>
                                <option value="10" {{ (old('code') == 10) ? "selected" : "" }}>Uniforms</option>
                        </select>
                        <small class="form-text text-muted">Code for accounts.</small>
                        <div class="text-danger">{{ $errors->first('code') }}</div>
                    </div>
                    <!-- /.Code -->

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