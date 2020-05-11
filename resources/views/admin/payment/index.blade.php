@extends('layouts.main')

@section('page-title', 'Payments')

@section('header')

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
                        <p class="text-right float-right"><a class="btn btn-primary" href="{{ route('admin.payments.create') }}" role="button">Add Payment</a></p>
                        <p class="text-right float-right mr-3"><a class="btn btn-light" href="{{ route('admin.payments.export') }}" role="button">Export</a></p>
                    </div>
                </div>
            </div>
            <!-- Payments List -->
            <div class="table-responsive">
                <table class="table table-hover" id="payment_table">
                    <thead class="thead-light">
                        <tr>
                        <th scope="col">Leader</th>
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
                                <th scope="row">{{ $p->user->name }}</th>
                                <td>{{ $p->title }}</td>
                                <td>€{{ $p->amount }}</td>
                                <td>{{ date('Y-m-d', strtotime($p->purchase_date)) }}</td>
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
                                    @if ($p->receipt_received === 1)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->approved === 1)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->approved !== 1)
                                        <a class="btn btn-warning btn-sm" href="{{ route('admin.payments.edit', $p->id) }}" role="button"><i class="far fa-edit"></i></a>
                                        <form action="{{ action('Admin\PaymentController@destroy', $p->id )}}" class="payment-delete" method="post" style="display: inline;">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger btn-sm" ><i class="fas fa-times"></i></button>
                                        </form>
                                        <form action="{{ action('Admin\PaymentController@approve', $p->id )}}" class="payment-approve" method="post" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-success btn-sm" >Approve</button>
                                        </form>
                                    @endif
                                    @if ($p->paid_back === 0)
                                        <form action="{{ action('Admin\PaymentController@paidBack', $p->id )}}" class="payment-pay" method="post" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-info btn-sm" >Mark Paid</button>
                                        </form>
                                    @endif
                                    @if ($p->receipt_received === 0)
                                        <form action="{{ action('Admin\PaymentController@receivedReceipt', $p->id )}}" class="payment-received-receipt" method="post" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-info btn-sm" >Received Receipt</button>
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
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
@endsection

@section('scripts')
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        // my custom script
        $(document).ready( function () {
            $('#payment_table').DataTable({
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', 
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                }
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

        // Approve Payment Confirmation
        $('.payment-approve').submit(function(event){
            event.preventDefault()

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve payment!',
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
                        text: "Your payment was not approved",
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

        // Pay Payment Confirmation
        $('.payment-pay').submit(function(event){
            event.preventDefault()

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as paid!',
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
                        text: "Your payment was not marked as paid.",
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

        // Payment Received Receipt Confirmation
        $('.payment-received-receipt').submit(function(event){
            event.preventDefault()

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as received receipt!',
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
                        text: "Your payment was not marked as received receipt.",
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