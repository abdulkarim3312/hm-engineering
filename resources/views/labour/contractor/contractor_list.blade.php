@extends('layouts.app')
@section('title')
    Contractor List
@endsection
@section('style')
    <style>
        .row{
            margin: 0;
        }
        hr {
            margin-top: 0rem;
            margin-bottom: 0rem;
            border: 0;
            border-top: 1px solid black;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000 !important;
            vertical-align: middle;
            border-bottom-width: 1px !important;
            border-left-width: 1px !important;
            text-align: center;
            padding: 0.2rem !important;
        }
        @media print{
            @page {
                size: auto;
                margin: 20px !important;
            }
        }
        button, html input[type=button], input[type=reset], input[type=submit] {
            background: #367FA9;
            color: #fff;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')"><i class="fa fa-print"></i> Print</button><br><br>

                </div>
                <div class="box-body">
                    <div id="prinarea">
                        <div class="row">
                            <div class="col-xs-4 text-left">
                                <div class="logo-area-report">
                                    <img width="35%" src="{{ asset('img/head_logo.jpeg') }}">
                                </div>
                            </div>
                            <div class="col-xs-8 text-center" style="margin-left: -123px;">
                            <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                            </div>
                            <div class="col-xs-12 text-center">
                                <h3><u>Contractor List</u></h3>
                            </div>
                        </div>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr >
                                <th class="text-center">ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Trade</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Address</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contractors as $contractor)
                                <tr>
                                    <td class="text-center">{{$contractor->contractor_id}}</td>
                                    <td>{{$contractor->name}}</td>
                                    @if ($contractor->trade == 1)
                                        <td>Civil Engineering</td>
                                    @elseif ($contractor->trade == 2)    
                                        <td>Paint Contractor</td>
                                    @elseif ($contractor->trade == 3)    
                                        <td>Sanitary Contractor</td>
                                    @elseif ($contractor->trade == 4)    
                                        <td>Tiles Contractor</td>
                                    @elseif ($contractor->trade == 5)    
                                        <td>MS Contractor</td>
                                    @elseif ($contractor->trade == 6)   
                                        <td>Wood Contractor</td> 
                                    @elseif ($contractor->trade == 7)  
                                        <td>Electric Contractor</td>  
                                    @elseif ($contractor->trade == 8)  
                                        <td>Thai Contractor</td>  
                                    @else
                                        <td>Other Contractor</td>
                                    @endif
                                    <td class="text-center">{{$contractor->email ?? ''}}</td>
                                    <td class="text-center">{{$contractor->mobile ?? ''}}</td>
                                    <td class="text-center">{{$contractor->address}}</td>
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
