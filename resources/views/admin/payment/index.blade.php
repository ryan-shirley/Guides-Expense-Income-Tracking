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
                        <p class="text-right float-right"><a class="btn btn-primary" href="{{ route('admin.payments.create', $year) }}" role="button">Add Payment</a></p>
                        <p class="text-right float-right mr-3"><a class="btn btn-light" href="{{ route('admin.payments.export', $year) }}" role="button">Export</a></p>
                    </div>
                </div>
            </div>

            <payments-component api_token="{{ $user->api_token }}" year="{{ $year }}" />
        </div>
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
    @endsection

    @section('scripts')
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        // my custom script
        $(document).ready(function() {
            $('#payment_table').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "order": [
                    [4, "desc"]
                ]
            });
        });

        // Delete Payment Confirmation
        $('.payment-delete').submit(function(event) {
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
                } else if (result.dismiss === 'cancel') {
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
        $('.payment-approve').submit(function(event) {
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
                } else if (result.dismiss === 'cancel') {
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
        $('.payment-pay').submit(function(event) {
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
                } else if (result.dismiss === 'cancel') {
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
        $('.payment-received-receipt').submit(function(event) {
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
                } else if (result.dismiss === 'cancel') {
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
