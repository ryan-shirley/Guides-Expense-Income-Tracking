@extends('layouts.main')

@section('page-title', 'Events')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-transparent mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                        <h2 class="mb-0">Events</h2>
                    </div>
                    <div class="col">
                        <p class="text-right float-right"><a class="btn btn-primary" href="{{ route('admin.events.create') }}" role="button">Add Event</a></p>
                        <!-- <p class="text-right float-right mr-3"><a class="btn btn-light" href="{{ route('admin.bank-transactions.export') }}" role="button">Export</a></p> -->
                    </div>
                </div>
            </div>
            <!-- Events List -->
            <div class="table-responsive">
                <table class="table table-hover" id="events_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $e)
                            <tr>
                                <td><a href="{{ route('admin.events.show', $e->id) }}">{{ $e->title }}</a></td>
                                <td>{{ date('Y-m-d', strtotime($e->start_date)) }}</td>
                                <td>{{ date('Y-m-d', strtotime($e->end_date)) }}</td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.events.edit', $e->id) }}" role="button"><i class="far fa-edit"></i></a>
                                    <form action="{{ action('Admin\EventController@destroy', $e->id )}}" method="post" style="display: inline;">
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
            $('#events_table').DataTable({
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', 
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                "order": [[ 1, "desc" ]]
            });
        });
    </script>
@endsection