@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <h1 class="mt-5 mb-3 text-center">All Payments</h1>

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">To pay back</div>
                            <div class="h5 mb-0 font-weight-bold">€{{ $total_to_pay_back }}</div>
                        </div>
                    </div>
                </div>
                <!-- /.Amount to be paid back -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Spent last 30 day</div>
                            <div class="h5 mb-0 font-weight-bold">€{{ $total_30_days }}</div>
                        </div>
                    </div>
                </div>
                <!-- /.Total last 30 days -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">All time spent</div>
                            <div class="h5 mb-0 font-weight-bold">€{{ $total_all_time }}</div>
                        </div>
                    </div>
                </div>
                <!-- /.All time spent -->
            </div>
            <!-- /.Stats -->

            <a class="btn btn-primary btn-sm mb-3" href="{{ route('admin.payment.create') }}" role="button">Add Payment</a>

            <!-- Payments List -->
            <table class="table table-striped table-hover table-bordered" id="payment_table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Leader</th>
                        <th scope="col">Title</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Purchase Date</th>
                        <th scope="col">Guide Money</th>
                        <th scope="col">Paid Back</th>
                        <th scope="col">In Accounts</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $p)
                        <tr>
                            <th scope="row"><a href="{{ route('admin.user.show', $p->user) }}">{{ $p->user->name }}</a></th>
                            <td>{{ $p->title }}</td>
                            <td>{{ $p->amount }}</td>
                            <td>{{ date('d M Y', strtotime($p->purchase_date)) }}</td>
                            <td>
                                @if ($p->guide_money === 1)
                                    Guide
                                @else
                                    Personal
                                @endif
                            </td>
                            <td>
                                @if ($p->paid_back === 1)
                                    ✅
                                @else
                                    ❌
                                @endif
                            </td>
                            <td>
                                @if ($p->in_accounts === 1)
                                    ✅
                                @else
                                    ❌
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-primary btn-sm" href="{{ route('admin.payment.show', $p->id) }}" role="button">View</a>
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.payment.edit', $p->id) }}" role="button">Edit</a>
                                    <form action="{{ action('Admin\PaymentController@changePaymentStatus', $p->id )}}" method="post">
                                        @csrf
                                        <button class="btn btn-secondary btn-sm" > Mark
                                        @if ($p->paid_back === 1)
                                            Not Paid
                                        @else
                                            Paid
                                        @endif
                                        </button>
                                    </form>
                                    @if ($p->in_accounts === 0)
                                        <form action="{{ action('Admin\PaymentController@changeAccountStatus', $p->id )}}" method="post">
                                            @csrf
                                            <button class="btn btn-success btn-sm" >Send to accounts</button>
                                        </form>
                                    @endif
                                    <form action="{{ action('Admin\PaymentController@destroy', $p->id )}}" method="post" onSubmit="return confirm('Are you sure you wish to delete?')">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger btn-sm" >Delete</button>
                                    </form>
                                </div>
                                <!-- /.Action Buttons -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- /.Payments List -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" >
        // my custom script
        $(document).ready( function () {
            console.log('test')
        $('#payment_table').DataTable();
    } );
    </script>
@stop
