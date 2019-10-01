@extends('layouts.main')

@section('page-title', 'Payments')

@section('header')

@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-xl-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                        <h2 class="mb-0">All Payments</h2>
                    </div>
                    <div class="col">
                        <p class="text-right"><a class="btn btn-primary" href="{{ route('admin.payments.create') }}" role="button">Add Payment</a></p>
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
                        <th scope="col">Approved</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $p)
                            <tr>
                                <th scope="row"><a href="{{ route('admin.payments.user.show', $p->user) }}">{{ $p->user->name }}</a></th>
                                <td>{{ $p->title }}</td>
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
                                    @if ($p->approved === 1)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->approved !== 1)
                                        <a class="btn btn-warning btn-sm" href="{{ route('admin.payments.edit', $p->id) }}" role="button"><i class="far fa-edit"></i></a>
                                        <form action="{{ action('Admin\PaymentController@destroy', $p->id )}}" method="post" onSubmit="return confirm('Are you sure you wish to delete?')" style="display: inline;">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger btn-sm" ><i class="fas fa-times"></i></button>
                                        </form>
                                        <form action="{{ action('Admin\PaymentController@approve', $p->id )}}" method="post" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-success btn-sm" >Approve</button>
                                        </form>
                                    @endif
                                    <form action="{{ action('Admin\PaymentController@changePaymentStatus', $p->id )}}" method="post" style="display: inline;">
                                        @csrf
                                        <button class="btn btn-info btn-sm" > Mark
                                        @if ($p->paid_back === 1)
                                            Not Paid
                                        @else
                                            Paid
                                        @endif
                                        </button>
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
    <script type="text/javascript">
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
    </script>
@endsection