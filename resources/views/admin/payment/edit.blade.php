@extends('layouts.main')

@section('page-title', 'Edit Payment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Edit Payment</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.payments.update', $payment->id )}}" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Leader</label>
                        <select class="form-control" name="user_id" id="customerList">
                            @foreach ($leaders as $l)
                                <option value="{{ $l->id }}" {{ (old('user_id', $payment->user_id) == $l->id) ? "selected" : "" }}>{{ $l->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Leader who made expense</small>
                        <div class="text-danger">{{ $errors->first('user_id') }}</div>
                    </div>
                    <!-- /.Leader -->

                    <div class="form-group">
                        <label for="title">Store name</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter the name of the store" value="{{ old( 'title', $payment->title) }}">
                        <small class="form-text text-muted">Full name of the store</small>
                        <div class="text-danger">{{ $errors->first('title') }}</div>
                    </div>
                    <!-- /.Title | Store Name -->

                    <div class="form-group">
                        <label for="title">Description</label>
                        <input type="text" class="form-control" name="description" placeholder="Description what was purchased" value="{{ old( 'description', $payment->description) }}">
                        <small class="form-text text-muted">Description of what was purchased</small>
                        <div class="text-danger">{{ $errors->first('description') }}</div>
                    </div>
                    <!-- /.Description -->

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" step="1" placeholder="Enter amount" value="{{ old( 'amount', $payment->amount) }}">
                        <small class="form-text text-muted">Expense amount (Round nearest euro)</small>
                        <div class="text-danger">{{ $errors->first('amount') }}</div>
                    </div>
                    <!-- /.Amount -->

                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" name="purchase_date" value="{{ old( 'purchase_date', $payment->purchase_date->format('Y-m-d')) }}">
                        <small class="form-text text-muted">The date for this expense</small>
                        <div class="text-danger">{{ $errors->first('purchase_date') }}</div>
                    </div>
                    <!-- /.Purchase Date -->

                    <div class="form-group">
                        <label for="guide_money">Type of money</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="guide_money" id="guide_money1" value="1" {{ (old('guide_money', $payment->guide_money) == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="guide_money1">
                            Guides
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="guide_money" id="guide_money2" value="0" {{ (old('guide_money', $payment->guide_money) == '0') ? "checked" : "" }}>
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
                            <input class="form-check-input" type="radio" name="paid_back" id="paid_back1" value="1" {{ (old('paid_back', $payment->paid_back) == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="paid_back1">
                            Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paid_back" id="paid_back2" value="0" {{ (old('paid_back', $payment->paid_back) == '0') ? "checked" : "" }}>
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
                            <input class="form-check-input" type="radio" name="is_cash" id="is_cash1" value="1" {{ (old('is_cash', $payment->is_cash) == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_cash1">
                                Cash
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_cash" id="is_cash2" value="0" {{ (old('is_cash', $payment->is_cash) == '0') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_cash2">
                            All other (Cheques, cards, online payments)
                            </label>
                        </div>
                        <small class="form-text text-muted">Please pick the type of payment that was used.</small>
                        <div class="text-danger">{{ $errors->first('is_cash') }}</div>
                    </div>
                    <!-- /.Is Cash -->

                    <div class="form-group">
                        <label for="user_id">Analysis Code</label>
                        <select class="form-control" name="code" id="codes">
                                <option value="1" {{ (old('code', $payment->code) == 1) ? "selected" : "" }}>Programme Material</option>
                                <option value="2" {{ (old('code', $payment->code) == 2) ? "selected" : "" }}>Uniform & Badges</option>
                                <option value="3" {{ (old('code', $payment->code) == 3) ? "selected" : "" }}>Camps / Outings / Events</option>
                                <option value="4" {{ (old('code', $payment->code) == 4) ? "selected" : "" }}>IGG Membership Fees</option>
                                <option value="5" {{ (old('code', $payment->code) == 5) ? "selected" : "" }}>Equipment</option>
                                <option value="6" {{ (old('code', $payment->code) == 6) ? "selected" : "" }}>Unit Administration</option>
                                <option value="7" {{ (old('code', $payment->code) == 7) ? "selected" : "" }}>Rent</option>
                                <option value="8" {{ (old('code', $payment->code) == 8) ? "selected" : "" }}>Adult Training</option>
                                <option value="9" {{ (old('code', $payment->code) == 9) ? "selected" : "" }}>Thinking Day</option>
                                <option value="10" {{ (old('code', $payment->code) == 10) ? "selected" : "" }}>Banking Fees</option>
                                <option value="11" {{ (old('code', $payment->code) == 11) ? "selected" : "" }}>Miscellaneous</option>
                                <option value="12" {{ (old('code', $payment->code) == 12) ? "selected" : "" }}>Fundraising</option>
                                <option value="cc" {{ (old('code', $payment->code) == 'cc') ? "selected" : "" }}>If drawing cash from bank or float (petty cash)</option>
                        </select>
                        <small class="form-text text-muted">Code for accounts.</small>
                        <div class="text-danger">{{ $errors->first('code') }}</div>
                    </div>
                    <!-- /.Code -->

                    <div class="form-group">
                        <label for="user_id">Event</label>
                        <select class="form-control" name="event_id" id="eventsList">
                            <option value="0"></option>
                            @foreach ($events as $e)
                                <option value="{{ $e->id }}" {{ (old('event_id', $payment->event_id) == $e->id) ? "selected" : "" }}>{{ $e->title }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Event where this payment took place</small>
                        <div class="text-danger">{{ $errors->first('event_id') }}</div>
                    </div>
                    <!-- /.Event -->

                    <div class="form-group">
                        <label for="title">Receipt Image</label>
                        <img src="{{ $payment->receipt_url  }}" onerror="this.src='https://placehold.co/600x200?text=No+Receipt'" class="img-thumbnail mb-2" />
                        <input type="file" class="form-control" name="receipt_image" placeholder="Receipt image" value="{{ old( 'receipt_image') }}">
                        <small class="form-text text-muted">Close up of the full receipt</small>
                        <div class="text-danger">{{ $errors->first('receipt_image') }}</div>
                    </div>
                    <!-- /.Receipt Image -->

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
