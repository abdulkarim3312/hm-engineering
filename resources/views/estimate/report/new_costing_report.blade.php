@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">

    <style>
        .table-bordered {
            border: 2px solid #000!important;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1.5px solid #000!important;
        }
        hr {
            margin-top: 10px;
            margin-bottom: 10px;
            border: 0;
            border-top: 1px solid #eee;
        }
        input.form-control.btn.btn-primary.submit {
            background-color: #337ab7;
            color: #fff;
        }
    </style>
@endsection

@section('title')
    Costing Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <form action="{{ route('costing_report') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Project</label>
                                    <select class="form-control select2 project" name="project" id="project" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option {{ request()->get('project') == $project->id ? 'selected' : '' }} value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>	&nbsp;</label>
                                    <input class="form-control btn btn-primary submit" type="submit" value="Submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php
        $grandTotal = 0;
    @endphp

    <div class="row">
        <div class="col-sm-12" style="min-height:300px">
            @if(request()->get('project'))
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>
                        <div class="adv-table" id="prinarea">
                            <div class="row" style="margin-bottom: 10px">
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
                            @php
                                $grandTotal = 0;
                            @endphp
                            <hr>
                            <div class="row">
                                <div style="padding:5px; width:100%; text-align:center;">
                                    <h3><strong>Project Name: {{$projectName->name }}</strong></h3>
                                    <h4><strong>Costing Report</strong></h4>
                                </div>
                            </div>
                            <hr>
                            @if($pileConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Pile Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Pile No</th>
                                                <th class="text-center">Pile Costing Item</th>
                                            </tr>
                                            @php
                                                $totalPileCost = 0;
                                                $totalBricksChips = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($pileConfigures as $pileConfigure)
                                                @php
                                                    if ($pileConfigure->course_aggregate_type == 1){
                                                        $totalPileCost += $pileConfigure->total_pile_bar_price + $pileConfigure->total_pile_cement_bag_price
                                                                     + $pileConfigure->total_pile_sands_price + $pileConfigure->total_pile_s_sands_price + $pileConfigure->total_pile_aggregate_price;
                                                    }elseif($pileConfigure->course_aggregate_type == 2){
                                                        $totalBricksChips += $pileConfigure->total_pile_bar_price + $pileConfigure->total_pile_cement_bag_price
                                                                     + $pileConfigure->total_pile_sands_price + $pileConfigure->total_pile_s_sands_price +$pileConfigure->total_pile_rmc_price +
                                                                     $pileConfigure->total_pile_picked_price;
                                                    }else{
                                                        $totalRmc += $pileConfigure->total_pile_bar_price
                                                                    +$pileConfigure->total_pile_rmc_price;
                                                    }

                                                    $grandTotal += $pileConfigure->total_pile_bar_price + $pileConfigure->total_pile_cement_bag_price
                                                                     + $pileConfigure->total_pile_sands_price + $pileConfigure->total_pile_s_sands_price+$pileConfigure->total_pile_rmc_price+$pileConfigure->total_pile_aggregate_price +
                                                                     $pileConfigure->total_pile_picked_price;

                                                @endphp
                                                <tr>
                                                    <th>Pile Configure No-{{$pileConfigure->pile_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($pileConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$pileConfigure->total_pile_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$pileConfigure->total_pile_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$pileConfigure->total_pile_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$pileConfigure->total_pile_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$pileConfigure->total_pile_aggregate_price}}</b></span><br>
                                                        @elseif ($pileConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$pileConfigure->total_pile_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$pileConfigure->total_pile_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$pileConfigure->total_pile_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$pileConfigure->total_pile_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$pileConfigure->total_pile_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$pileConfigure->total_pile_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$pileConfigure->total_pile_rmc_price}}</b></span>
                                                        @endif
                                                        <hr>
                                                        @if ($pileConfigure->course_aggregate_type == 1)
                                                            <span>Sub Total:<b>{{$pileConfigure->total_pile_bar_price + $pileConfigure->total_pile_cement_bag_price
                                                                + $pileConfigure->total_pile_sands_price + $pileConfigure->total_pile_s_sands_price+$pileConfigure->total_pile_aggregate_price}}
                                                            </b></span><br>
                                                        @elseif ($pileConfigure->course_aggregate_type == 2)
                                                            <span>Sub Total:<b>{{$pileConfigure->total_pile_bar_price + $pileConfigure->total_pile_cement_bag_price
                                                                + $pileConfigure->total_pile_sands_price + $pileConfigure->total_pile_s_sands_price+
                                                                $pileConfigure->total_pile_picked_price}}
                                                            </b></span><br>
                                                        @else
                                                            <span>Sub Total:<b>{{$pileConfigure->total_pile_bar_price +                 $pileConfigure->total_pile_rmc_price}}
                                                            </b></span><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>Total Cost:</th>
                                                <td> <span>Pile Total:<b> {{number_format($totalPileCost + $totalBricksChips + $totalRmc,2)}}</b></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($beamConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Beam Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Beam No</th>
                                                <th class="text-center">Beam Costing Item</th>
                                            </tr>
                                            @php
                                                $totalBeamCost = 0;
                                                $totalBricksChips = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($beamConfigures as $beamConfigure)
                                            
                                                @php
                                                    if($beamConfigure->course_aggregate_type == 1){
                                                        $totalBeamCost += $beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                     + $beamConfigure->total_beam_sands_price + $beamConfigure->total_beam_s_sands_price + $beamConfigure->total_beam_aggregate_price;
                                                    }elseif ($beamConfigure->course_aggregate_type == 2) {
                                                        $totalBricksChips += $beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                     + $beamConfigure->total_beam_sands_price + $beamConfigure->total_beam_s_sands_price +
                                                                     $beamConfigure->total_beam_picked_price;
                                                    }else {
                                                        $totalRmc += $beamConfigure->total_beam_bar_price +
                                                                     $beamConfigure->total_beam_rmc_price;
                                                    }
                                                    $grandTotal += $beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                     + $beamConfigure->total_beam_sands_price +$beamConfigure->total_beam_s_sands_price+ $beamConfigure->total_beam_aggregate_price +
                                                                     $beamConfigure->total_beam_picked_price+
                                                                     $beamConfigure->total_beam_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>
                                                        Beam Configure No:-{{$beamConfigure->beam_configure_no}} <br>
                                                        Beam Floor Name:-{{$beamConfigure->estimateFloor->name ?? ''}} <br>
                                                        Beam Type:-{{$beamConfigure->beamType->name ?? ''}} <br>
                                                    </th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($beamConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$beamConfigure->total_beam_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$beamConfigure->total_beam_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$beamConfigure->total_beam_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$beamConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$beamConfigure->total_beam_aggregate_price}}</b></span><br>
                                                        @elseif ($beamConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$beamConfigure->total_beam_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$beamConfigure->total_beam_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$beamConfigure->total_beam_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$beamConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$beamConfigure->total_beam_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$beamConfigure->total_beam_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$beamConfigure->total_beam_rmc_price}}</b></span>
                                                        @endif
                                                        <hr>
                                                        @if ($beamConfigure->course_aggregate_type == 1)
                                                            <span>Sub Total:<b>{{$beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                + $beamConfigure->total_beam_sands_price +$beamConfigure->total_beam_s_sands_price+ $beamConfigure->total_beam_aggregate_price}}
                                                            </b></span><br>
                                                        @elseif ($beamConfigure->course_aggregate_type == 2)
                                                            <span>Sub Total:<b>{{$beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                + $beamConfigure->total_beam_sands_price +$beamConfigure->total_beam_s_sands_price +
                                                                $beamConfigure->total_beam_picked_price}}
                                                            </b></span><br>
                                                        @else
                                                            <span>Sub Total RMC:<b>{{$beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_rmc_price}}
                                                            </b></span><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>Total Cost:</th>
                                                <td> <span>Beam Total:<b> {{number_format($totalBeamCost + $totalBricksChips + $totalRmc,2)}}</b></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($gradeBeamConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Grade Beam Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Grade Beam No</th>
                                                <th class="text-center">Grade Beam Costing Item</th>
                                            </tr>
                                            @php
                                                $totalBeamCost = 0;
                                                $totalBricksChips = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($gradeBeamConfigures as $beamConfigure)
                                                @php
                                                    if($beamConfigure->course_aggregate_type == 1){
                                                        $totalBeamCost += $beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                     + $beamConfigure->total_beam_sands_price + $beamConfigure->total_beam_s_sands_price + $beamConfigure->total_beam_aggregate_price;
                                                    }elseif ($beamConfigure->course_aggregate_type == 2) {
                                                        $totalBricksChips += $beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                     + $beamConfigure->total_beam_sands_price + $beamConfigure->total_beam_s_sands_price +
                                                                     $beamConfigure->total_beam_picked_price;
                                                    }else {
                                                        $totalRmc += $beamConfigure->total_beam_bar_price +
                                                                     $beamConfigure->total_grade_beam_rmc_price;
                                                    }

                                                    $grandTotal += $beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                     + $beamConfigure->total_beam_sands_price +$beamConfigure->total_beam_s_sands_price+ $beamConfigure->total_beam_aggregate_price +
                                                                     $beamConfigure->total_beam_picked_price+
                                                                     $beamConfigure->total_grade_beam_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>Grade Beam Configure No-{{$beamConfigure->beam_configure_no}} <br>
                                                        Grade Beam Floor Name:-{{$beamConfigure->estimateFloor->name ?? ''}} <br>
                                                        Grade Beam Type:-{{$beamConfigure->gradeBeamType->name ?? ''}} <br>
                                                    </th>
                                                    

                                                    <td class="text-right">
                                                        @if ($beamConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$beamConfigure->total_beam_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$beamConfigure->total_beam_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$beamConfigure->total_beam_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$beamConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$beamConfigure->total_beam_aggregate_price}}</b></span><br>
                                                        @elseif ($beamConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$beamConfigure->total_beam_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$beamConfigure->total_beam_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$beamConfigure->total_beam_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$beamConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$beamConfigure->total_beam_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$beamConfigure->total_beam_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$beamConfigure->total_grade_beam_rmc_price}}</b></span>
                                                        @endif
                                                        <hr>
                                                        @if ($beamConfigure->course_aggregate_type == 1)
                                                            <span>Sub Total:<b>{{$beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                + $beamConfigure->total_beam_sands_price +$beamConfigure->total_beam_s_sands_price+ $beamConfigure->total_beam_aggregate_price}}
                                                            </b></span><br>
                                                        @elseif ($beamConfigure->course_aggregate_type == 2)
                                                            <span>Sub Total:<b>{{$beamConfigure->total_beam_bar_price + $beamConfigure->total_beam_cement_bag_price
                                                                + $beamConfigure->total_beam_sands_price +$beamConfigure->total_beam_s_sands_price +
                                                                $beamConfigure->total_beam_picked_price}}
                                                            </b></span><br>
                                                        @else
                                                            <span>Sub Total RMC:<b>{{$beamConfigure->total_beam_bar_price + $beamConfigure->total_grade_beam_rmc_price}}
                                                            </b></span><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>Total Cost:</th>
                                                <td> <span>Grade Beam Total:<b> {{number_format($totalBeamCost + $totalBricksChips + $totalRmc,2)}}</b></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($columnConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Column Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Column No</th>
                                                <th class="text-center">Column Costing Item</th>
                                            </tr>
                                            @php
                                                $totalColumnCost = 0;
                                                $totalBricksChips = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($columnConfigures as $columnConfigure)
                                                @php
                                                    if($columnConfigure->course_aggregate_type == 1){
                                                        $totalColumnCost += $columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                         + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_s_sands_price+$columnConfigure->total_column_aggregate_price;
                                                    }elseif ($columnConfigure->course_aggregate_type == 2) {
                                                        $totalBricksChips += $columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                         + $columnConfigure->total_column_sands_price +$columnConfigure->total_column_s_sands_price+
                                                                         $columnConfigure->total_column_picked_price;
                                                    }else {
                                                        $totalRmc += $columnConfigure->total_column_bar_price + $columnConfigure->total_column_rmc_price;
                                                    }
                                                    $grandTotal += $columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                         + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_aggregate_price +
                                                                         $columnConfigure->total_column_picked_price+$columnConfigure->total_column_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>Column Configure No-{{$columnConfigure->column_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_column_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_column_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_column_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_column_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$columnConfigure->total_column_aggregate_price}}</b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_column_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_column_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_column_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_column_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$columnConfigure->total_column_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_column_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$columnConfigure->total_column_rmc_price}}</b></span><br>
                                                        @endif
                                                        <hr>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Sub Total:<b>{{$columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_aggregate_price +
                                                                $columnConfigure->total_column_s_sands_price}}
                                                            </b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Sub Total:<b>{{$columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_s_sands_price +
                                                                $columnConfigure->total_column_picked_price}}
                                                            </b></span><br>
                                                        @else
                                                            <span>Sub Total:<b>{{$columnConfigure->total_column_bar_price + $columnConfigure->total_column_rmc_price}}
                                                            </b></span><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>Total Cost:</th>
                                                <td> <span>Column Total:<b> {{number_format($totalColumnCost + $totalBricksChips + $totalRmc,2)}}</b></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($shortColumnConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Short Column Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Short Column No</th>
                                                <th class="text-center">Short Column Costing Item</th>
                                            </tr>
                                            @php
                                                $totalColumnCost = 0;
                                                $totalBricksChips = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($shortColumnConfigures as $columnConfigure)
                                                @php
                                                     if($columnConfigure->course_aggregate_type == 1){
                                                        $totalColumnCost += $columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                         + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_s_sands_price+$columnConfigure->total_column_aggregate_price;
                                                    }elseif ($columnConfigure->course_aggregate_type == 2) {
                                                        $totalBricksChips += $columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                         + $columnConfigure->total_column_sands_price +$columnConfigure->total_column_s_sands_price+
                                                                         $columnConfigure->total_column_picked_price;
                                                    }else {
                                                        $totalRmc += $columnConfigure->total_column_bar_price + $columnConfigure->total_short_column_rmc_price;
                                                    }
                                                    $grandTotal += $columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                         + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_aggregate_price +
                                                                         $columnConfigure->total_column_picked_price;
                                                @endphp
                                                <tr>
                                                    <th>Column Configure No-{{$columnConfigure->column_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_column_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_column_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_column_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_column_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$columnConfigure->total_column_aggregate_price}}</b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_column_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_column_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_column_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_column_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$columnConfigure->total_column_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_column_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$columnConfigure->total_short_column_rmc_price}}</b></span><br>
                                                        @endif
                                                        <hr>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Sub Total:<b>{{$columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_aggregate_price +
                                                                $columnConfigure->total_column_s_sands_price}}
                                                            </b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Sub Total:<b>{{$columnConfigure->total_column_bar_price + $columnConfigure->total_column_cement_bag_price
                                                                + $columnConfigure->total_column_sands_price + $columnConfigure->total_column_s_sands_price +
                                                                $columnConfigure->total_column_picked_price}}
                                                            </b></span><br>
                                                        @else
                                                            <span>Sub Total:<b>{{$columnConfigure->total_column_bar_price + $columnConfigure->total_short_column_rmc_price}}
                                                            </b></span><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>Total Cost:</th>
                                                <td> <span>Column Total:<b> {{number_format($totalColumnCost + $totalBricksChips + $totalRmc,2)}}</b></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($footingConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Footing Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Footing No</th>
                                                <th class="text-center">Footing Costing Item</th>
                                            </tr>
                                            @php
                                                $totalColumnCost = 0;
                                                $totalChip = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($footingConfigures as $columnConfigure)
                                                @php
                                                    if ($columnConfigure->course_aggregate_type == 1) {
                                                        $totalColumnCost += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                    + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price
                                                                    ;
                                                    }elseif ($columnConfigure->course_aggregate_type == 2) {
                                                        $totalChip += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                    + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+
                                                                    $columnConfigure->total_common_picked_price;
                                                    }else {
                                                        $totalRmc += $columnConfigure->total_common_bar_price +$columnConfigure->total_pile_cap_rmc_price ;

                                                    }
                                                    $grandTotal += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                    + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price +
                                                                    $columnConfigure->total_common_picked_price+$columnConfigure->total_pile_cap_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>Footing Configure No-{{$columnConfigure->common_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$columnConfigure->total_common_aggregate_price}}</b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$columnConfigure->total_common_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$columnConfigure->total_footing_rmc_price}}</b></span>
                                                        @endif


                                                        <hr>
                                                    @if ($columnConfigure->course_aggregate_type == 1)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                    @elseif ($columnConfigure->course_aggregate_type == 2)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                    @else
                                                        <span>Total RMC Cost:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_pile_cap_rmc_price}}
                                                            </b></span><br>
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <th>Total Cost:</th>
                                                    <td> <span>Footing Total:<b> {{number_format($totalColumnCost+$totalChip+$totalRmc,2)}}</b></span></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($commonConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Slab Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Slab No</th>
                                                <th class="text-center">Slab Costing Item</th>
                                            </tr>
                                            @php
                                                $totalCommonCost = 0;
                                                $totalBricksChips = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($commonConfigures as $commonConfigure)
                                                @php
                                                   if($commonConfigure->course_aggregate_type == 1){
                                                    $totalBricksChips += $commonConfigure->total_common_bar_price + $commonConfigure->total_common_cement_bag_price
                                                                         + $commonConfigure->total_common_sands_price + $commonConfigure->total_common_aggregate_price +
                                                                         $commonConfigure->total_common_picked_price;
                                                   }elseif ($commonConfigure->course_aggregate_type == 2) {
                                                    $totalCommonCost += $commonConfigure->total_common_bar_price + $commonConfigure->total_common_cement_bag_price
                                                                         + $commonConfigure->total_common_sands_price + $commonConfigure->total_common_aggregate_price +
                                                                         $commonConfigure->total_common_picked_price;
                                                   }else {
                                                    $totalRmc += $commonConfigure->total_common_bar_price + $commonConfigure->total_common_cement_bag_price
                                                                         + $commonConfigure->total_common_sands_price + $commonConfigure->total_common_aggregate_price +
                                                                         $commonConfigure->total_common_picked_price;
                                                   }
                                                    $grandTotal += $commonConfigure->total_common_bar_price + $commonConfigure->total_common_cement_bag_price
                                                                         + $commonConfigure->total_common_sands_price + $commonConfigure->total_common_aggregate_price +
                                                                         $commonConfigure->total_common_picked_price+
                                                                         $commonConfigure->total_slab_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>{{$commonConfigure->costingSegment->name}} Configure No-{{$commonConfigure->common_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($commonConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$commonConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$commonConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$commonConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$commonConfigure->total_slab_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$commonConfigure->total_common_aggregate_price}}</b></span><br>
                                                        @elseif ($commonConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$commonConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$commonConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$commonConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$commonConfigure->total_slab_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$commonConfigure->total_common_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$commonConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$commonConfigure->total_slab_rmc_price}}</b></span><br>

                                                        @endif
                                                        <hr>
                                                        @if ($commonConfigure->course_aggregate_type == 1)
                                                            <span>Sub Total:<b>{{$commonConfigure->total_common_bar_price + $commonConfigure->total_common_cement_bag_price
                                                                + $commonConfigure->total_common_sands_price + $commonConfigure->total_common_aggregate_price +
                                                                $commonConfigure->total_slab_s_sands_price}}
                                                            </b></span><br>
                                                        @elseif ($commonConfigure->course_aggregate_type == 2)
                                                            <span>Sub Total:<b>{{$commonConfigure->total_common_bar_price + $commonConfigure->total_common_cement_bag_price
                                                                + $commonConfigure->total_common_sands_price + $commonConfigure->total_slab_s_sands_price +
                                                                $commonConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                        @else
                                                            <span>Sub Total:<b>{{$commonConfigure->total_common_bar_price + $commonConfigure->total_slab_rmc_price}}
                                                            </b></span><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <th>Total Cost:</th>
                                                    <td> <span>Slab Total:<b> {{number_format($totalCommonCost + $totalBricksChips + $totalRmc,2)}}</b></span></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($pileCapConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Pile Cap Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Pile Cap No</th>
                                                <th class="text-center">Pile Cap Costing Item</th>
                                            </tr>
                                            @php
                                                $totalColumnCost = 0;
                                                $totalChip = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($pileCapConfigures as $columnConfigure)
                                                @php
                                                    if ($columnConfigure->course_aggregate_type == 1) {
                                                        $totalColumnCost += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price
                                                                     ;
                                                    }elseif ($columnConfigure->course_aggregate_type == 2) {
                                                        $totalChip += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+
                                                                     $columnConfigure->total_common_picked_price;
                                                    }else {
                                                        $totalRmc += $columnConfigure->total_common_bar_price +$columnConfigure->total_pile_cap_rmc_price ;

                                                    }
                                                    $grandTotal += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price +
                                                                     $columnConfigure->total_common_picked_price+$columnConfigure->total_pile_cap_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>Pile Cap Configure No-{{$columnConfigure->common_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$columnConfigure->total_common_aggregate_price}}</b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_beam_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$columnConfigure->total_common_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$columnConfigure->total_pile_cap_rmc_price}}</b></span>
                                                        @endif


                                                        <hr>
                                                       @if ($columnConfigure->course_aggregate_type == 1)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                       @elseif ($columnConfigure->course_aggregate_type == 2)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                       @else
                                                        <span>Total RMC Cost:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_pile_cap_rmc_price}}
                                                            </b></span><br>
                                                       @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <th>Total Cost:</th>
                                                    <td> <span>Pile Cap Total:<b> {{number_format($totalColumnCost+$totalChip+$totalRmc,2)}}</b></span></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($matConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Mat Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Mat No</th>
                                                <th class="text-center">Mat Costing Item</th>
                                            </tr>
                                            @php
                                                $totalColumnCost = 0;
                                                $totalChip = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($matConfigures as $columnConfigure)
                                                @php
                                                    if ($columnConfigure->course_aggregate_type == 1) {
                                                        $totalColumnCost += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price
                                                                     ;
                                                    }elseif ($columnConfigure->course_aggregate_type == 2) {
                                                        $totalChip += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+
                                                                     $columnConfigure->total_common_picked_price;
                                                    }else {
                                                        $totalRmc += $columnConfigure->total_common_bar_price +$columnConfigure->total_mat_rmc_price ;

                                                    }
                                                    $grandTotal += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price +
                                                                     $columnConfigure->total_common_picked_price+$columnConfigure->total_mat_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>Mat Configure No-{{$columnConfigure->common_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_slab_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$columnConfigure->total_common_aggregate_price}}</b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_slab_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$columnConfigure->total_common_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$columnConfigure->total_mat_rmc_price}}</b></span>
                                                        @endif


                                                        <hr>
                                                       @if ($columnConfigure->course_aggregate_type == 1)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                       @elseif ($columnConfigure->course_aggregate_type == 2)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                       @else
                                                        <span>Total RMC Cost:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_mat_rmc_price}}
                                                            </b></span><br>
                                                       @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <th>Total Cost:</th>
                                                    <td> <span>Mat Total:<b> {{number_format($totalColumnCost+$totalChip+$totalRmc,2)}}</b></span></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($returningConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Returning Wall Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Returning Wall No</th>
                                                <th class="text-center">Returning Wall Costing Item</th>
                                            </tr>
                                            @php
                                                $totalColumnCost = 0;
                                                $totalChip = 0;
                                                $totalRmc = 0;
                                            @endphp
                                            @foreach($returningConfigures as $columnConfigure)
                                                @php
                                                    if ($columnConfigure->course_aggregate_type == 1) {
                                                        $totalColumnCost += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price
                                                                     ;
                                                    }elseif ($columnConfigure->course_aggregate_type == 2) {
                                                        $totalChip += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+
                                                                     $columnConfigure->total_common_picked_price;
                                                    }else {
                                                        $totalRmc += $columnConfigure->total_common_bar_price +$columnConfigure->total_mat_rmc_price ;

                                                    }
                                                    $grandTotal += $columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                                     + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price +
                                                                     $columnConfigure->total_common_picked_price+$columnConfigure->total_mat_rmc_price;
                                                @endphp
                                                <tr>
                                                    <th>Returning Wall Configure No-{{$columnConfigure->common_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @if ($columnConfigure->course_aggregate_type == 1)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_slab_s_sands_price}}</b></span><br>
                                                            <span>Aggregate Cost: <b>{{$columnConfigure->total_common_aggregate_price}}</b></span><br>
                                                        @elseif ($columnConfigure->course_aggregate_type == 2)
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>Cement Cost: <b>{{$columnConfigure->total_common_cement_bag_price}}</b></span><br>
                                                            <span>Local Sands Cost: <b>{{$columnConfigure->total_common_sands_price}}</b></span><br>
                                                            <span>Shylet Sands Cost: <b>{{$columnConfigure->total_slab_s_sands_price}}</b></span><br>
                                                            <span>Picked Cost: <b>{{$columnConfigure->total_common_picked_price}}</b></span><br>
                                                        @else
                                                            <span>Bar(Rod) Cost: <b>{{$columnConfigure->total_common_bar_price}}</b></span><br>
                                                            <span>RMC(Cft) Cost: <b>{{$columnConfigure->total_mat_rmc_price}}</b></span>
                                                        @endif


                                                        <hr>
                                                       @if ($columnConfigure->course_aggregate_type == 1)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price+$columnConfigure->total_common_aggregate_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                       @elseif ($columnConfigure->course_aggregate_type == 2)
                                                        <span>Sub Total:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_common_cement_bag_price
                                                            + $columnConfigure->total_common_sands_price + $columnConfigure->total_beam_s_sands_price +
                                                            $columnConfigure->total_common_picked_price}}
                                                            </b></span><br>
                                                       @else
                                                        <span>Total RMC Cost:<b>{{$columnConfigure->total_common_bar_price + $columnConfigure->total_mat_rmc_price}}
                                                            </b></span><br>
                                                       @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <th>Total Cost:</th>
                                                    <td> <span>Returning Wall Total:<b> {{number_format($totalColumnCost+$totalChip+$totalRmc,2)}}</b></span></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($bricksConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Bricks Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Bricks No</th>
                                                <th class="text-center">Bricks Costing Item</th>
                                            </tr>
                                            @php
                                                $totalBricksCost = 0;
                                            @endphp
                                            @foreach($bricksConfigures as $bricksConfigure)
                                                @php
                                                    $totalBricksCost += $bricksConfigure->total_bricks_cost + $bricksConfigure->total_bricks_cement_cost
                                                                         + $bricksConfigure->total_bricks_sands_cost;
                                                    $grandTotal += $bricksConfigure->total_bricks_cost + $bricksConfigure->total_bricks_cement_cost
                                                                         + $bricksConfigure->total_bricks_sands_cost;
                                                @endphp
                                                <tr>
                                                    <th>Bricks Configure No-{{$bricksConfigure->bricks_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        <span>Bricks Cost: <b>{{$bricksConfigure->total_bricks_cost}}</b></span><br>
                                                        <span>Bricks Cement Cost: <b>{{$bricksConfigure->total_bricks_cement_cost}}</b></span><br>
                                                        <span>Bricks Sands Cost: <b>{{$bricksConfigure->total_bricks_sands_cost}}</b></span><br>
                                                        <hr>
                                                        <span>Total:<b>{{$bricksConfigure->total_bricks_cost + $bricksConfigure->total_bricks_cement_cost
                                                                         + $bricksConfigure->total_bricks_sands_cost}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span>Bricks Total Cost:<b> {{number_format($totalBricksCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($plasterConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Plaster Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Plaster No</th>
                                                <th class="text-center">Plaster Costing Item</th>
                                            </tr>
                                            @php
                                                $totalPlasterCost = 0;
                                            @endphp
                                            @foreach($plasterConfigures as $plasterConfigure)
                                                @php
                                                    $totalPlasterCost += $plasterConfigure->total_plaster_cement_cost + $plasterConfigure->total_plaster_sands_cost;
                                                    $grandTotal += $plasterConfigure->total_plaster_cement_cost + $plasterConfigure->total_plaster_sands_cost;
                                                @endphp
                                                <tr>
                                                    <th>Plaster Configure No-{{$plasterConfigure->plaster_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        <span>Plaster Cement Cost: <b>{{$plasterConfigure->total_plaster_cement_cost}}</b></span><br>
                                                        <span>Plaster Sands Cost: <b>{{$plasterConfigure->total_plaster_sands_cost}}</b></span><br>
                                                        <hr>
                                                        <span>Total:<b>{{$plasterConfigure->total_plaster_cement_cost + $plasterConfigure->total_plaster_sands_cost}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span>Plaster Total Cost:<b> {{number_format($totalPlasterCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($grillGlassTilesConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Grill Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center"> No</th>
                                                <th class="text-center">Grill Item</th>
                                            </tr>
                                            @php
                                                $totalGrillGlassTilesCost = 0;
                                            @endphp
                                            @foreach($grillGlassTilesConfigures as $grillGlassTilesConfigure)
                                                @php
                                                    $totalGrillGlassTilesCost += $grillGlassTilesConfigure->total_tiles_glass_cost;
                                                    $grandTotal += $grillGlassTilesConfigure->total_tiles_glass_cost;

                                                @endphp
                                                <tr>
                                                    <th>  Grill

                                                        Configure No-{{$grillGlassTilesConfigure->grill_glass_tiles_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                            Grill Cost: <b>{{$grillGlassTilesConfigure->total_tiles_glass_cost}}</b></span><br>

                                                        <hr>
                                                            <span>Total:<b>{{$grillGlassTilesConfigure->total_tiles_glass_cost}}
                                                                </b></span><br>

                                                        <hr>
                                                        <span>Total Cost:<b> {{number_format($totalGrillGlassTilesCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($glassConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Glass Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center"> No</th>
                                                <th class="text-center">Glass Item</th>
                                            </tr>
                                            @php
                                                $totalGrillGlassTilesCost = 0;
                                            @endphp
                                            @foreach($glassConfigures as $grillGlassTilesConfigure)
                                                @php
                                                    $totalGrillGlassTilesCost += $grillGlassTilesConfigure->total_grill_cost + $grillGlassTilesConfigure->total_thai_cost;
                                                    $grandTotal += $grillGlassTilesConfigure->total_grill_cost + $grillGlassTilesConfigure->total_thai_cost;

                                                @endphp
                                                <tr>
                                                    <th>  Glass

                                                        Configure No-{{$grillGlassTilesConfigure->grill_glass_tiles_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                            Glass Cost: <b>{{$grillGlassTilesConfigure->total_grill_cost}}</b></span><br>
                                                        <hr>
                                                            Thai Cost: <b>{{$grillGlassTilesConfigure->total_thai_cost}}</b></span><br>

                                                        <hr>
                                                            <span>Total:<b>{{$grillGlassTilesConfigure->total_grill_cost + $grillGlassTilesConfigure->total_thai_cost}}
                                                                </b></span><br>

                                                        <hr>
                                                        <span>Total Cost:<b> {{number_format($totalGrillGlassTilesCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($tilesConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Tiles Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center"> No</th>
                                                <th class="text-center">Tiles Item</th>
                                            </tr>
                                            @php
                                                $totalGrillGlassTilesCost = 0;
                                            @endphp
                                            @foreach($tilesConfigures as $grillGlassTilesConfigure)
                                                @php
                                                    $totalGrillGlassTilesCost += $grillGlassTilesConfigure->total_tiles_glass_cost;
                                                    $grandTotal += $grillGlassTilesConfigure->total_tiles_glass_cost;

                                                @endphp
                                                <tr>
                                                    <th>  Tiles

                                                        Configure No-{{$grillGlassTilesConfigure->grill_glass_tiles_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                            Tiles Cost: <b>{{$grillGlassTilesConfigure->total_tiles_glass_cost}}</b></span><br>
                                                        <hr>
                                                            <span>Total:<b>{{$grillGlassTilesConfigure->total_tiles_glass_cost}}
                                                                </b></span><br>

                                                        <hr>
                                                        <span>Total Cost:<b> {{number_format($totalGrillGlassTilesCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($paintConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Paint Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Paint No</th>
                                                <th class="text-center">Paint Costing Item</th>
                                            </tr>
                                            @php
                                                $totalPaintCost = 0;
                                            @endphp
                                            @foreach($paintConfigures as $paintConfigure)
                                                @php
                                                    $totalPaintCost += $paintConfigure->total_paint_cost + $paintConfigure->total_seller_cost;
                                                    $grandTotal += $paintConfigure->total_paint_cost + $paintConfigure->total_seller_cost;
                                                @endphp
                                                <tr>
                                                    <th>Paint Configure No-{{$paintConfigure->paint_configure_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        <span>Paint Cost: <b>{{$paintConfigure->total_paint_cost}}</b></span><br>
                                                        <span>Seller Cost: <b>{{$paintConfigure->total_seller_cost}}</b></span><br>
                                                        <hr>
                                                        <span>Total:<b>{{$paintConfigure->total_paint_cost + $paintConfigure->total_seller_cost}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span>Paint Total Cost:<b> {{number_format($totalPaintCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($earthWorkConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Earth Work Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Earth Work No</th>
                                                <th class="text-center">Earth Work Amount</th>
                                            </tr>
                                            @php
                                                $totalEarthWorkCost = 0;
                                            @endphp
                                            @foreach($earthWorkConfigures as $earthWorkConfigure)
                                                @php
                                                    $totalEarthWorkCost += $earthWorkConfigure->total_price;
                                                    $grandTotal += $earthWorkConfigure->total_price;
                                                @endphp
                                                <tr>
                                                    @if ($earthWorkConfigure->earth_work_type == 1)
                                                        <th>Earth Work Configure For Cutting</th>
                                                    @else
                                                        <th>Earth Work Configure For Filling</th>
                                                    @endif

                                                    <td class="text-right">
                                                        <br>
                                                        <span>Earth Work Cost: <b>{{$earthWorkConfigure->total_price}}</b></span><br>
                                                        <hr>
                                                        <span>Total:<b>{{$earthWorkConfigure->total_price}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span>Earth Work Total Cost:<b> {{number_format($totalEarthWorkCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($sandFillings)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Sand Filling Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Sand Filling No</th>
                                                <th class="text-center">Sand Filling Amount</th>
                                            </tr>
                                            @php
                                                $totalEarthWorkCost = 0;
                                            @endphp
                                            @foreach($sandFillings as $sandFilling)
                                                @php
                                                    $totalEarthWorkCost += $sandFilling->total_price;
                                                    $grandTotal += $sandFilling->total_price;
                                                @endphp
                                                <tr>

                                                        <th>Sand Filling No: 0{{ $sandFilling->id }}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        <span>Sand Filling Cost: <b>{{$sandFilling->total_price}}</b></span><br>
                                                        <hr>
                                                        <span>Total:<b>{{$sandFilling->total_price}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span>Earth Work Total Cost:<b> {{number_format($totalEarthWorkCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($bricksSolings)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Bricks Soling Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Bricks Soling No</th>
                                                <th class="text-center">Bricks Soling Amount</th>
                                            </tr>
                                            @php
                                                $totalEarthWorkCost = 0;
                                            @endphp
                                            @foreach($bricksSolings as $bricksSoling)
                                                @php
                                                    $totalEarthWorkCost += $bricksSoling->total_price;
                                                    $grandTotal += $bricksSoling->total_price;
                                                @endphp
                                                <tr>

                                                        <th>Bricks Soling No: 0{{ $bricksSoling->id }}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        <span>Sand Filling Cost: <b>{{$bricksSoling->total_price}}</b></span><br>
                                                        <hr>
                                                        <span>Total:<b>{{$bricksSoling->total_price}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span> Total Cost:<b> {{number_format($totalEarthWorkCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($extraCostingConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Extra Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Extra Costing No</th>
                                                <th class="text-center">Extra Costing Item</th>
                                            </tr>
                                            @php
                                                $totalExtraCost = 0;
                                            @endphp
                                            @foreach($extraCostingConfigures as $extraCostingConfigure)
                                                @php
                                                    $totalExtraCost += $extraCostingConfigure->total;
                                                    $grandTotal += $extraCostingConfigure->total;
                                                @endphp
                                                <tr>
                                                    <th>Extra Costing Configure No-{{$extraCostingConfigure->costing_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @foreach($extraCostingConfigure->products as $product)
                                                            <span>{{$product->name}} Cost: <b>{{$product->total}}</b></span><br>
                                                        @endforeach
                                                        <hr>
                                                        <span>Total:<b>{{$extraCostingConfigure->total}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span>Extra Total Cost:<b> {{number_format($totalExtraCost,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($mobilizationWorkConfigures)
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Mobilization Work</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Mobilization Work No</th>
                                                <th class="text-center">Mobilization Work Item</th>
                                            </tr>
                                            @php
                                                $totalMobilizationWork = 0;
                                            @endphp
                                            @foreach($mobilizationWorkConfigures as $mobilizationWorkConfigure)
                                                @php
                                                    $totalMobilizationWork += $mobilizationWorkConfigure->total;
                                                    $grandTotal += $mobilizationWorkConfigure->total;
                                                @endphp
                                                <tr>
                                                    <th>Mobilization Work Configure No-{{$mobilizationWorkConfigure->costing_no}}</th>

                                                    <td class="text-right">
                                                        <br>
                                                        @foreach($mobilizationWorkConfigure->products as $product)
                                                            <span>{{$product->product->name}} Cost: <b>{{$product->amount}}</b></span><br>
                                                        @endforeach
                                                        <hr>
                                                        <span>Total:<b>{{$mobilizationWorkConfigure->total}}
                                                        </b></span><br>
                                                        <hr>
                                                        <span>Mobilization Total Cost:<b> {{number_format($totalMobilizationWork,2)}}</b></span><br>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-offset-2 col-md-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-center">{{$projectName->name}} Total Cost</th>
                                            <th class="text-center">৳ {{number_format($grandTotal,2)}} Taka</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

        </div>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(function () {
            $('.select2').select2();
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            var segmentSelected = '{{ request()->get('segment') }}';

            $('#project').change(function () {
                var projectId = $(this).val();

                $('#segment').html('<option value="">Select segment</option>');

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_estimate_project_segment') }}",
                        data: { projectId: projectId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (segmentSelected == item.id)
                                $('#segment').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#segment').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });

            $('#project').trigger('change');
        });

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection

