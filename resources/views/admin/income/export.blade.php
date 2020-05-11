@extends('layouts.main')

@section('page-title', 'Export Incomes')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Exporter</h6>
                        <h2 class="mb-0">Export Incomes</h2>
                    </div>
                </div>
            </div>
           <data-export-incomes api_token="{{ $user->api_token }}" />
        </div>
        <!-- /.Card -->
    </div>
    <!-- /.Column -->
</div>
@endsection