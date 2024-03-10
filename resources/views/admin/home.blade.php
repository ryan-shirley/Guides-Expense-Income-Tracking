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
    <div class="col-md-12">
        <div class="card bg-gradient-default shadow mb-3">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-light ls-1 mb-1">Blank</h6>
                        <h2 class="text-white mb-0">Blank</h2>
                    </div>
                </div>
            </div>
            <!-- /.Card Header -->
            <div class="card-body">
            </div>
            <!-- /.Card Body -->
        </div>
    </div>
    <!-- /.Column Bank History -->
</div>
<!-- /.Row -->
@endsection
