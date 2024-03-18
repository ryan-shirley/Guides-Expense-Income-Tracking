@extends('layouts.main')

@section('page-title', 'Export Payments')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Exporter</h6>
                        <h2 class="mb-0">Export Payments</h2>
                    </div>
                </div>
            </div>
           <data-export api_token="{{ $user->api_token }}" year="{{ $year }}" columns="{{ $columns }}" url="/api/payments/export" />
        </div>
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
</div>
@endsection
