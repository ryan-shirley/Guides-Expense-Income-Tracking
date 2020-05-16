@extends('layouts.main')

@section('page-title', $event->title . ' - Payments & Expenses')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">{{ $event->title }}</h6>
                        <h2 class="mb-0">Payments</h2>
                    </div>
                </div>
            </div>
            <!-- Events List -->
            <div class="table-responsive">
                <table class="table table-hover" id="payments_table">
                    <thead class="thead-light">
                        <tr>
                        <th scope="col">Ref</th>
                        <th scope="col">Leader</th>
                        <th scope="col">Title</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Purchase Date</th>
                        <th scope="col">Guide Money</th>
                        <th scope="col">Paid Back</th>
                        <th scope="col">Received Receipt</th>
                        <th scope="col">Type</th>
                        <th scope="col">Code</th>
                        <th scope="col">Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $p)
                            <tr>
                                <td scope="row">{{ $p->keyID }}</td>
                                <th>{{ $p->user->name }}</th>
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
                                    @if ($p->is_cash === 1)
                                        Cash
                                    @else
                                        Other
                                    @endif
                                </td>
                                <td>
                                   {{ $p->code }}
                                </td>
                                <td>
                                    @if ($p->approved === 1)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
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

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">{{ $event->title }}</h6>
                        <h2 class="mb-0">Incomes</h2>
                    </div>
                </div>
            </div>
            <!-- Incomes List -->
            <div class="table-responsive">
                <table class="table table-hover" id="incomes_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Ref</th>
                            <th scope="col">Title</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Date</th>
                            <th scope="col">Type</th>
                            <th scope="col">Code</th>
                            <th scope="col">Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $i)
                            <tr>
                                <td>{{ $i->keyID }}</td>
                                <td>{{ $i->title }}</td>
                                <td>€{{ $i->amount }}</td>
                                <td>{{ date('Y-m-d', strtotime($i->date)) }}</td>
                                <td>
                                    @if ($i->is_cash === 1)
                                        Cash or Cheque
                                    @else
                                        Online
                                    @endif</td>
                                <td>{{ $i->code }}</td>
                                <td>
                                    @if ($i->approved === 1)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.Incomes List -->
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
            $('#payments_table').DataTable({
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', 
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                "order": [[ 4, "desc" ]]
            });

            $('#incomes_table').DataTable({
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', 
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                "order": [[ 3, "desc" ]]
            });
        });
    </script>
@endsection