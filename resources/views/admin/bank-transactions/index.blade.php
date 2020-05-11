@extends('layouts.main')

@section('page-title', 'Bank Transactions')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                        <h2 class="mb-0">Bank Transactions</h2>
                    </div>
                    <div class="col">
                        <p class="text-right float-right"><a class="btn btn-primary" href="{{ route('admin.bank-transactions.create') }}" role="button">Add Transaction</a></p>
                        <p class="text-right float-right mr-3"><a class="btn btn-light" href="{{ route('admin.bank-transactions.export') }}" role="button">Export</a></p>
                    </div>
                </div>
            </div>
            <!-- Payments List -->
            <div class="table-responsive">
                <table class="table table-hover" id="payment_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Amount</th>
                            <th scope="col">Date</th>
                            <th scope="col">Type</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $t)
                            <tr>
                                <td>{{ $t->amount }}</td>
                                <td>{{ date('Y-m-d', strtotime($t->date)) }}</td>
                                <td>
                                    @if ($t->is_logement === 1)
                                        Logement
                                    @else
                                        Withdrawal
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.bank-transactions.edit', $t->id) }}" role="button"><i class="far fa-edit"></i></a>
                                    <form action="{{ action('Admin\BankTransactionsController@destroy', $t->id )}}" method="post" style="display: inline;">
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
            <!-- /.Payments List -->
        </div>
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
</div>
@endsection

@section('scripts')
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" >
        // my custom script
        $(document).ready( function () {
            $('#payment_table').DataTable({
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', 
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                "order": [[ 2, "desc" ]]
            });
        });

        // approve Income Confirmation
        $('.income-approve').submit(function(event){
            event.preventDefault()

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve income!',
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
                        text: "Your income was not approved",
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