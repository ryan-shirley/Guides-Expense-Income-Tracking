@extends('layouts.main')

@section('page-title', 'Admin Dashboard')

@section('header')
<div class="row justify-content-center">
    <div class="col-md-6">
        @foreach ($years as $year)
            <a href="{{ route('admin.year.home', $year) }}" role="button">
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
                        <h6 class="text-uppercase text-light ls-1 mb-1">Leaders</h6>
                        <h2 class="text-white mb-0">Who needs paying back</h2>
                    </div>
                </div>
            </div>
            <!-- /.Card Header -->

            @if(count($leadersToPayBack) === 0) 
                <div class="card-body">
                    <p>Well done you have paid back everyone back!</p>
                </div>
            @else
                <!-- Leaders List -->
                <div class="table-responsive">
                    <table class="table table-dark table-hover" id="payment_table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Leader</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leadersToPayBack as $id => $amount)
                                <tr>
                                    <th scope="row">{{ \App\User::find($id)->name }}</th>
                                    <td>€{{ $amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.Leaders List -->
            @endif
        </div>
        <!-- /.To pay back -->
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
        <!-- /.Pending Accounts -->
    </div>
</div>
<!-- /.Row -->
@endsection

@section('content')
    <div style="height:200px"></div>
@endsection
