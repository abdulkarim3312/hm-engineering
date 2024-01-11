@extends('layouts.app')

@section('title')
    Flat/Shop
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
                                <a class="btn btn-primary" href="{{ route('apartment.add') }}">Add Flat/Shop</a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Project</label>

                                <select class="form-control" id="project">
                                    <option value="all">All Projects</option>
                                    @foreach($projects as $project)
                                        <option data-project-id="{{$project->id}}">{{$project->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="box-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Project</th>
                                <th>Floor</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                               @foreach($flats as $flat)
                                    <tr>
                                        <td>{{ $flat->project->name }}</td>
                                        <td>{{ $flat->floor->name }}</td>
                                        <td>{{ $flat->name }}</td>
                                        <td>
                                            @if($flat->type == 1)
                                            Apartment
                                            @elseif($flat->type == 2)
                                             Shop
                                             @elseif($flat->type == 4)
                                                Car Parking
                                             @else
                                             Commercial Space
                                             @endif
                                        </td>

                                        <td>{{ $flat->size }}</td>
                                        <td>
                                            @if ($flat->status == 1)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('flat.edit', ['flat' => $flat->id]) }}">Edit </a>
                                             <a class="btn btn-danger btn-sm btn-delete" data-id="{{ $flat->id }}" role="button"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                               @endforeach
                            </tbody>

                        </table>
                    </div>
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
                        url: "{{ route('flat_delete') }}",
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
        // $(function () {
        //     $('#table').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         ajax: '{{ route('flat.datatable') }}',
        //         columns: [
        //             {data: 'project', name: 'project.name'},
        //             {data: 'floor', name: 'floor.name'},
        //             {data: 'type', name: 'type', searchable: false},
        //             {data: 'name', name: 'name'},
        //             {data: 'size', name: 'size'},
        //             {data: 'status', name: 'status'},
        //             {data: 'action', name: 'action', orderable: false},
        //         ],
        //     });
        // })

        document.getElementById('project').addEventListener('change', function() {
            // Get the selected project ID
            var selectedProjectId = this.value;

            // Get all the rows in the table
            var rows = document.querySelectorAll('#table tbody tr');

            // Loop through each row
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];

                // Get the project name cell in the row
                var projectCell = row.querySelector('td:first-child');

                // If the selected project is "all" or the project name in the row matches the selected project, show the row
                if (selectedProjectId === 'all' || projectCell.textContent === selectedProjectId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

    </script>
@endsection
