@extends('layouts.main')

@section('page-title', 'Add Payment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Add Payment</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.payments.store' )}}">
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Leader</label>
                        <select class="form-control" name="user_id" id="customerList">
                            @foreach ($leaders as $l)
                                <option value="{{ $l->id }}" {{ (old('user_id') == $l->id) ? "selected" : "" }}>{{ $l->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Leader who made expense</small>
                        <div class="text-danger">{{ $errors->first('user_id') }}</div>
                    </div>
                    <!-- /.Leader -->

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter title" value="{{ old( 'title') }}">
                        <small class="form-text text-muted">Describe what this expense was for.</small>
                        <div class="text-danger">{{ $errors->first('title') }}</div>
                    </div>
                    <!-- /.Title -->

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" step="0.01" placeholder="Enter amount" value="{{ old( 'amount') }}">
                        <small class="form-text text-muted">The amount for this expense</small>
                        <div class="text-danger">{{ $errors->first('amount') }}</div>
                    </div>
                    <!-- /.Amount -->

                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" name="purchase_date" value="{{ old( 'purchase_date') }}">
                        <small class="form-text text-muted">The date for this expense</small>
                        <div class="text-danger">{{ $errors->first('purchase_date') }}</div>
                    </div>
                    <!-- /.Amount -->

                    <div class="form-group">
                        <label for="guide_money">Type of money</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="guide_money" id="guide_money1" value="1" {{ (old('guide_money') == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="guide_money1">
                            Guides
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="guide_money" id="guide_money2" value="0" {{ (old('guide_money') == '0') ? "checked" : "" }}>
                            <label class="form-check-label" for="guide_money2">
                            Personal
                            </label>
                        </div>
                        <small class="form-text text-muted">Please pick where the money came from that was used for this expense.</small>
                        <div class="text-danger">{{ $errors->first('guide_money') }}</div>
                    </div>
                    <!-- /.Guide Money -->

                    <div class="form-group">
                        <label for="paid_back">Paid Back</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paid_back" id="paid_back1" value="1" {{ (old('paid_back') == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="paid_back1">
                            Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paid_back" id="paid_back2" value="0" {{ (old('paid_back') == '0') ? "checked" : "" }}>
                            <label class="form-check-label" for="paid_back2">
                            No
                            </label>
                        </div>
                        <small class="form-text text-muted">Please pick if this expense has been paid back or not.</small>
                        <div class="text-danger">{{ $errors->first('paid_back') }}</div>
                    </div>
                    <!-- /.Paid Back -->

                    <button class="btn btn-primary" type="submit" value="Store">Submit</button>
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