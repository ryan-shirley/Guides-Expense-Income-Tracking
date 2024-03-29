@extends('layouts.main')

@section('page-title', 'Edit Transaction')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Edit Transaction</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.bank-transactions.update', ['year' => $year, 'bank_transaction' => $transaction->id] )}}">
                    @method('PATCH')
                    @csrf

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" step="0.01" placeholder="Enter amount" value="{{ old( 'amount', $transaction->amount) }}">
                        <small class="form-text text-muted">The amount for this transaction.</small>
                        <div class="text-danger">{{ $errors->first('amount') }}</div>
                    </div>
                    <!-- /.Amount -->

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="date" value="{{ old( 'date', $transaction->date->format('Y-m-d')) }}">
                        <small class="form-text text-muted">The date for this transaction.</small>
                        <div class="text-danger">{{ $errors->first('date') }}</div>
                    </div>
                    <!-- /.Date -->

                    <div class="form-group">
                        <label for="is_logement">Transaction Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_logement" id="is_logement1" value="1" {{ (old('is_logement', $transaction->is_logement) == '1') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_logement1">
                                Logement
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_logement" id="is_logement2" value="0" {{ (old('is_logement', $transaction->is_logement) == '0') ? "checked" : "" }}>
                            <label class="form-check-label" for="is_logement2">
                                Withdrawal
                            </label>
                        </div>
                        <small class="form-text text-muted">Please pick the type of transaction.</small>
                        <div class="text-danger">{{ $errors->first('is_logement') }}</div>
                    </div>
                    <!-- /.Type -->

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