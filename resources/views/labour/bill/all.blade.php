@extends('layouts.app')
@section('title')
    Labour Employee Bill
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
                    <a class="btn btn-primary" href="{{ route('labour.bill.add') }}">Labour Bill Process</a>

                </div>
                <div class="box-body table-responsive">

                    <table id="table" class="table table-bordered table-striped ">
                        <thead>
                        <tr>
                            <th>Process Date</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Payment Type</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@endsection

@section('script')
    <script>
        $(function () {

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('labour.bill.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'total', name: 'total'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 1, "asc" ]],
            });

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        })
    </script>
@endsection
