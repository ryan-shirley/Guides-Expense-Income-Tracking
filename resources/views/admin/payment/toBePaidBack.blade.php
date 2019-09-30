@extends('layouts.main')

@section('page-title', 'To pay back')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Leaders</h6>
                        <h2 class="mb-0">Who needs paying back</h2>
                    </div>
                </div>
            </div>
            <!-- Leaders List -->
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
                            <th scope="row">{{ \App\USer::find($id)->name }}</th>
                            <td>€{{ $amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- /.Leaders List -->
        </div>
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
</div>
@endsection