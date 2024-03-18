@extends('layouts.main')

@section('page-title', 'Admin Dashboard')

@section('header')
<!-- Expenses stats -->
<div class="row justify-content-center">
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total spent - {{ date('Y') }}</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $total_year }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Total spent current year -->
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Income - {{ date('Y') }}</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $incomeForYear }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Income current year -->
    <div class="col-xl-3 col-lg-6 ">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Left to pay back</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $total_to_pay_back }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Left to pay back -->
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Waiting for approval</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $num_waiting_approval }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-pause"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Waiting on approval -->
</div>
<!-- /.Expenses Stats -->
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @foreach ($years as $year)
            <a href="{{ route('admin.payments.index', $year) }}" role="button">
                <div class="card card-stats mb-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p class="card-title text-uppercase text-muted mb-0 font-weight-bold h1">{{ $year }}</p>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <!-- /.Years -->
    <div class="col-md-6">
        <div class="card bg-gradient-default shadow mb-3">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-light ls-1 mb-1">Pending Accounts</h6>
                        <h2 class="text-white mb-0">Approve known leaders</h2>
                    </div>
                </div>
            </div>
            <!-- /.Card Header -->

            @if(count($users_pending_approval) === 0) 
                <div class="card-body">
                    <p>Well done you have no pending accounts!</p>
                </div>
            @else
                <!-- Account List -->
                <div class="table-responsive">
                    <table class="table table-dark table-hover" id="payment_table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users_pending_approval as $user)
                                <tr>
                                    <th scope="row">{{ $user->name }}</th>
                                    <td>
                                        <form action="{{ action('Admin\UsersController@approve', $user->id )}}" class="account-approve" method="post" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-success btn-sm" >Approve</button>
                                        </form>
                                        <form action="{{ action('Admin\UsersController@destroy', $user->id )}}" class="account-delete" method="post" style="display: inline;">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger btn-sm" ><i class="fas fa-times"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.Account List -->
            @endif
        </div>
    </div>
    <!-- /.Pending Accounts -->
</div>
<!-- /.Row -->
@endsection
