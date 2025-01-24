@extends('layouts.main')

@section('page-title', 'Create Payment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Create Payment</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leader.payments.store' )}}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="title">Store name</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter the name of the store" value="{{ old( 'title') }}">
                        <small class="form-text text-muted">Full name of the store</small>
                        <div class="text-danger">{{ $errors->first('title') }}</div>
                    </div>
                    <!-- /.Title | Store Name -->

                    <div class="form-group">
                        <label for="title">Description</label>
                        <input type="text" class="form-control" name="description" placeholder="Description what was purchased" value="{{ old( 'description') }}">
                        <small class="form-text text-muted">Description of what was purchased</small>
                        <div class="text-danger">{{ $errors->first('description') }}</div>
                    </div>
                    <!-- /.Description -->

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" step="1" placeholder="Enter amount" value="{{ old( 'amount') }}">
                        <small class="form-text text-muted">Expense amount (Round nearest euro)</small>
                        <div class="text-danger">{{ $errors->first('amount') }}</div>
                    </div>
                    <!-- /.Amount -->

                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" name="purchase_date" value="{{ old( 'purchase_date') }}">
                        <small class="form-text text-muted">The date for this expense</small>
                        <div class="text-danger">{{ $errors->first('purchase_date') }}</div>
                    </div>
                    <!-- /.Purchase Date -->

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

                    <div class="form-group">
                        <label for="is_cash">Payment Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_cash" id="is_cash1" value="1" {{ (old('is_cash') == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_cash1">
                                Cash
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_cash" id="is_cash2" value="0" {{ (old('is_cash') == '0') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_cash2">
                                All other (Cheques, cards, online payments)
                            </label>
                        </div>
                        <small class="form-text text-muted">Please pick the type of payment that was used.</small>
                        <div class="text-danger">{{ $errors->first('is_cash') }}</div>
                    </div>
                    <!-- /.Is Cash -->

                    <div class="form-group">
                        <label for="title">Receipt Image</label>
                        <input type="file" class="form-control" name="receipt_image" placeholder="Receipt image" value="{{ old( 'receipt_image') }}">
                        <small class="form-text text-muted">Close up of the full receipt</small>
                        <div class="text-danger">{{ $errors->first('receipt_image') }}</div>
                    </div>
                    <!-- /.Receipt Image -->

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
