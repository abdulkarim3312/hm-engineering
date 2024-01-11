@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Labour Employee Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
{{--                    <li><a href="#salary" data-toggle="tab">Salary</a></li>--}}
{{--                    <li><a href="#designation_log" data-toggle="tab">Designation Log</a></li>--}}
{{--                    <li><a href="#leave" data-toggle="tab">Leave</a></li>--}}
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
                           </div>
                            <div class="row">
                                <div class="col-xs-8">
                                    <table class="table table-bordered" >
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $labour->name }}</td>
                                        </tr>

                                        <tr>
                                            <th>Employee ID</th>
                                            <td>{{ $labour->labour_employee_id }}</td>
                                        </tr>

                                        <tr>
                                            <th>Joining Date</th>
                                            <td>
                                                @if ($labour->joining_date!=null)
                                                    {{ $labour->joining_date }}
                                                @endif


                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Designation</th>
                                            <td>{{ $labour->designation->name }}</td>
                                        </tr>

                                        <tr>
                                            <th>Project Name</th>
                                            <td>{{ $labour->project->name }}</td>
                                        </tr>

                                        <tr>
                                            <th>Gender</th>
                                            <td>
                                                @if($labour->gender == 1)
                                                    Male
                                                @else
                                                    Female
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Mobile No.</th>
                                            <td>{{ $labour->mobile_no }}</td>
                                        </tr>

                                        <tr>
                                            <th>Father Name</th>
                                            <td>{{ $labour->father_name }}</td>
                                        </tr>

                                        <tr>
                                            <th>Mother Name</th>
                                            <td>{{ $labour->mother_name }}</td>
                                        </tr>

                                        <tr>
                                            <th>Present Address</th>
                                            <td>{{ $labour->present_address }}</td>
                                        </tr>

                                        <tr>
                                            <th>Permanent Address</th>
                                            <td>{{ $labour->permanent_address }}</td>
                                        </tr>

                                        <tr>
                                            <th>Religion</th>
                                            <td>
                                                @if($labour->religion == 1)
                                                    Muslim
                                                @elseif($labour->religion == 2)
                                                    Hindu
                                                @elseif($labour->religion == 3)
                                                    Christian
                                                @elseif($labour->religion == 4)
                                                    Other
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Per Day Amount</th>
                                            <td>{{ number_format($labour->per_day_amount,2)}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-4 text-center">
                                    @if($labour->photo)
                                        <img class="img-thumbnail" src="{{ asset($labour->photo) }}" width="150px"> <br><br>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
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

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea_profile) {

            $('body').html($('#'+prinarea_profile).html());
            window.print();
            window.location.replace(APP_URL)
        }

    </script>
@endsection
