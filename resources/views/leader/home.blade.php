@extends('layouts.main')

@section('page-title', 'Dashboard')

@section('header')
<!-- Expenses stats -->
<div class="row">
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total to be paid back</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $total_to_be_paid }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                            <i class="fas fa-piggy-bank"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.To be paid back -->
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
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">I Spent in the last 30 days</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $total_30_days }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Spent last 30 days -->
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total I spent Year - {{ date('Y') }}</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $total_year }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-default text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Total current year -->
</div>
<!-- /.Expenses Stats -->
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                        <h2 class="mb-0">All Payments</h2>
                    </div>
                    <div class="col">
                        <p class="text-right"><a class="btn btn-primary" href="{{ route('leader.payments.create') }}" role="button">Add Payment</a></p>
                    </div>
                </div>
            </div>
            <!-- Payments List -->
            <div class="table-responsive">
                <table class="table table-hover" id="payment_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Ref</th>
                            <th scope="col">Title</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Purchase Date</th>
                            <th scope="col">Guide Money</th>
                            <th scope="col">Paid Back</th>
                            <th scope="col">Received Receipt</th>
                            <th scope="col">Approved</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $p)
                            <tr>
                                <td scope="row">{{ $p->keyID }}</td>
                                <td>{{ $p->title }}</td>
                                <td>€{{ $p->amount }}</td>
                                <td>{{ date('Y-m-d', strtotime($p->purchase_date)) }}</td>
                                <td>
                                    @if ($p->guide_money)
                                        Guide
                                    @else
                                        Personal
                                    @endif
                                </td>
                                <td>
                                    @if ($p->paid_back)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->receipt_received)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->approved)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (!$p->approved)
                                        <a class="btn btn-warning btn-sm" href="{{ route('leader.payments.edit', $p->id) }}" role="button"><i class="far fa-edit"></i></a>
                                        <form action="{{ action('Leader\PaymentController@destroy', $p->id )}}" class="payment-delete" method="post" style="display: inline;">
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
            </div>
            <!-- /.Payments List -->
        </div>
        <!-- /.Card payments table -->
    </div>
    <!-- /.Column -->
</div>
<!-- /.Row -->
@endsection

@section('scripts')
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" >
        // my custom script
        $(document).ready( function () {
            console.log('test')
            $('#payment_table').DataTable({
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', 
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                "order": [[ 3, "desc" ]]
            });
        });

        // Delete Payment Confirmation
        $('.payment-delete').submit(function(event){
            event.preventDefault()

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete payment!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-default',
                },
            }).then((result) => {
                if (result.value) {
                    $(this).unbind('submit').submit();
                }
                else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: "Your payment was not deleted",
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Close',
                        customClass: {
                            confirmButton: 'btn btn-primary',
                        },
                    })
                }
            })
        });
    </script>
@endsection