@extends('layouts.main')

@section('page-title', 'Income')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-xl-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                        <h2 class="mb-0">Incoming Money</h2>
                    </div>
                    <div class="col">
                        <p class="text-right"><a class="btn btn-primary" href="{{ route('admin.incomes.create') }}" role="button">Add Income</a></p>
                    </div>
                </div>
            </div>
            <!-- Payments List -->
            <div class="table-responsive">
                <table class="table table-hover" id="payment_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Date</th>
                            <th scope="col">Approved</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $i)
                            <tr>
                                <td>{{ $i->title }}</td>
                                <td>€{{ $i->amount }}</td>
                                <td>{{ date('d M Y', strtotime($i->date)) }}</td>
                                <td>
                                    @if ($i->approved === 1)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($i->approved !== 1)
                                        <a class="btn btn-warning btn-sm" href="{{ route('admin.incomes.edit', $i->id) }}" role="button"><i class="far fa-edit"></i></a>
                                        <form action="{{ action('Admin\IncomeController@destroy', $i->id )}}" class="income-delete" method="post" style="display: inline;">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger btn-sm" ><i class="fas fa-times"></i></button>
                                        </form>
                                        <form action="{{ action('Admin\IncomeController@approve', $i->id )}}" method="post" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Approve</button>
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
</div>
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
                }
            });
        });

        // Delete Income Confirmation
        $('.income-delete').submit(function(event){
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