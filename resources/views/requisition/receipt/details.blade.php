@extends('layouts.app')

@section('style')
    <style>
        .bottom-title{
            border-top: 2px solid #000;
            text-align: center;
        }

    </style>
@endsection

@section('title')
    Requisition Details
@endsection

@section('content')
    <div class="row" >
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
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Requisition No.</th>
                                        <td>{{ $requisition->requisition_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Requisition Date</th>
                                        <td>{{ $requisition->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Requisition Note</th>
                                        <td>{{ $requisition->note }}</td>
                                    </tr>
                                    <tr>
                                        <th>Approved Note</th>
                                        <td>{{ $requisition->approved_note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <img src="{{ asset('img/logo.png') }}"
                                 style="border-radius: 50%;
                                     opacity: 0.2;
                                     width: 330px;
                                     height: 300px;
                                     position: absolute;
                                     margin-left: 190px;
                                     z-index: 1;
                                     ">

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Project Info</th>
                                    </tr>
                                    <tr>
                                        <th>Project Name</th>
                                        <td>{{ $requisition->project->name ?? ''}}</td>
                                    </tr>
{{--                                    <tr>--}}
{{--                                        <th>Segment Name</th>--}}
{{--                                        <td>{{ $requisition->segment->name ?? '' }}</td>--}}
{{--                                    </tr>--}}
                                    <tr>
                                        <th>Address</th>
                                        <td style="white-space: break-spaces;">{{ $requisition->project->address ?? ''}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Requisition Quantity</th>
                                            <th>Approved Quantity</th>
                                        </tr>
                                        </thead>
                                        @php
                                        $totalApprovedQuantity = 0;
                                        @endphp

                                        <tbody>
                                        @foreach($requisition->requisitionProducts as $product)
                                            @php
                                                $totalApprovedQuantity += $product->approved_quantity;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->unit->name ??'' }}</td>
                                                <td class="text-right">{{ $product->quantity }}</td>
                                                <td class="text-right">{{ $product->approved_quantity ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <!--<tr>-->
                                        <!--    <th colspan="3" class="text-right">Total Amount</th>-->
                                        <!--    <th class="text-right">{{ $requisition->total_quantity }}</th>-->
                                        <!--    <th class="text-right">{{$totalApprovedQuantity}}</th>-->
                                        <!--</tr>-->
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 offset-md-3">
                                <table class="table table-bordered">

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#table-payments').DataTable({
                "order": [[ 0, "desc" ]],
            });
        });
    </script>
    <script>


        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection

