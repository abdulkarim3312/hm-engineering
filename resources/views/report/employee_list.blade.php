@extends('layouts.app')
@section('title')
    Employee List
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')"><i class="fa fa-print"></i> Print</button>
                </div>
                <div class="box-body">
                    <div id="prinarea">
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
                              <h3><u>Employee List</u></h3>
                          </div>
                      </div>
                         <table id="table" class="table table-bordered table-striped">
                             <thead>
                             <tr >
                                 <th class="text-center">ID</th>
                                 <th class="text-center">Name</th>
                                 <th class="text-center">Designation</th>
                                 <th class="text-center">Department</th>
                                 <th class="text-center">Provident Fund</th>
                                 <th class="text-center">Mobile</th>
                             </tr>
                             </thead>
                             <tbody>
                             @foreach($employees as $employee)
                                 <tr>
                                     <td class="text-center">{{$employee->employee_id}}</td>
                                     <td>{{$employee->name}}</td>
                                     <td class="text-center">{{$employee->designation->name}}</td>
                                     <td class="text-center">{{$employee->department->name}}</td>
                                     <td class="text-center">{{$employee->provident_fund}}</td>
                                     <td class="text-center">{{$employee->mobile_no}}</td>
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
    <script>

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
