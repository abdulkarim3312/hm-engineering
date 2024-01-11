@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Employee Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
                    <li><a href="#salary" data-toggle="tab">Salary</a></li>
                    <li><a href="#designation_log" data-toggle="tab">Designation Log</a></li>
                    <li><a href="#leave" data-toggle="tab">Leave</a></li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="profile">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea_profile')">Print</button><br>

                        <div id="prinarea_profile">
                           <div class="row">
                               <div class="col-xs-4 text-left">
                                   <div class="logo-area-report">
                                       <img width="35%" src="{{ asset('img/head_logo.jpeg') }}">
                                   </div>
                               </div>
                               <div class="col-xs-8 text-center">
                                   <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                               </div>
                               <div class="col-xs-12 text-center">
                                   <h3><u>CV</u></h3>
                               </div>
                           </div>
                            <div class="row">
                                <div class="col-xs-8">
                                    <table class="table table-bordered" >
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $employee->name ?? ''}}</td>
                                        </tr>

                                        <tr>
                                            <th>Employee ID</th>
                                            <td>{{ $employee->employee_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td>
                                                @if ($employee->dob!=null)
                                                    {{ $employee->dob->format('j F, Y') }}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Joining Date</th>
                                            <td>
                                                @if ($employee->joining_date!=null)
                                                    {{ $employee->joining_date->format('j F, Y') }}
                                                @endif


                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Confirmation Date</th>

                                            <td>

                                                @if ($employee->confirmation_date!=null)
                                                    {{ $employee->confirmation_date->format('j F, Y') }}
                                                @endif

                                            </td>
                                        </tr>



                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $employee->department->name ?? ''}}</td>
                                        </tr>

                                        <tr>
                                            <th>Designation</th>
                                            <td>{{ $employee->designation->name ?? ''}}</td>
                                        </tr>

                                        <tr>
                                            <th>Project Name</th>
                                            <td>{{ $employee->project->name ?? ''}}</td>
                                        </tr>

                                        <tr>
                                            <th>Employee Type</th>
                                            <td>
                                                @if($employee->employee_type == 1)
                                                    Permanent
                                                @else
                                                    Temporary
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Reporting To</th>
                                            <td>{{ $employee->reporting_to }}</td>
                                        </tr>

                                        <tr>
                                            <th>Gender</th>
                                            <td>
                                                @if($employee->gender == 1)
                                                    Male
                                                @else
                                                    Female
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Marital Status</th>
                                            <td>
                                                @if($employee->marital_status == 1)
                                                    Single
                                                @else
                                                    Married
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Mobile No.</th>
                                            <td>{{ $employee->mobile_no }}</td>
                                        </tr>

                                        <tr>
                                            <th>Father Name</th>
                                            <td>{{ $employee->father_name ?? ''}}</td>
                                        </tr>

                                        <tr>
                                            <th>Mother Name</th>
                                            <td>{{ $employee->mother_name ?? ''}}</td>
                                        </tr>

                                        <tr>
                                            <th>Emergency Contact</th>
                                            <td>{{ $employee->emergency_contact }}</td>
                                        </tr>

                                        <tr>
                                            <th>Present Address</th>
                                            <td>{{ $employee->present_address }}</td>
                                        </tr>

                                        <tr>
                                            <th>Permanent Address</th>
                                            <td>{{ $employee->permanent_address }}</td>
                                        </tr>

                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $employee->email }}</td>
                                        </tr>

                                        <tr>
                                            <th>Religion</th>
                                            <td>
                                                @if($employee->religion == 1)
                                                    Muslim
                                                @elseif($employee->religion == 2)
                                                    Hindu
                                                @elseif($employee->religion == 3)
                                                    Christian
                                                @elseif($employee->religion == 4)
                                                    Other
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Bank Name</th>
                                            <td>{{ $employee->bank_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Branch</th>
                                            <td>{{ $employee->bank_branch ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Account</th>
                                            <td>{{ $employee->bank_account }}</td>
                                        </tr>
                                        <tr>
                                            <th>Gross Salary</th>
                                            <td>{{ number_format($employee->gross_salary,2)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Previous Salary</th>
                                            <td>{{ number_format($employee->previous_salaray,2) }}</td>
                                        </tr>


                                    </table>
                                </div>
                                <div class="col-xs-4 text-center">
                                    @if($employee->photo)
                                        <img class="img-thumbnail" src="{{ asset($employee->photo) }}" width="150px"> <br><br>
                                    @endif
                                    @if ($employee->signature)

                                        <img class="img-thumbnail" src="{{ asset($employee->signature) }}" width="150px"> <br><br>
                                    @endif

                                    @if($employee->cv)
                                        <a href="{{ asset($employee->cv) }}" class="btn btn-primary btn-sm" download>Download CV</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="salary">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Basic Salary</th>
                                    <td>৳{{ $employee->basic_salary }}</td>
                                </tr>
                                <tr>
                                    <th>House Rent</th>
                                    <td>৳{{ $employee->house_rent }}</td>
                                </tr>
                                <tr>
                                    <th>Travel</th>
                                    <td>৳{{ $employee->travel }}</td>
                                </tr>
                                <tr>
                                    <th>Medical</th>
                                    <td>৳{{ $employee->medical }}</td>
                                </tr>
                                <tr>
                                    <th>Tax</th>
                                    <td>৳{{ $employee->tax }}</td>
                                </tr>
                                <tr>
                                    <th>Others Deduct</th>
                                    <td>৳{{ $employee->others_deduct }}</td>
                                </tr>
                                <tr>
                                    <th>Gross Salary</th>
                                    <th>৳{{ $employee->gross_salary }}</th>
                                </tr>
                            </table>
                        </div>

                        <div id="prinarea_salary">

                            <h3 class="text-center"><u>Salary Change Log</u></h3>
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Basic Salary</th>
                                        <th>House Rent</th>
                                        <th>Travel</th>
                                        <th>Medical</th>
                                        <th>Tax</th>
                                        <th>Others Deduct</th>
                                        <th>Gross Salary</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($employee->salaryChangeLog as $log)
                                        <tr>
                                            <td>{{ $log->date->format('j F, Y') }}</td>
                                            <td>
                                                @if($log->type == 1)
                                                    Confirmation
                                                @elseif($log->type == 2)
                                                    Yearly Increment
                                                @elseif($log->type == 3)
                                                    Promotion
                                                @elseif($log->type == 4)
                                                    Other
                                                @elseif($log->type == 5)
                                                    Initial
                                                @endif
                                            </td>
                                            <td>৳{{ $log->basic_salary }}</td>
                                            <td>৳{{ $log->house_rent }}</td>
                                            <td>৳{{ $log->travel }}</td>
                                            <td>৳{{ $log->medical }}</td>
                                            <td>৳{{ $log->tax }}</td>
                                            <td>৳{{ $log->others_deduct }}</td>
                                            <td>৳{{ $log->gross_salary }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <h3 class="text-center"><u>Salary Advance Log</u></h3>
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Year</th>
                                        <th>Month</th>
                                        <th>Advance Amount</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($employee->salaryAdvanceLog as $log)
                                        <tr>
                                            <td>{{ $log->date->format('j F, Y') }}</td>
                                            <td>
                                                @if($log->type == 4)
                                                    Other
                                                @elseif($log->type == 5)
                                                    Advance
                                                @endif
                                            </td>
                                            <td>{{ $log->year  }}</td>
                                            <?php
                                            $dateObj   = DateTime::createFromFormat('!m', $log->month);
                                            $monthName = $dateObj->format('F'); // March
                                            ?>
                                            <td>{{ $monthName }}</td>

                                            <td>৳{{ $log->advance }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="designation_log">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($employee->designationLogs as $log)
                                    <tr>
                                        <td>

                                            @if ($log->date!=null)
                                                {{ $log->date->format('j F, Y') }}
                                            @endif
                                            </td>
                                        <td>{{ $log->department->name ?? ''}}</td>
                                        <td>{{ $log->designation->name ?? ''}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="leave">
                        <button class="pull-right btn btn-primary" onclick="getprintleave('prinarea_leave')">Print</button><br>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box" style="border: 0px solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Filter</h3>
                                    </div>
                                    <!-- /.box-header -->

                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Year</label>

                                                    <select class="form-control" id="leave-year">
                                                        @for($i=2020; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="prinarea_leave">
                            <div class="table-responsive " id="leave-table" >
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Days</th>
                                        <th>Note</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td>
                                                @if($leave->type == 1)
                                                    Annual
                                                @elseif($leave->type == 2)
                                                    Casual
                                                @elseif($leave->type == 3)
                                                    Sick
                                                @elseif($leave->type == 4)
                                                    Others
                                                @endif
                                            </td>
                                            <td>{{ $leave->from->format('j F, Y') }}</td>
                                            <td>{{ $leave->to->format('j F, Y') }}</td>
                                            <td>{{ $leave->total_days }}</td>
                                            <td>{{ $leave->note }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th colspan="3">Total Days</th>
                                        <th>{{ $leaves->sum('total_days') }}</th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#leave-year').change(function () {
                var year = $(this).val();
                var employeeId = '{{ $employee->id }}';

                $.ajax({
                    method: "POST",
                    url: "{{ route('employee.get_leaves') }}",
                    data: { year: year, employeeId: employeeId }
                }).done(function( response ) {
                    $('#leave-table').html(response.html);
                });
            });
        })
    </script>
    <script>

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea_profile) {

            $('body').html($('#'+prinarea_profile).html());
            window.print();
            window.location.replace(APP_URL)
        }
        function getprintleave(prinarea_leave) {

            $('body').html($('#'+prinarea_leave).html());
            window.print();
            window.location.replace(APP_URL)
        }
        function getprintleave(prinarea_salary) {

            $('body').html($('#'+prinarea_salary).html());
            window.print();
            window.location.replace(APP_URL)
        }

    </script>
@endsection
