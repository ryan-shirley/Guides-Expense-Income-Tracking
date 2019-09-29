@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mt-5 mb-3 text-center">Hello, {{ $user->name }}</h1>

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">To be paid back</div>
                            <div class="h5 mb-0 font-weight-bold">€{{ $total_to_be_paid }}</div>
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
            
            <!-- <div class="card">
                <div class="card-header">Leader Home</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div> -->

            <a class="btn btn-primary btn-sm mb-3" href="{{ route('leader.payment.create') }}" role="button">Add Payment</a>

            <!-- Payments List -->
            <table class="table table-striped table-hover table-bordered" id="payment_table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Purchase Date</th>
                        <th scope="col">Guide Money</th>
                        <th scope="col">Paid Back</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $p)
                        <tr>
                            <td scope="row">{{ $p->title }}</td>
                            <td>€{{ $p->amount }}</td>
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
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-primary btn-sm" href="{{ route('leader.payment.show', $p->id) }}" role="button">View</a>
                                    @if ($p->in_accounts !== 1)
                                        <a class="btn btn-warning btn-sm" href="{{ route('leader.payment.edit', $p->id) }}" role="button">Edit</a>
                                        <form action="{{ action('Leader\PaymentController@destroy', $p->id )}}" method="post" onSubmit="return confirm('Are you sure you wish to delete?')">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger btn-sm" >Delete</button>
                                        </form>
                                    @endif
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
