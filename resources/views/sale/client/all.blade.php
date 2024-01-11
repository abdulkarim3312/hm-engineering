@extends('layouts.app')

@section('title')
    Client
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
                    <a class="btn btn-primary" href="{{ route('client.add') }}">Add Client</a>

                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID No.</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Profession</th>
                                <th>Country</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>DOB</th>
                                <th>Ann-day</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="text-right" colspan="7">Total</th>
                                <th>{{ number_format($clients->sum('total'), 2) }}</th>
                                <th>{{ number_format($clients->sum('paid'), 2) }}</th>
                                <th>{{ number_format($clients->sum('due'), 2) }}</th>
                                <th colspan="2"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('body').on('click', '.btn-delete', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "Post",
                            url: "{{ route('client_delete') }}",
                            data: { id: id }
                        }).done(function( response ) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });

                    }
                })

            });
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('client.datatable') }}',
                columns: [
                    {data: 'id_no', name: 'id_no'},
                    {data: 'image', name: 'image'},
                    {data: 'name', name: 'name'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'country', name: 'country.name'},
                    {data: 'mobile_no', name:'mobile_no'},
                    {data: 'address', name: 'address'},
                    {data: 'email', name: 'email'},
                    {data: 'birthday', name: 'birthday'},
                    {data: 'marrige_day', name: 'marrige_day'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due', name: 'due'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        })
    </script>
@endsection
