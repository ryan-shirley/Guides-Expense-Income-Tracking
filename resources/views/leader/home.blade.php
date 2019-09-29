@extends('layouts.main')

@section('content')
<!-- Header -->
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Expenses stats -->
            <div class="row">
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">To be paid back</h5>
                                    <span class="h2 font-weight-bold mb-0">€{{ $total_to_be_paid }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-piggy-bank"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.To be paid back -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Spent last 30 day</h5>
                                    <span class="h2 font-weight-bold mb-0">€{{ $total_30_days }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.Spent last 30 days -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">All time spent</h5>
                                    <span class="h2 font-weight-bold mb-0">€{{ $total_all_time }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.All Time -->
            </div>
            <!-- /.Expenses Stats -->
        </div>
        <!-- /.Header Body -->
    </div>
    <!-- /.Container Fluid -->
</div>
<!-- /.Header -->

<div class="container-fluid mt--7">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card shadow">
                <div class="card-header bg-transparent mb-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Hello</h6>
                            <h2 class="mb-0">All Payments</h2>
                        </div>
                        <div class="col">
                            <p class="text-right"><a class="btn btn-primary btn-sm" href="{{ route('leader.payment.create') }}" role="button">Add Payment</a></p>
                        </div>
                    </div>
                </div>
                <!-- Payments List -->
                <table class="table table-hover" id="payment_table">
                    <thead class="thead-light">
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
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('leader.payment.show', $p->id) }}" role="button">View</a>
                                    @if ($p->in_accounts !== 1)
                                        <a class="btn btn-warning btn-sm" href="{{ route('leader.payment.edit', $p->id) }}" role="button"><i class="far fa-edit"></i></a>
                                        <form action="{{ action('Leader\PaymentController@destroy', $p->id )}}" method="post" onSubmit="return confirm('Are you sure you wish to delete?')" style="display: inline;">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            
                                            <button class="btn btn-danger btn-sm" ><i class="fas fa-times"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.Payments List -->
            </div>
            <!-- /.Card payments table -->
        </div>
        <!-- /.Column -->
    </div>
    <!-- /.Row -->
</div>
<!-- /.Container Fluid -->
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
@endsection