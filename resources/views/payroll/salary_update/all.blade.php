@extends('layouts.app')
@section('title')
    Salary Update
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
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Type</th>
                            <th>Mobile</th>
                            <th>Gross Salary</th>
                            <th width="18%">Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-update-salary">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update Salary</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input class="form-control" id="modal-employee-id" disabled>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" id="modal-name" disabled>
                        </div>

                        <div class="form-group">
                            <label>Department</label>
                            <input class="form-control" id="modal-department" disabled>
                        </div>

                        <div class="form-group">
                            <label>Designation</label>
                            <input class="form-control" id="modal-designation" disabled>
                        </div>

                        <input type="hidden" name="id" id="modal-id">

                        <div class="form-group">
                            <label>Basic Salary</label>
                            <input type="text" class="form-control" name="basic_salary" id="modal-basic-salary" placeholder="Enter Basic Salary" readonly>
                        </div>

                        <div class="form-group">
                            <label>House Rent</label>
                            <input type="text" class="form-control" name="house_rent" id="modal-house-rent" placeholder="Enter House Rent" readonly>
                        </div>

                        <div class="form-group">
                            <label>Travel</label>
                            <input type="text" class="form-control" name="travel" id="modal-travel" placeholder="Enter Travel" readonly>
                        </div>

                        <div class="form-group">
                            <label>Medical</label>
                            <input type="text" class="form-control" name="medical" id="modal-medical" placeholder="Enter Medical" readonly>
                        </div>

                        <div class="form-group">
                            <label>Tax</label>
                            <input type="text" class="form-control" name="tax" id="modal-tax" placeholder="Enter Tax">
                        </div>

                        <div class="form-group">
                            <label>Others Deduct</label>
                            <input type="text" class="form-control" name="others_deduct" id="modal-others-deduct" placeholder="Enter Others Deduct">
                        </div>

                        <div class="form-group">
                            <label>Gross Salary</label>
                            <input type="text" class="form-control" name="gross_salary" id="modal-gross-salary" placeholder="Enter Gross Salary">
                        </div>

                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type" id="update_type">
                                <option value="">Select Type</option>
                                <option value="1">Confirmation</option>
                                <option value="2">Yearly Increment</option>
                                <option value="3">Promotion</option>
                                <option value="6">Bonus</option>
                                <option value="4">Other</option>
                            </select>
                        </div>

                        <div class="form-group" id="modal-bonus-salary">
                            <label>Bonus Salary</label>
                            <input type="text" class="form-control" id="modal-bonus-salary-value" name="bonus_salary"  placeholder="Enter Bonus Salary">
                        </div>

                        <div class="form-group">
                            <label>Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="modal-date" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-update">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-advance-salary">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Advance Salary</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-advance-form" enctype="multipart/form-data" name="modal-advance-form">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input class="form-control" id="modal-advance-employee-id" disabled>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" id="modal-advance-name" disabled>
                        </div>

                        <div class="form-group">
                            <label>Department</label>
                            <input class="form-control" id="modal-advance-department" disabled>
                        </div>

                        <div class="form-group">
                            <label>Designation</label>
                            <input class="form-control" id="modal-advance-designation" disabled>
                        </div>

                        <input type="hidden" name="id" id="modal-advance-id">

                        <div class="form-group">
                            <label>Year</label>
                            <select class="form-control" name="year" id="year">
                                <option value="">Select Year</option>
                                @for($i=2022; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ request()->get('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Advance Month</label>
                            <select class="form-control" name="month" id="month">
                                <option value="">Select Month</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type">
                                <option value="5">Advance</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Payment Type</label>
                            <select class="form-control" id="modal-pay-type" name="payment_type">
                                <option value="1">Cash</option>
                                <option value="2">Bank</option>
                                <option value="3">Mobile Banking</option>
                            </select>
                        </div>

                        <div id="modal-bank-info">
                            <div class="form-group">
                                <label>Bank<span class="text-danger">*</span></label>
                                <select class="form-control modal-bank" name="bank">
                                    <option value="">Select Bank</option>

{{--                                    @foreach($banks as $bank)--}}
{{--                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>--}}
{{--                                    @endforeach--}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Branch <span class="text-danger">*</span></label>
                                <select class="form-control modal-branch" name="branch">
                                    <option value="">Select Branch</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Account <span class="text-danger">*</span></label>
                                <select class="form-control modal-account" name="account">
                                    <option value="">Select Account</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Cheque No.</label>
                                <input class="form-control" type="text" name="cheque_no" placeholder="Enter Cheque No.">
                            </div>

                            <div class="form-group">
                                <label>Cheque Image</label>
                                <input class="form-control" name="cheque_image" type="file">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="amount" placeholder="Enter advance salary amount">
                        </div>

                        <div class="form-group">
                            <label>Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right date"  name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->
                        </div>

                        <div class="form-group">
                            <label>Note</label>
                            <input class="form-control" name="note" placeholder="Enter Note">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-advance">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('payroll.salary_update.datatable') }}',
                columns: [
                    {data: 'photo', name: 'photo', orderable: false},
                    {data: 'employee_id', name: 'employee_id'},
                    {data: 'name', name: 'name'},
                    {data: 'department', name: 'department.name'},
                    {data: 'designation', name: 'designation.name'},
                    {data: 'employee_type', name: 'employee_type'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'gross_salary', name: 'gross_salary'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 1, "asc" ]],
            });

            //Date picker
            $('#date,.date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('body').on('click', '.btn-update', function () {
                var employeeId = $(this).data('id');
                $('#modal-bonus-salary').hide();
                $.ajax({
                    method: "GET",
                    url: "{{ route('get_employee_details') }}",
                    data: { employeeId: employeeId }
                }).done(function( response ) {
                    console.log(response);
                    $('#modal-employee-id').val(response.employee_id);
                    $('#modal-name').val(response.name);
                    $('#modal-department').val(response.department.name);
                    $('#modal-designation').val(response.designation.name);
                    $('#modal-id').val(response.id);
                    $('#modal-basic-salary').val(response.basic_salary);
                    $('#modal-house-rent').val(response.house_rent);
                    $('#modal-travel').val(response.travel);
                    $('#modal-medical').val(response.medical);
                    $('#modal-tax').val(response.tax);
                    $('#modal-others-deduct').val(response.others_deduct);
                    $('#modal-gross-salary').val(response.gross_salary);

                    if (response.bonus){
                        $('#modal-bonus-salary-value').val(response.bonus);
                    }else {
                        $('#modal-bonus-salary-value').val('');
                    }


                    $('#modal-update-salary').modal('show');
                });
            });

            $('#modal-btn-update').click(function () {
                var formData = new FormData($('#modal-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('payroll.salary_update.post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-update-salary').modal('hide');
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

            $('body').on('change','#update_type',function (){
                var typeId= $('#update_type').val();

                if (typeId==6){
                    $('#modal-bonus-salary').show();
                }else {
                    $('#modal-bonus-salary').hide();
                }

            });


            $('body').on('click', '.btn-advance', function () {
                var employeeId = $(this).data('id');

                $.ajax({
                    method: "GET",
                    url: "{{ route('get_employee_details') }}",
                    data: { employeeId: employeeId }
                }).done(function( response ) {
                    console.log(response);
                    $('#modal-advance-employee-id').val(response.employee_id);
                    $('#modal-advance-name').val(response.name);
                    $('#modal-advance-department').val(response.department.name);
                    $('#modal-advance-designation').val(response.designation.name);
                    $('#modal-advance-id').val(response.id);
                    $('#modal-advance-salary').modal('show');
                });
            });
            $('#modal-btn-advance').click(function () {
                var formData = new FormData($('#modal-advance-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('employee.salary_advance_post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-advance-salary').modal('hide');
                            Swal.fire(
                                'Advanced!',
                                response.message,
                                'success'
                            ).then((result) => {
                               // location.reload();
                                window.location.href = response.redirect_url;
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
            $('#year').change(function () {
                var year = $(this).val();
                $('#month').html('<option value="">Select Month</option>');

                if (year != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_month') }}",
                        data: { year: year }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            $('#month').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });

            //payment information
            $('#modal-pay-type').change(function () {
                if ($(this).val() == '2') {
                    $('#modal-bank-info').show();
                } else {
                    $('#modal-bank-info').hide();
                }
            });

            $('#modal-pay-type').trigger('change');
            $('.modal-bank').change(function () {
                var bankId = $(this).val();
                $('.modal-branch').html('<option value="">Select Branch</option>');
                $('.modal-account').html('<option value="">Select Account</option>');

                if (bankId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_branch') }}",
                        data: { bankId: bankId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            $('.modal-branch').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('.modal-branch').trigger('change');
                    });
                }

                $('.modal-branch').trigger('change');
            });

            $('.modal-branch').change(function () {
                var branchId = $(this).val();
                $('.modal-account').html('<option value="">Select Account</option>');

                if (branchId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bank_account') }}",
                        data: { branchId: branchId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            $('.modal-account').append('<option value="'+item.id+'">'+item.account_no+'</option>');
                        });
                    });
                }
            });
        })
    </script>
@endsection
