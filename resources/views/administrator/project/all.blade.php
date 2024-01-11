@extends('layouts.app')

@section('title')
    Project
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <a class="btn btn-primary" href="{{ route('project.add') }}">Add Project</a>

                </div>
                <div class="box-body">

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Client</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Amount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Project Progress</th>
                            <th>Attachment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('project.datatable') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'type', name: 'type'},
                    {data: 'client', name: 'client.name'},
                    {data: 'address', name: 'address'},
                    {data: 'phone_number', name: 'phone_number'},
                    {data: 'amount', name: 'amount'},
                    {data: 'duration_start', name: 'duration_start'},
                    {data: 'duration_end', name: 'duration_end'},
                    {data: 'project_progress', name: 'project_progress'},
                    {data: 'attachment', name: 'attachment'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        })
    </script>
@endsection
