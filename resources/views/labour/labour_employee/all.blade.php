@extends('layouts.app')

@section('title')
    Labour Employee
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
                    <a class="btn btn-primary" href="{{ route('labour.add') }}">Add Labour Employee</a>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Labour ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Project</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- /.modal -->
    <div class="modal fade" id="modal-bonus-salary">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Bonus Salary </h4>
                </div>
                <div class="modal-body">
                    <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input class="form-control" id="modal-bonus-id" name="modal_bonus_id" readonly>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" id="modal-bonus-name" disabled>
                        </div>

                        <div class="form-group">
                            <label>bonus amount</label>
                            <input type="text" class="form-control" name="modal_bonus_amount" id="modal-bonus-amount" >
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-bonus">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')

    <script>
        $(function () {

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('labour.datatable') }}',
                columns: [
                    {data: 'photo', name: 'photo', orderable: false},
                    {data: 'labour_employee_id', name: 'labour_employee_id'},
                    {data: 'name', name: 'name'},
                    {data: 'project', name: 'project.name'},
                    {data: 'designation', name: 'designation.name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 1, "asc" ]],
            });

            $('body').on('click','.btn-bonus',function (){
                    var labourId= $(this).data('id');
                    //alert(labouId);
                console.log(labourId);

                $('#modal-bonus-salary').hide();
                $.ajax({
                    method: "GET",
                    url: "{{ route('get_labour_details') }}",
                    data: { labourId: labourId }
                }).done(function( response ) {
                    console.log(response);
                    $('#modal-bonus-id').val(response.id);
                    $('#modal-bonus-name').val(response.name);
                    $('#modal-bonus-amount').val(response.bonus);


                    $('#modal-bonus-salary').modal('show');
                });

            });


            $('#modal-btn-bonus').click(function () {
                var formData = new FormData($('#modal-form')[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('labour.bonus.edit.post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-bonus-salary').modal('hide');
                            Swal.fire(
                                'Updated!',
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
                    }
                });
            });




            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

        })
    </script>
@endsection
