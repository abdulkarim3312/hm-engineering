@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Floor
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
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                            <a class="btn btn-primary" href="{{ route('floor.add') }}">Add floor</a>
                        </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Project</label>

                                <select class="form-control" id="project">
                                    <option value="">All Projects</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Project</th>
                            <th>Floor</th>
                            <th>Size</th>
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
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

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
                            url: "{{ route('floor_delete') }}",
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

            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 100,
                ajax: {
                    url: "{{ route('floor.datatable') }}",
                    data: function (d) {
                        d.project = $('#project').val();
                    }
                },
                columns: [
                      { data: 'project', name: 'project' },
                      { data: 'name', name: 'name' },
                      { data: 'size', name: 'size' },
                      { data: 'status', name: 'status' },
                      { data: 'action', name: 'action', orderable: false }
                ],
            "responsive": true, "autoWidth": false,
        });

            $('#project').change(function () {
                table.ajax.reload();
            });
        });
    </script>
@endsection
