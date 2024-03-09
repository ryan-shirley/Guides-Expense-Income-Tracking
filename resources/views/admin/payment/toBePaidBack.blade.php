@extends('layouts.main')

@section('page-title', 'To pay back')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Leaders</h6>
                        <h2 class="mb-0">Who needs paying back</h2>
                    </div>
                </div>
            </div>
            @if(count($leadersToPayBack) === 0) 
                <div class="card-body">
                    <p>Well done you have paid back everyone back!</p>
                </div>
            @else
                <!-- Leaders List -->
                <div class="table-responsive">
                    <table class="table table-hover" id="payment_table">
                        <thead class="thead-light">
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
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
</div>
@endsection