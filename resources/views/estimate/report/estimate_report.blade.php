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
    Estimate Report
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
                    <form action="{{ route('estimate_report') }}">
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

    <div class="row">
        <div class="col-sm-12" style="min-height:300px">
            @if($pileConfigures)
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
                                    <h4><strong>Estimate And Costing Report</strong></h4>
                                </div>
                            </div>
                            <hr>
                            @if($pileConfigures)
                                @foreach($pileConfigures as $pileConfigure)

                                <div class="row">
                                    <div style="padding:5px; width:100%; text-align:center;">
                                        <h3><strong>Pile</strong></h3>
                                        <h4><u><i><strong>Pile Configure No: {{$pileConfigure->pile_configure_no}}</strong></i></u></h4>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Pile Configure No.</th>
                                                <td>{{ $pileConfigure->pile_configure_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pile Configure Date</th>
                                                <td>{{ $pileConfigure->date }}</td>
                                            </tr>
                                            <tr>
                                                <th>Estimate Project</th>
                                                <td>{{ $pileConfigure->project->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Ton</th>
                                                <td>{{ $pileConfigure->total_ton }} Rod</td>
                                            </tr>
                                            <tr>
                                                <th>Total Kg</th>
                                                <td>{{ $pileConfigure->total_kg }} Rod</td>
                                            </tr>
                                            <tr>
                                                <th>Total Cement</th>
                                                <td>{{ $pileConfigure->total_cement }} Cft</td>
                                            </tr>
                                            <tr>
                                                <th>Total Cement</th>
                                                <td>{{ $pileConfigure->total_cement_bag }} Bag</td>
                                            </tr>
                                            <tr>
                                                <th>Total Sands</th>
                                                <td>{{ $pileConfigure->total_sands }} Cft</td>
                                            </tr>
                                            <tr>
                                                <th>Total Aggregate</th>
                                                <td>{{ $pileConfigure->total_aggregate }} Cft</td>
                                            </tr>

                                            <tr>
                                                <th>Total Piked</th>
                                                <td>{{ $pileConfigure->total_picked }} Pcs</td>
                                            </tr>
                                            <tr>
                                                <th>Note </th>
                                                <td>{{ $pileConfigure->note??'' }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center">Pilling Info</th>
                                            </tr>
                                            <tr>
                                                <th>Spiral Bar</th>
                                                <td>{{ $pileConfigure->spiral_bar }}</td>
                                            </tr>
                                            <tr>
                                                <th>Spiral Interval</th>
                                                <td>{{ $pileConfigure->spiral_interval }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ratio</th>
                                                <td>{{ $pileConfigure->first_ratio }}:{{ $pileConfigure->second_ratio }}:{{ $pileConfigure->third_ratio }}</td>
                                            </tr>
                                            <tr>
                                                <th>Height</th>
                                                <td>{{ $pileConfigure->pile_height }}</td>
                                            </tr>
                                            <tr>
                                                <th>Radius</th>
                                                <td>{{ $pileConfigure->radius }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pile Quantity</th>
                                                <td>{{ $pileConfigure->pile_quantity }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Volume</th>
                                                <td>{{ $pileConfigure->total_volume }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Dry Volume</th>
                                                <td>{{ $pileConfigure->total_dry_volume }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Bar</th>
                                                <th>Dia</th>
                                                <th>Dia(D^2)</th>
                                                <th>Value of Bar</th>
                                                <th>Kg/Rft</th>
                                                <th>Kg/Ton</th>
                                                <th>Rft/Ton</th>
                                                <th>Sub Total Kg</th>
                                                <th>Sub Total Ton</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <h4>Main Bar Description</h4>
                                            @foreach($pileConfigure->pileConfigureProducts as $product)
                                                @if ($product->bar_type != null)
                                                    <tr>
                                                        <td>
                                                            @if($product->bar_type == 6)
                                                                6mm
                                                            @elseif($product->bar_type == 8)
                                                                8mm
                                                            @elseif($product->bar_type == 10)
                                                                10mm
                                                            @elseif($product->bar_type == 12)
                                                                12mm
                                                            @elseif($product->bar_type == 16)
                                                                16mm
                                                            @elseif($product->bar_type == 18)
                                                                18mm
                                                            @elseif($product->bar_type == 20)
                                                                20mm
                                                            @elseif($product->bar_type == 22)
                                                                22mm
                                                            @elseif($product->bar_type == 25)
                                                                25mm
                                                            @elseif($product->bar_type == 28)
                                                                28mm
                                                            @elseif($product->bar_type == 32)
                                                                32mm
                                                            @elseif($product->bar_type == 36)
                                                                36mm
                                                            @endif

                                                        </td>
                                                        <td>{{ $product->dia }}</td>
                                                        <td> {{ $product->dia_square }}</td>
                                                        <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                        <td> {{ number_format($product->rft_by_ton, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                            <tr>
                                                <th class="text-right" colspan="7">Total Ton/KG</th>
                                                <td> {{ number_format($pileConfigure->total_kg, 2) }}</td>
                                                <td> {{ number_format($pileConfigure->total_ton, 3) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div> --}}
                                @php
                                $sub_total_kg = 0;
                                $sub_total_ton = 0;
                                @endphp
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Bar</th>
                                                <th>Dia</th>
                                                <th>Dia(D^2)</th>
                                                <th>Value of Bar</th>
                                                <th>Kg/Rft</th>
                                                <th>Kg/Ton</th>
                                                <th>Rft/Ton</th>
                                                <th>Sub Total Kg</th>
                                                <th>Sub Total Ton</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <h4>Main Bar Description</h4>
                                            @foreach($pileConfigure->pileConfigureProducts as $product)
                                                @if ($product->bar_type != null)

                                                    <?php
                                                        $sub_total_kg += $product->sub_total_kg;
                                                        $sub_total_ton += $product->sub_total_ton;
                                                    ?>
                                                    <tr>
                                                        <td>
                                                        @if($product->bar_type == 6)
                                                            6mm
                                                            @elseif($product->bar_type == 8)
                                                            8mm
                                                            @elseif($product->bar_type == 10)
                                                                10mm
                                                            @elseif($product->bar_type == 12)
                                                                12mm
                                                            @elseif($product->bar_type == 16)
                                                                16mm
                                                            @elseif($product->bar_type == 18)
                                                                18mm
                                                            @elseif($product->bar_type == 20)
                                                                20mm
                                                            @elseif($product->bar_type == 22)
                                                                22mm
                                                            @elseif($product->bar_type == 25)
                                                                25mm
                                                            @elseif($product->bar_type == 28)
                                                                28mm
                                                            @elseif($product->bar_type == 32)
                                                                32mm
                                                            @elseif($product->bar_type == 36)
                                                                36mm
                                                            @endif

                                                        </td>
                                                        <td>{{ $product->dia }}</td>
                                                        <td> {{ $product->dia_square }}</td>
                                                        <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                        <td> {{ number_format($product->rft_by_ton, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                            <tr>
                                                <th colspan="7" class="text-right">Sub Total</th>
                                                <th> {{ number_format($sub_total_kg, 2) }}</th>
                                                <th> {{ number_format($sub_total_ton, 3) }}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                @php
                                $tieTotalKg = 0;
                                $tieTotalTon = 0;
                                $sub_total_kg_tie = 0;
                                $sub_total_ton_tie = 0;
                                @endphp
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Bar</th>
                                                <th>Dia</th>
                                                <th>Dia(D^2)</th>
                                                <th>Value of Bar</th>
                                                <th>Kg/Rft</th>
                                                <th>Kg/Ton</th>
                                                <th>Sub Total Kg</th>
                                                <th>Sub Total Ton</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <h4>Tie Bar Description</h4>
                                            @foreach($pileConfigure->pileConfigureProducts as $product)
                                                @if($product->tie_bar_type != null)
                                                    <?php
                                                    $sub_total_kg_tie += $product->sub_total_kg_tie;
                                                    $sub_total_ton_tie += $product->sub_total_ton_tie;
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            @if($product->tie_bar_type == 6)
                                                                6mm
                                                            @elseif($product->tie_bar_type == 8)
                                                                8mm
                                                            @elseif($product->tie_bar_type == 10)
                                                                10mm
                                                            @elseif($product->tie_bar_type == 12)
                                                                12mm
                                                            @elseif($product->tie_bar_type == 16)
                                                                16mm
                                                            @elseif($product->tie_bar_type == 18)
                                                                18mm
                                                            @elseif($product->tie_bar_type == 20)
                                                                20mm
                                                            @elseif($product->tie_bar_type == 22)
                                                                22mm
                                                            @elseif($product->tie_bar_type == 25)
                                                                25mm
                                                            @elseif($product->tie_bar_type == 28)
                                                                28mm
                                                            @elseif($product->tie_bar_type == 32)
                                                                32mm
                                                            @elseif($product->tie_bar_type == 36)
                                                                36mm
                                                            @endif

                                                        </td>
                                                        <td>{{ $product->tie_bar_type }}</td>
                                                        <td> {{ $product->tie_dia_square }}</td>
                                                        <td> {{ number_format($product->tie_value_of_bar, 2) }}</td>
                                                        <td> {{ number_format($product->tie_kg_by_rft, 2) }}</td>
                                                        <td> {{ number_format($product->tie_kg_by_ton, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_kg_tie, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_ton_tie, 3) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>

                                            <tr>
                                                <th colspan="6" class="text-right">Sub Total</th>
                                                <th> {{ number_format($sub_total_kg_tie, 2) }}</th>
                                                <th> {{ number_format($sub_total_ton_tie, 3) }}</th>
                                            </tr>

                                            <tr>
                                                <th colspan="6" class="text-right" >Total Ton/KG</th>
                                                <td> {{ number_format($pileConfigure->total_kg, 2) }}</td>
                                                <td> {{ number_format($pileConfigure->total_ton, 3) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                            @if($beamConfigures)
                                @foreach($beamConfigures as $beamConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Beam</strong></h3>
                                            <h4><u><i><strong>Beam Configure No: {{$beamConfigure->beam_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Beam Configure No.</th>
                                                    <td>{{ $beamConfigure->beam_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Beam Configure Date</th>
                                                    <td>{{ $beamConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $beamConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $beamConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $beamConfigure->total_kg }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $beamConfigure->total_cement }} Bag</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Sands</th>
                                                    <td>{{ $beamConfigure->total_sands }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Aggregate</th>
                                                    <td>{{ $beamConfigure->total_aggregate }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $beamConfigure->total_picked }} Pcs</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $beamConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Beam Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Spiral Bar</th>
                                                    <td>{{ $beamConfigure->tie_bar }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Spiral Interval</th>
                                                    <td>{{ $beamConfigure->tie_interval }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ratio</th>
                                                    <td>{{ $beamConfigure->first_ratio }}:{{ $beamConfigure->second_ratio }}:{{ $beamConfigure->third_ratio }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Height</th>
                                                    <td>{{ $beamConfigure->beam_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Beam Quantity</th>
                                                    <td>{{ $beamConfigure->beam_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $beamConfigure->total_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $beamConfigure->total_dry_volume }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $mainTotalKg = 0;
                                        $mainTotalTon = 0;
                                        $extraTotalKg = 0;
                                        $extraTotalTon = 0;
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Main Bar Description</h4>
                                                @foreach($beamConfigure->beamConfigureProducts as $product)
                                                    @if($product->status == 2)
                                                        <?php
                                                        $mainTotalKg += $product->sub_total_kg;
                                                        $mainTotalTon += $product->sub_total_ton;
                                                        ?>
                                                    <tr>
                                                        <td>
                                                        @if($product->bar_type == 6)
                                                            6mm
                                                            @elseif($product->bar_type == 8)
                                                            8mm
                                                            @elseif($product->bar_type == 10)
                                                                10mm
                                                            @elseif($product->bar_type == 12)
                                                                12mm
                                                            @elseif($product->bar_type == 16)
                                                                16mm
                                                            @elseif($product->bar_type == 18)
                                                                18mm
                                                            @elseif($product->bar_type == 20)
                                                                20mm
                                                            @elseif($product->bar_type == 22)
                                                                22mm
                                                            @elseif($product->bar_type == 25)
                                                                25mm
                                                            @elseif($product->bar_type == 28)
                                                                28mm
                                                            @elseif($product->bar_type == 32)
                                                                32mm
                                                            @elseif($product->bar_type == 36)
                                                                36mm
                                                            @endif

                                                        </td>
                                                        <td>{{ $product->dia }}</td>
                                                        <td> {{ $product->dia_square }}</td>
                                                        <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                        <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="6" class="text-right">Main Bar Total</th>
                                                    <th> {{ number_format($mainTotalKg, 3) }}</th>
                                                    <th> {{ number_format($mainTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Extra Bar Description</h4>
                                                @foreach($beamConfigure->beamConfigureProducts as $product)
                                                    @if($product->status == 1)
                                                        <?php
                                                        $extraTotalKg += $product->sub_total_kg;
                                                        $extraTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>

                                                <tr>
                                                    <th colspan="6" class="text-right" >Extra Bar Total</th>
                                                    <td> {{ number_format($extraTotalKg, 3) }}</td>
                                                    <td> {{ number_format($extraTotalTon, 3) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $tieTotalKg = 0;
                                                        $tieTotalTon = 0;
                                                    @endphp
                                                <h4>Tie Rod Description</h4>
                                                @foreach($beamConfigure->beamConfigureProducts as $product)
                                                    @if($product->tie_bar_type != null )

                                                        <?php
                                                        $tieTotalKg += $product->sub_total_kg_tie;
                                                        $tieTotalTon += $product->sub_total_ton_tie;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->tie_bar_type == 6)
                                                                    6mm
                                                                @elseif($product->tie_bar_type == 8)
                                                                    8mm
                                                                @elseif($product->tie_bar_type == 10)
                                                                    10mm
                                                                @elseif($product->tie_bar_type == 12)
                                                                    12mm
                                                                @elseif($product->tie_bar_type == 16)
                                                                    16mm
                                                                @elseif($product->tie_bar_type == 18)
                                                                    18mm
                                                                @elseif($product->tie_bar_type == 20)
                                                                    20mm
                                                                @elseif($product->tie_bar_type == 22)
                                                                    22mm
                                                                @elseif($product->tie_bar_type == 25)
                                                                    25mm
                                                                @elseif($product->tie_bar_type == 28)
                                                                    28mm
                                                                @elseif($product->tie_bar_type == 32)
                                                                    32mm
                                                                @elseif($product->tie_bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->tie_dia }}</td>
                                                            <td> {{ $product->tie_dia_square }}</td>
                                                            <td> {{ number_format($product->tie_value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg_tie, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton_tie, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>

                                                <tr>
                                                    <th colspan="6" class="text-right">Sub Total</th>
                                                    <th> {{ number_format($tieTotalKg, 2) }}</th>
                                                    <th> {{ number_format($tieTotalTon, 3) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="6" class="text-right">Total Ton/KG</th>
                                                    <th> {{ number_format($beamConfigure->total_kg, 3) }}</th>
                                                    <th> {{ number_format($beamConfigure->total_ton, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            @if($gradeBeamConfigures)
                                @foreach($gradeBeamConfigures as $beamConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Grade Beam</strong></h3>
                                            <h4><u><i><strong>Grade Beam Configure No: {{$beamConfigure->beam_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Beam Configure No.</th>
                                                    <td>{{ $beamConfigure->beam_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Beam Configure Date</th>
                                                    <td>{{ $beamConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $beamConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $beamConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $beamConfigure->total_kg }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $beamConfigure->total_cement }} Bag</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Sands</th>
                                                    <td>{{ $beamConfigure->total_sands }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Aggregate</th>
                                                    <td>{{ $beamConfigure->total_aggregate }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $beamConfigure->total_picked }} Pcs</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $beamConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Beam Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Spiral Bar</th>
                                                    <td>{{ $beamConfigure->tie_bar }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Spiral Interval</th>
                                                    <td>{{ $beamConfigure->tie_interval }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ratio</th>
                                                    <td>{{ $beamConfigure->first_ratio }}:{{ $beamConfigure->second_ratio }}:{{ $beamConfigure->third_ratio }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Height</th>
                                                    <td>{{ $beamConfigure->beam_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Beam Quantity</th>
                                                    <td>{{ $beamConfigure->beam_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $beamConfigure->total_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $beamConfigure->total_dry_volume }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $mainTotalKg = 0;
                                        $mainTotalTon = 0;
                                        $extraTotalKg = 0;
                                        $extraTotalTon = 0;
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Main Bar Description</h4>
                                                @foreach($beamConfigure->gradeBeamConfigureProducts as $product)
                                                    @if($product->status == 2)
                                                        <?php
                                                        $mainTotalKg += $product->sub_total_kg;
                                                        $mainTotalTon += $product->sub_total_ton;
                                                        ?>
                                                    <tr>
                                                        <td>
                                                        @if($product->bar_type == 6)
                                                            6mm
                                                            @elseif($product->bar_type == 8)
                                                            8mm
                                                            @elseif($product->bar_type == 10)
                                                                10mm
                                                            @elseif($product->bar_type == 12)
                                                                12mm
                                                            @elseif($product->bar_type == 16)
                                                                16mm
                                                            @elseif($product->bar_type == 18)
                                                                18mm
                                                            @elseif($product->bar_type == 20)
                                                                20mm
                                                            @elseif($product->bar_type == 22)
                                                                22mm
                                                            @elseif($product->bar_type == 25)
                                                                25mm
                                                            @elseif($product->bar_type == 28)
                                                                28mm
                                                            @elseif($product->bar_type == 32)
                                                                32mm
                                                            @elseif($product->bar_type == 36)
                                                                36mm
                                                            @endif

                                                        </td>
                                                        <td>{{ $product->dia }}</td>
                                                        <td> {{ $product->dia_square }}</td>
                                                        <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                        <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                        <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="6" class="text-right">Main Bar Total</th>
                                                    <th> {{ number_format($mainTotalKg, 3) }}</th>
                                                    <th> {{ number_format($mainTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Extra Bar Description</h4>
                                                @foreach($beamConfigure->gradeBeamConfigureProducts as $product)
                                                    @if($product->status == 1)
                                                        <?php
                                                        $extraTotalKg += $product->sub_total_kg;
                                                        $extraTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>

                                                <tr>
                                                    <th colspan="6" class="text-right" >Extra Bar Total</th>
                                                    <td> {{ number_format($extraTotalKg, 3) }}</td>
                                                    <td> {{ number_format($extraTotalTon, 3) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $tieTotalKg = 0;
                                                        $tieTotalTon = 0;
                                                    @endphp
                                                <h4>Tie Rod Description</h4>
                                                @foreach($beamConfigure->gradeBeamConfigureProducts as $product)
                                                    @if($product->tie_bar_type != null )

                                                        <?php
                                                        $tieTotalKg += $product->sub_total_kg_tie;
                                                        $tieTotalTon += $product->sub_total_ton_tie;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->tie_bar_type == 6)
                                                                    6mm
                                                                @elseif($product->tie_bar_type == 8)
                                                                    8mm
                                                                @elseif($product->tie_bar_type == 10)
                                                                    10mm
                                                                @elseif($product->tie_bar_type == 12)
                                                                    12mm
                                                                @elseif($product->tie_bar_type == 16)
                                                                    16mm
                                                                @elseif($product->tie_bar_type == 18)
                                                                    18mm
                                                                @elseif($product->tie_bar_type == 20)
                                                                    20mm
                                                                @elseif($product->tie_bar_type == 22)
                                                                    22mm
                                                                @elseif($product->tie_bar_type == 25)
                                                                    25mm
                                                                @elseif($product->tie_bar_type == 28)
                                                                    28mm
                                                                @elseif($product->tie_bar_type == 32)
                                                                    32mm
                                                                @elseif($product->tie_bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->tie_dia }}</td>
                                                            <td> {{ $product->tie_dia_square }}</td>
                                                            <td> {{ number_format($product->tie_value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg_tie, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton_tie, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>

                                                <tr>
                                                    <th colspan="6" class="text-right">Sub Total</th>
                                                    <th> {{ number_format($tieTotalKg, 2) }}</th>
                                                    <th> {{ number_format($tieTotalTon, 3) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="6" class="text-right">Total Ton/KG</th>
                                                    <th> {{ number_format($beamConfigure->total_kg, 3) }}</th>
                                                    <th> {{ number_format($beamConfigure->total_ton, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            @if($columnConfigures)
                                @foreach($columnConfigures as $columnConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Column</strong></h3>
                                            <h4><u><i><strong>Column Configure No: {{$columnConfigure->column_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Column Configure No.</th>
                                                    <td>{{ $columnConfigure->column_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Column Configure Date</th>
                                                    <td>{{ $columnConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $columnConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $columnConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $columnConfigure->total_kg }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $columnConfigure->total_cement }} Bag</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Sands</th>
                                                    <td>{{ $columnConfigure->total_sands }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Aggregate</th>
                                                    <td>{{ $columnConfigure->total_aggregate }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $columnConfigure->total_picked }} Pcs</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $columnConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Column Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Total Ring</th>
                                                    <td>{{ $columnConfigure->ring_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tie Interval</th>
                                                    <td>{{ $columnConfigure->tie_interval }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ratio</th>
                                                    <td>{{ $columnConfigure->first_ratio }}:{{ $columnConfigure->second_ratio }}:{{ $columnConfigure->third_ratio }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Column Height</th>
                                                    <td>{{ $columnConfigure->column_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tie Length</th>
                                                    <td>{{ $columnConfigure->tie_length_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tie Width</th>
                                                    <td>{{ $columnConfigure->tie_width_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $columnConfigure->total_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $columnConfigure->total_dry_volume }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $tieTotalKg = 0;
                                        $tieTotalTon = 0;
                                        $straightTotalKg = 0;
                                        $straightTotalTon = 0;
                                    @endphp
                                      <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Main Rod Description</h4>
                                                @foreach($columnConfigure->columnConfigureProducts as $product)
                                                    @if($product->bar_type != null)


                                                        <?php
                                                        $straightTotalKg += $product->sub_total_kg_straight;
                                                        $straightTotalTon += $product->sub_total_ton_straight;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg_straight, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton_straight, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="6" class="text-right">Sub Total</th>
                                                    <th> {{ number_format($straightTotalKg, 2) }}</th>
                                                    <th> {{ number_format($straightTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Tie Rod Description</h4>
                                                @foreach($columnConfigure->columnConfigureProducts as $product)
                                                    @if($product->tie_bar_type != null )

                                                        <?php
                                                        $tieTotalKg += $product->sub_total_kg_tie;
                                                        $tieTotalTon += $product->sub_total_ton_tie;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->tie_bar_type == 6)
                                                                    6mm
                                                                @elseif($product->tie_bar_type == 8)
                                                                    8mm
                                                                @elseif($product->tie_bar_type == 10)
                                                                    10mm
                                                                @elseif($product->tie_bar_type == 12)
                                                                    12mm
                                                                @elseif($product->tie_bar_type == 16)
                                                                    16mm
                                                                @elseif($product->tie_bar_type == 18)
                                                                    18mm
                                                                @elseif($product->tie_bar_type == 20)
                                                                    20mm
                                                                @elseif($product->tie_bar_type == 22)
                                                                    22mm
                                                                @elseif($product->tie_bar_type == 25)
                                                                    25mm
                                                                @elseif($product->tie_bar_type == 28)
                                                                    28mm
                                                                @elseif($product->tie_bar_type == 32)
                                                                    32mm
                                                                @elseif($product->tie_bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->tie_dia }}</td>
                                                            <td> {{ $product->tie_dia_square }}</td>
                                                            <td> {{ number_format($product->tie_value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg_tie, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton_tie, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>

                                                <tr>
                                                    <th colspan="6" class="text-right">Sub Total</th>
                                                    <th> {{ number_format($tieTotalKg, 2) }}</th>
                                                    <th> {{ number_format($tieTotalTon, 3) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="6" class="text-right">Total Ton/KG</th>
                                                    <th> {{ number_format($columnConfigure->total_kg, 2) }}</th>
                                                    <th> {{ number_format($columnConfigure->total_ton, 3) }}</th>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                            @if($shortColumnConfigures)
                                @foreach($shortColumnConfigures as $columnConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Short Column</strong></h3>
                                            <h4><u><i><strong>Short Column Configure No: {{$columnConfigure->column_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Column Configure No.</th>
                                                    <td>{{ $columnConfigure->column_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Column Configure Date</th>
                                                    <td>{{ $columnConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $columnConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $columnConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $columnConfigure->total_kg }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $columnConfigure->total_cement }} Bag</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Sands</th>
                                                    <td>{{ $columnConfigure->total_sands }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Aggregate</th>
                                                    <td>{{ $columnConfigure->total_aggregate }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $columnConfigure->total_picked }} Pcs</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $columnConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Column Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Total Ring</th>
                                                    <td>{{ $columnConfigure->ring_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tie Interval</th>
                                                    <td>{{ $columnConfigure->tie_interval }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ratio</th>
                                                    <td>{{ $columnConfigure->first_ratio }}:{{ $columnConfigure->second_ratio }}:{{ $columnConfigure->third_ratio }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Column Height</th>
                                                    <td>{{ $columnConfigure->column_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tie Length</th>
                                                    <td>{{ $columnConfigure->tie_length_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tie Width</th>
                                                    <td>{{ $columnConfigure->tie_width_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $columnConfigure->total_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $columnConfigure->total_dry_volume }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $tieTotalKg = 0;
                                        $tieTotalTon = 0;
                                        $straightTotalKg = 0;
                                        $straightTotalTon = 0;
                                    @endphp
                                      <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Main Rod Description</h4>
                                                @foreach($columnConfigure->shortColumnConfigureProducts as $product)
                                                    @if($product->bar_type != null)


                                                        <?php
                                                        $straightTotalKg += $product->sub_total_kg_straight;
                                                        $straightTotalTon += $product->sub_total_ton_straight;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg_straight, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton_straight, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="6" class="text-right">Sub Total</th>
                                                    <th> {{ number_format($straightTotalKg, 2) }}</th>
                                                    <th> {{ number_format($straightTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <h4>Tie Rod Description</h4>
                                                @foreach($columnConfigure->shortColumnConfigureProducts as $product)
                                                    @if($product->tie_bar_type != null )

                                                        <?php
                                                        $tieTotalKg += $product->sub_total_kg_tie;
                                                        $tieTotalTon += $product->sub_total_ton_tie;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->tie_bar_type == 6)
                                                                    6mm
                                                                @elseif($product->tie_bar_type == 8)
                                                                    8mm
                                                                @elseif($product->tie_bar_type == 10)
                                                                    10mm
                                                                @elseif($product->tie_bar_type == 12)
                                                                    12mm
                                                                @elseif($product->tie_bar_type == 16)
                                                                    16mm
                                                                @elseif($product->tie_bar_type == 18)
                                                                    18mm
                                                                @elseif($product->tie_bar_type == 20)
                                                                    20mm
                                                                @elseif($product->tie_bar_type == 22)
                                                                    22mm
                                                                @elseif($product->tie_bar_type == 25)
                                                                    25mm
                                                                @elseif($product->tie_bar_type == 28)
                                                                    28mm
                                                                @elseif($product->tie_bar_type == 32)
                                                                    32mm
                                                                @elseif($product->tie_bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->tie_dia }}</td>
                                                            <td> {{ $product->tie_dia_square }}</td>
                                                            <td> {{ number_format($product->tie_value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->tie_kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg_tie, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton_tie, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="6" class="text-right">Sub Total</th>
                                                    <th> {{ number_format($tieTotalKg, 2) }}</th>
                                                    <th> {{ number_format($tieTotalTon, 3) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="6" class="text-right">Total Ton/KG</th>
                                                    <th> {{ number_format($columnConfigure->total_kg, 2) }}</th>
                                                    <th> {{ number_format($columnConfigure->total_ton, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                            @if($footingConfigures)
                                @foreach($footingConfigures as $columnConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Footing</strong></h3>
                                            <h4><u><i><strong>Footing Configure No: {{$columnConfigure->column_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th> Footing Configure No.</th>
                                                    <td>{{ $columnConfigure->common_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th> Footing Configure Date</th>
                                                    <td>{{ $columnConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project Name</th>
                                                    <td>{{ $columnConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Costing Segment Name</th>
                                                    <td>{{ $columnConfigure->costingSegment->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $columnConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $columnConfigure->total_kg }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $columnConfigure->total_cement_bag }} Bag</td>
                                                </tr>
                                                <tr>
                                                    <th>Local Sands</th>
                                                    <td>{{ $columnConfigure->total_sands }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Sylhet Sands</th>
                                                    <td>{{ $columnConfigure->total_s_sands }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Aggregate</th>
                                                    <td>{{ $columnConfigure->total_aggregate }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $columnConfigure->total_picked }} Pcs</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $columnConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">{{$columnConfigure->costingSegment->name}} Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Ratio</th>
                                                    <td>{{ $columnConfigure->first_ratio }}:{{ $columnConfigure->second_ratio }}:{{ $columnConfigure->third_ratio }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{$columnConfigure->costingSegment->name}} Quantity</th>
                                                    <td>{{ $columnConfigure->costing_segment_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Footing Length</th>
                                                    <td>{{ $columnConfigure->segment_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Footing Width</th>
                                                    <td>{{ $columnConfigure->segment_width }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Footing Thickness</th>
                                                    <td>{{ $columnConfigure->segment_thickness }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $columnConfigure->total_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $columnConfigure->total_dry_volume }}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $mainTotalKg = 0;
                                        $mainTotalTon = 0;
                                        $extraTotalKg = 0;
                                        $extraTotalTon = 0;
                                    @endphp
                                     <div class="row">
                                        <div class="col-md-12">
                                            <h4>Main Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Length Type</th>
                                                    <th>Length</th>
                                                    <th>Spacing</th>
                                                    <th>Type Length</th>
                                                    <th>Layer</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($columnConfigure->footingConfigureProducts as $product)

                                                    @if($product->status == null)
                                                        <?php
                                                        $mainTotalKg += $product->sub_total_kg;
                                                        $mainTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                            @if($product->bar_type == 6)
                                                                6mm
                                                                @elseif($product->bar_type == 8)
                                                                8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td>
                                                                @if($product->length_type == 1)
                                                                    X-Direction
                                                                @else
                                                                    Y-Direction
                                                                @endif
                                                            </td>
                                                            <td> {{ number_format($product->length, 2) }}</td>
                                                            <td> {{ number_format($product->spacing, 2) }}</td>
                                                            <td> {{ number_format($product->type_length, 2) }}</td>
                                                            <td> {{ number_format($product->layer, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="11" class="text-right">Main Bar Total</th>
                                                    <th> {{ number_format($mainTotalKg, 2) }}</th>
                                                    <th> {{ number_format($mainTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($commonConfigures)
                                @foreach($commonConfigures as $commonConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Slab</strong></h3>
                                            <h4><u><i><strong>{{$commonConfigure->costingSegment->name}} Configure No: {{$commonConfigure->common_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Slab Configure No.</th>
                                                    <td>{{ $commonConfigure->common_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Slab Configure Date</th>
                                                    <td>{{ $commonConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project Name</th>
                                                    <td>{{ $commonConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Costing Segment Name</th>
                                                    <td>{{ $commonConfigure->costingSegment->name }}</td>
                                                </tr>
                                                @if ($commonConfigure->course_aggregate_type == 3)

                                                @else
                                                    <tr>
                                                        <th>Total Cement(Cft)</th>
                                                        <td>{{ $commonConfigure->total_cement }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Cement</th>
                                                        <td>{{ $commonConfigure->total_cement_bag }} Bag</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Local Sands</th>
                                                        <td>{{ $commonConfigure->total_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Sylhet Sands</th>
                                                        <td>{{ $commonConfigure->total_s_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Aggregate</th>
                                                        <td>{{ $commonConfigure->total_aggregate }} Cft</td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $commonConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">{{$commonConfigure->costingSegment->name}} Info</th>
                                                </tr>
                                                <tr>
                                                    <th>{{$commonConfigure->costingSegment->name}} Quantity</th>
                                                    <td>{{ $commonConfigure->costing_segment_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $commonConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $commonConfigure->total_kg }} Rod</td>
                                                </tr>
                                               @if ($commonConfigure->course_aggregate_type == 2)
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $commonConfigure->total_picked }} Pcs</td>
                                                </tr>
                                               @endif
                                                <tr>
                                                    <th>Slab Length</th>
                                                    <td>{{ $commonConfigure->slab_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Slab Width</th>
                                                    <td>{{ $commonConfigure->slab_width }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Slab Thickness</th>
                                                    <td>{{ $commonConfigure->slab_thickness }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $commonConfigure->total_volume }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $commonConfigure->total_dry_volume }} </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $mainTotalKg = 0;
                                        $mainTotalTon = 0;
                                        $extraTotalKg = 0;
                                        $extraTotalTon = 0;
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Main Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Length Type</th>
                                                    <th>Length</th>
                                                    <th>Spacing</th>
                                                    <th>Type Length</th>
                                                    <th>Layer</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($commonConfigure->commonConfigureProducts as $product)

                                                    @if($product->status == null)
                                                        <?php
                                                        $mainTotalKg += $product->sub_total_kg;
                                                        $mainTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td>
                                                                @if($product->length_type == 1)
                                                                    X-Direction
                                                                @else
                                                                    Y-Direction
                                                                @endif
                                                            </td>
                                                            <td> {{ number_format($product->length, 2) }}</td>
                                                            <td> {{ number_format($product->spacing, 2) }}</td>
                                                            <td> {{ number_format($product->type_length, 2) }}</td>
                                                            <td> {{ number_format($product->layer, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="11" class="text-right">Main Bar Total</th>
                                                    <th> {{ number_format($mainTotalKg, 2) }}</th>
                                                    <th> {{ number_format($mainTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Extra Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Extra Bar</th>
                                                    <th>Extra Length</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($commonConfigure->commonConfigureProducts as $product)

                                                    @if($product->status == 1)
                                                        <?php
                                                        $extraTotalKg += $product->sub_total_kg;
                                                        $extraTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->number_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->extra_length, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="8" class="text-right" >Extra Bar Total</th>
                                                    <td> {{ number_format($extraTotalKg, 2) }}</td>
                                                    <td> {{ number_format($extraTotalTon, 3) }}</td>
                                                </tr>

                                                <tr>
                                                    <th colspan="8" class="text-right" >Total Ton/KG</th>
                                                    <td> {{ number_format($commonConfigure->total_kg, 2) }}</td>
                                                    <td> {{ number_format($commonConfigure->total_ton, 3) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            @if($pileCapConfigures)
                                @foreach($pileCapConfigures as $commonConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Pile Cap Configure</strong></h3>
                                            <h4><u><i><strong>{{$commonConfigure->costingSegment->name}} Configure No: {{$commonConfigure->common_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th> Pile Cap Type.</th>
                                                    <td>{{ $commonConfigure->footingType->name ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <th> Pile Cap Configure Date</th>
                                                    <td>{{ $commonConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project Name</th>
                                                    <td>{{ $commonConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Costing Segment Name</th>
                                                    <td>{{ $commonConfigure->costingSegment->name }}</td>
                                                </tr>
                                                @if ($commonConfigure->course_aggregate_type == 3)

                                                @elseif ($commonConfigure->course_aggregate_type == 2)
                                                    <tr>
                                                        <th>Total Cement</th>
                                                        <td>{{ $commonConfigure->total_cement_bag }} Bag</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Local Sands</th>
                                                        <td>{{ $commonConfigure->total_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Sylhet Sands</th>
                                                        <td>{{ $commonConfigure->total_s_sands }} Cft</td>
                                                    </tr>

                                                @else
                                                    <tr>
                                                        <th>Total Cement</th>
                                                        <td>{{ $commonConfigure->total_cement_bag }} Bag</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Local Sands</th>
                                                        <td>{{ $commonConfigure->total_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Sylhet Sands</th>
                                                        <td>{{ $commonConfigure->total_s_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Aggregate</th>
                                                        <td>{{ $commonConfigure->total_aggregate }} Cft</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $commonConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">{{$commonConfigure->costingSegment->name}} Info</th>
                                                </tr>
                                                <tr>
                                                    <th>{{$commonConfigure->costingSegment->name}} Quantity</th>
                                                    <td>{{ $commonConfigure->costing_segment_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $commonConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $commonConfigure->total_kg }} Rod</td>
                                                </tr>
                                                @if ($commonConfigure->course_aggregate_type == 2)
                                                    <tr>
                                                        <th>Total Piked</th>
                                                        <td>{{ $commonConfigure->total_picked }} Pcs</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>Pile Cap Length</th>
                                                    <td>{{ $commonConfigure->segment_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Pile Cap Width</th>
                                                    <td>{{ $commonConfigure->segment_width }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Pile Cap Thickness</th>
                                                    <td>{{ $commonConfigure->segment_thickness }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $commonConfigure->total_volume }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $commonConfigure->total_dry_volume }}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $mainTotalKg = 0;
                                        $mainTotalTon = 0;
                                        $extraTotalKg = 0;
                                        $extraTotalTon = 0;
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Main Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Length Type</th>
                                                    <th>Length</th>
                                                    <th>Spacing</th>
                                                    <th>Type Length</th>
                                                    <th>Layer</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($commonConfigure->pileCapConfigureProducts as $product)

                                                    @if($product->status == null)
                                                        <?php
                                                        $mainTotalKg += $product->sub_total_kg;
                                                        $mainTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                            @if($product->bar_type == 6)
                                                                6mm
                                                                @elseif($product->bar_type == 8)
                                                                8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td>
                                                                @if($product->length_type == 1)
                                                                    X-Direction
                                                                @else
                                                                Y-Direction
                                                                @endif
                                                            </td>
                                                            <td> {{ number_format($product->length, 2) }}</td>
                                                            <td> {{ number_format($product->spacing, 2) }}</td>
                                                            <td> {{ number_format($product->type_length, 2) }}</td>
                                                            <td> {{ number_format($product->layer, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="11" class="text-right">Main Bar Total</th>
                                                    <th> {{ number_format($mainTotalKg, 2) }}</th>
                                                    <th> {{ number_format($mainTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                            @if($matConfigures)
                                @foreach($matConfigures as $commonConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Mat Configure</strong></h3>
                                            <h4><u><i><strong>{{$commonConfigure->costingSegment->name}} Configure No: {{$commonConfigure->common_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Mat Configure No.</th>
                                                    <td>{{ $commonConfigure->common_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mat Configure Date</th>
                                                    <td>{{ $commonConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project Name</th>
                                                    <td>{{ $commonConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Costing Segment Name</th>
                                                    <td>{{ $commonConfigure->costingSegment->name }}</td>
                                                </tr>
                                                @if ($commonConfigure->course_aggregate_type == 3)

                                                @elseif ($commonConfigure->course_aggregate_type == 2)
                                                    <tr>
                                                        <th>Total Cement(Cft)</th>
                                                        <td>{{ $commonConfigure->total_cement }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Cement</th>
                                                        <td>{{ $commonConfigure->total_cement_bag }} Bag</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Local Sands</th>
                                                        <td>{{ $commonConfigure->total_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Sylhet Sands</th>
                                                        <td>{{ $commonConfigure->total_s_sands }} Cft</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <th>Total Cement(Cft)</th>
                                                        <td>{{ $commonConfigure->total_cement }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Cement</th>
                                                        <td>{{ $commonConfigure->total_cement_bag }} Bag</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Local Sands</th>
                                                        <td>{{ $commonConfigure->total_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Sylhet Sands</th>
                                                        <td>{{ $commonConfigure->total_s_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Aggregate</th>
                                                        <td>{{ $commonConfigure->total_aggregate }} Cft</td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $commonConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">{{$commonConfigure->costingSegment->name}} Info</th>
                                                </tr>
                                                <tr>
                                                    <th>{{$commonConfigure->costingSegment->name}} Quantity</th>
                                                    <td>{{ $commonConfigure->costing_segment_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $commonConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $commonConfigure->total_kg }} Rod</td>
                                                </tr>
                                               @if ($commonConfigure->course_aggregate_type == 2)
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $commonConfigure->total_picked }} Pcs</td>
                                                </tr>
                                               @endif
                                                <tr>
                                                    <th>Mat Length</th>
                                                    <td>{{ $commonConfigure->slab_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mat Width</th>
                                                    <td>{{ $commonConfigure->slab_width }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Mat Thickness</th>
                                                    <td>{{ $commonConfigure->slab_thickness }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $commonConfigure->total_volume }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $commonConfigure->total_dry_volume }} </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $mainTotalKg = 0;
                                        $mainTotalTon = 0;
                                        $extraTotalKg = 0;
                                        $extraTotalTon = 0;
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Main Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Length Type</th>
                                                    <th>Length</th>
                                                    <th>Spacing</th>
                                                    <th>Type Length</th>
                                                    <th>Layer</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($commonConfigure->matConfigureProducts as $product)

                                                    @if($product->status == null)
                                                        <?php
                                                        $mainTotalKg += $product->sub_total_kg;
                                                        $mainTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                            @if($product->bar_type == 6)
                                                                6mm
                                                                @elseif($product->bar_type == 8)
                                                                8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 3) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 3) }}</td>
                                                            <td>
                                                                @if($product->length_type == 1)
                                                                    X-Direction
                                                                @else
                                                                Y-Direction
                                                                @endif
                                                            </td>
                                                            <td> {{ number_format($product->length, 2) }}</td>
                                                            <td> {{ number_format($product->spacing, 2) }}</td>
                                                            <td> {{ number_format($product->type_length, 2) }}</td>
                                                            <td> {{ number_format($product->layer, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="11" class="text-right">Main Bar Total</th>
                                                    <th> {{ number_format($mainTotalKg, 2) }}</th>
                                                    <th> {{ number_format($mainTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Extra Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Extra Bar</th>
                                                    <th>Extra Length</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($commonConfigure->matConfigureProducts as $product)

                                                    @if($product->status == 1)
                                                        <?php
                                                        $extraTotalKg += $product->sub_total_kg;
                                                        $extraTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->number_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->extra_length, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="8" class="text-right" >Extra Bar Total</th>
                                                    <td><b>{{ number_format($extraTotalKg, 2) }}</b></td>
                                                    <td><b>{{ number_format($extraTotalTon, 3) }}</b></td>
                                                </tr>

                                                <tr>
                                                    <th colspan="8" class="text-right" >Total Ton/KG</th>
                                                    <td><b>{{ number_format($commonConfigure->total_kg, 3) }}</b></td>
                                                    <td><b>{{ number_format($commonConfigure->total_ton, 3) }}</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                            @if($returningConfigures)
                                @foreach($returningConfigures as $commonConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Returning Wall Configure</strong></h3>
                                            <h4><u><i><strong>{{$commonConfigure->costingSegment->name}} Configure No: {{$commonConfigure->common_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Mat Configure No.</th>
                                                    <td>{{ $commonConfigure->common_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mat Configure Date</th>
                                                    <td>{{ $commonConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project Name</th>
                                                    <td>{{ $commonConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Costing Segment Name</th>
                                                    <td>{{ $commonConfigure->costingSegment->name }}</td>
                                                </tr>
                                                @if ($commonConfigure->course_aggregate_type == 3)

                                                @elseif ($commonConfigure->course_aggregate_type == 2)
                                                    <tr>
                                                        <th>Total Cement(Cft)</th>
                                                        <td>{{ $commonConfigure->total_cement }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Cement</th>
                                                        <td>{{ $commonConfigure->total_cement_bag }} Bag</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Local Sands</th>
                                                        <td>{{ $commonConfigure->total_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Sylhet Sands</th>
                                                        <td>{{ $commonConfigure->total_s_sands }} Cft</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <th>Total Cement(Cft)</th>
                                                        <td>{{ $commonConfigure->total_cement }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Cement</th>
                                                        <td>{{ $commonConfigure->total_cement_bag }} Bag</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Local Sands</th>
                                                        <td>{{ $commonConfigure->total_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Sylhet Sands</th>
                                                        <td>{{ $commonConfigure->total_s_sands }} Cft</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Aggregate</th>
                                                        <td>{{ $commonConfigure->total_aggregate }} Cft</td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $commonConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">{{$commonConfigure->costingSegment->name}} Info</th>
                                                </tr>
                                                <tr>
                                                    <th>{{$commonConfigure->costingSegment->name}} Quantity</th>
                                                    <td>{{ $commonConfigure->costing_segment_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Ton</th>
                                                    <td>{{ $commonConfigure->total_ton }} Rod</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kg</th>
                                                    <td>{{ $commonConfigure->total_kg }} Rod</td>
                                                </tr>
                                               @if ($commonConfigure->course_aggregate_type == 2)
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $commonConfigure->total_picked }} Pcs</td>
                                                </tr>
                                               @endif
                                                <tr>
                                                    <th>Mat Length</th>
                                                    <td>{{ $commonConfigure->slab_length }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mat Width</th>
                                                    <td>{{ $commonConfigure->slab_width }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Mat Thickness</th>
                                                    <td>{{ $commonConfigure->slab_thickness }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Volume</th>
                                                    <td>{{ $commonConfigure->total_volume }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Dry Volume</th>
                                                    <td>{{ $commonConfigure->total_dry_volume }} </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $mainTotalKg = 0;
                                        $mainTotalTon = 0;
                                        $extraTotalKg = 0;
                                        $extraTotalTon = 0;
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Main Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Length Type</th>
                                                    <th>Length</th>
                                                    <th>Spacing</th>
                                                    <th>Type Length</th>
                                                    <th>Layer</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($commonConfigure->returningWallConfigureProducts as $product)

                                                    @if($product->status == null)
                                                        <?php
                                                        $mainTotalKg += $product->sub_total_kg;
                                                        $mainTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                            @if($product->bar_type == 6)
                                                                6mm
                                                                @elseif($product->bar_type == 8)
                                                                8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 3) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 3) }}</td>
                                                            <td>
                                                                @if($product->length_type == 1)
                                                                    X-Direction
                                                                @else
                                                                Y-Direction
                                                                @endif
                                                            </td>
                                                            <td> {{ number_format($product->length, 2) }}</td>
                                                            <td> {{ number_format($product->spacing, 2) }}</td>
                                                            <td> {{ number_format($product->type_length, 2) }}</td>
                                                            <td> {{ number_format($product->layer, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="11" class="text-right">Main Bar Total</th>
                                                    <th> {{ number_format($mainTotalKg, 2) }}</th>
                                                    <th> {{ number_format($mainTotalTon, 3) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Extra Bar Calculation</h4>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Bar</th>
                                                    <th>Dia</th>
                                                    <th>Dia(D^2)</th>
                                                    <th>Value of Bar</th>
                                                    <th>Kg/Rft</th>
                                                    <th>Kg/Ton</th>
                                                    <th>Extra Bar</th>
                                                    <th>Extra Length</th>
                                                    <th>Sub Total Kg</th>
                                                    <th>Sub Total Ton</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($commonConfigure->returningWallConfigureProducts as $product)

                                                    @if($product->status == 1)
                                                        <?php
                                                        $extraTotalKg += $product->sub_total_kg;
                                                        $extraTotalTon += $product->sub_total_ton;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if($product->bar_type == 6)
                                                                    6mm
                                                                @elseif($product->bar_type == 8)
                                                                    8mm
                                                                @elseif($product->bar_type == 10)
                                                                    10mm
                                                                @elseif($product->bar_type == 12)
                                                                    12mm
                                                                @elseif($product->bar_type == 16)
                                                                    16mm
                                                                @elseif($product->bar_type == 18)
                                                                    18mm
                                                                @elseif($product->bar_type == 20)
                                                                    20mm
                                                                @elseif($product->bar_type == 22)
                                                                    22mm
                                                                @elseif($product->bar_type == 25)
                                                                    25mm
                                                                @elseif($product->bar_type == 28)
                                                                    28mm
                                                                @elseif($product->bar_type == 32)
                                                                    32mm
                                                                @elseif($product->bar_type == 36)
                                                                    36mm
                                                                @endif

                                                            </td>
                                                            <td>{{ $product->dia }}</td>
                                                            <td> {{ $product->dia_square }}</td>
                                                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                                            <td> {{ number_format($product->number_of_bar, 2) }}</td>
                                                            <td> {{ number_format($product->extra_length, 2) }}</td>
                                                            <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th colspan="8" class="text-right" >Extra Bar Total</th>
                                                    <td><b>{{ number_format($extraTotalKg, 2) }}</b></td>
                                                    <td><b>{{ number_format($extraTotalTon, 3) }}</b></td>
                                                </tr>

                                                <tr>
                                                    <th colspan="8" class="text-right" >Total Ton/KG</th>
                                                    <td><b>{{ number_format($commonConfigure->total_kg, 3) }}</b></td>
                                                    <td><b>{{ number_format($commonConfigure->total_ton, 3) }}</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                @endforeach
                            @endif

                            @if($bricksConfigures)
                                @foreach($bricksConfigures as $bricksConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Bricks</strong></h3>
                                            <h4><u><i><strong>
                                                @if ($bricksConfigure->wall_type == 1)
                                                    Bricks Configure For: 3" Wall
                                                @elseif ($bricksConfigure->wall_type == 2)
                                                    Bricks Configure For: 5" Wall
                                                @else
                                                    Bricks Configure For: 10" Wall
                                                @endif
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Bricks Configure No.</th>
                                                    <td>{{ $bricksConfigure->bricks_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Bricks Configure Date</th>
                                                    <td>{{ $bricksConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Floor Quantity</th>
                                                    <td>{{ $bricksConfigure->floor_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $bricksConfigure->total_cement }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $bricksConfigure->total_cement_bag }} Bag</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Sands</th>
                                                    <td>{{ $bricksConfigure->total_sands }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $bricksConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Bricks Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $bricksConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor</th>
                                                    <td>{{ $bricksConfigure->estimateFloor->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor Unit</th>
                                                    <td>{{ $bricksConfigure->estimateFloorUnit->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ratio</th>
                                                    <td>{{ $bricksConfigure->first_ratio }}:{{ $bricksConfigure->second_ratio }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Unit Section</th>
                                                    <th>Wall Direction</th>
                                                    <th>Length</th>
                                                    <th>Height</th>
                                                    <th>Wall Number</th>
                                                    <th>Total Deduction</th>
                                                    <th>Sub Total Area</th>
                                                    <th>Sub Total Bricks</th>
                                                    <th>Sub Total Morters</th>
                                                    <th>Sub Total Cement</th>
                                                    <th>Sub Total Sands</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($bricksConfigure->bricksConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->unitSection->name }}</td>
                                                        <td>
                                                            @if($product->wall_direction == 1)
                                                                East
                                                            @elseif($product->wall_direction == 2)
                                                                West
                                                            @elseif($product->wall_direction == 3)
                                                                North
                                                            @elseif($product->wall_direction == 4)
                                                                South
                                                            @else
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->length }}</td>
                                                        <td> {{ $product->height }}</td>
                                                        <td> {{ number_format($product->wall_number, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_deduction, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_area, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_bricks, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_morters, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_cement, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_sands, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th class="text-right" colspan="7">Total</th>
                                                    <td> {{ number_format($bricksConfigure->total_bricks, 2) }} Pcs</td>
                                                    <td> {{ number_format($bricksConfigure->total_morters, 2) }} Cft</td>
                                                    <td> {{ number_format($bricksConfigure->total_cement, 2) }} Cft</td>
                                                    <td> {{ number_format($bricksConfigure->total_sands, 2) }} Cft</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($plasterConfigures)
                                @foreach($plasterConfigures as $plasterConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Plaster</strong></h3>
                                            <h4><u><i><strong>Plaster Configure No: {{$plasterConfigure->plaster_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Plaster Project Name</th>
                                                    <td>{{ $plasterConfigure->project->name ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Floor Name</th>
                                                    <td>{{ $plasterConfigure->floor->name ?? '' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Floor Quantity</th>
                                                    <td>{{ $plasterConfigure->floor_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Plaster Configure Date</th>
                                                    <td>{{ $plasterConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $plasterConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Plaster Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Floor Unit Name</th>
                                                    <td>{{ $plasterConfigure->floorUnit->name ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ratio</th>
                                                    <td>{{ $plasterConfigure->first_ratio }}:{{ $plasterConfigure->second_ratio }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement</th>
                                                    <td>{{ $plasterConfigure->total_cement }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cement Bag</th>
                                                    <td>{{ $plasterConfigure->total_cement_bag }} Bag</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Sands</th>
                                                    <td>{{ $plasterConfigure->total_sands }} Cft</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Lenght</th>
                                                        <th>Height</th>
                                                        <th>Plaster Wall Side</th>
                                                        <th>Plaster Thickness</th>
                                                        <th>Sub Total Area</th>
                                                        <th>Sub Total Cement</th>
                                                        <th>Sub Total Sands</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($plasterConfigure->plasterConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->length }}</td>
                                                        <td>{{ $product->height }}</td>
                                                        <td>{{ $product->plaster_side }}</td>
                                                        <td>{{ $product->plaster_thickness }}</td>
                                                        <td>{{ number_format($product->sub_total_dry_area, 2) }}</td>
                                                        <td>{{ number_format($product->sub_total_cement, 2) }}</td>
                                                        <td>{{ number_format($product->sub_total_sands, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th class="text-right" colspan="4">Total</th>
                                                    <td> {{ number_format($plasterConfigure->total_plaster_area, 2) }} Cft</td>
                                                    <td> {{ number_format($plasterConfigure->total_cement, 2) }} Cft</td>
                                                    <td> {{ number_format($plasterConfigure->total_sands, 2) }} Cft</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($grillGlassTilesConfigures)
                                @foreach($grillGlassTilesConfigures as $grillGlassTilesConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>
                                                    Grill Configure
                                                </strong></h3>
                                            <h4><u><i><strong>
                                                        Grill
                                                    Configure No: {{$grillGlassTilesConfigure->grill_glass_tiles_configure_no}}
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th> Grill Configure No. </th>
                                                    <td>{{ $grillGlassTilesConfigure->grill_glass_tiles_configure_no ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th> Grill Configure No.</th>
                                                    <td>{{ $grillGlassTilesConfigure->floor_number ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th> Grill Configure Date</th>
                                                    <td>{{ $grillGlassTilesConfigure->date ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Grill Type</th>
                                                    <td>{{ $grillGlassTilesConfigure->configure_type ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Grill Size</th>
                                                    <td>{{ $grillGlassTilesConfigure->grill_size ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $grillGlassTilesConfigure->note ??'' }}</td>
                                                </tr>

                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">
                                                            Grill  Information
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $grillGlassTilesConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor</th>
                                                    <td>{{ $grillGlassTilesConfigure->estimateFloor->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor Unit</th>
                                                    <td>{{ $grillGlassTilesConfigure->estimateFloorUnit->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Grill Sft(Single Floor)</th>
                                                    <td>{{ $grillGlassTilesConfigure->total_area_without_floor }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Grill Sft(All Floor)</th>
                                                    <td>{{ $grillGlassTilesConfigure->total_area_with_floor }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Unit Section Name</th>
                                                    <th>Length</th>
                                                    <th>Height/Width</th>
                                                    <th>Quantity</th>
                                                    <th>Sub Total Area</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($grillGlassTilesConfigure->grillGlassTilesConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->unitSection->name }}</td>
                                                        <td>{{ $product->length }}</td>
                                                        <td> {{ $product->height }}</td>
                                                        <td> {{ number_format($product->quantity, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_area, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th class="text-right" colspan="4">Single Floor Total</th>
                                                        <td> {{ number_format($grillGlassTilesConfigure->total_area_without_floor, 2) }} Sft/Kg</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4">All Floor Total</th>
                                                        <td> {{ number_format($grillGlassTilesConfigure->total_area_with_floor, 2) }} Sft/Kg</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($glassConfigures)
                                @foreach($glassConfigures as $glassConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>
                                                Glass Configure
                                                </strong></h3>
                                            <h4><u><i><strong>
                                                        Glass
                                                    Configure No: {{$glassConfigure->grill_glass_tiles_configure_no}}
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th> Glass & Thai Configure No.</th>
                                                    <td>{{ $glassConfigure->grill_glass_tiles_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th> Glass & Thai Configure No</th>
                                                    <td>{{ $glassConfigure->floor_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th> Glass & Thai Configure Date</th>
                                                    <td>{{ $glassConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th> Glass & Thai Configure Type Name</th>
                                                    <td><b>{{ $glassConfigure->configure_type }}</b></td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $glassConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Glass Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $glassConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor</th>
                                                    <td>{{ $glassConfigure->estimateFloor->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor Unit</th>
                                                    <td>{{ $glassConfigure->estimateFloorUnit->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Glass Area(Single Floor)</th>
                                                    <td>{{ $glassConfigure->total_area_without_floor }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Thai Rft</th>
                                                    <td>{{ $glassConfigure->total_rft_with_floor }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Unit Section Name</th>
                                                    <th>Length</th>
                                                    <th>Height/Width</th>
                                                    <th>Quantity</th>
                                                    <th>Sub Total Area</th>
                                                    <th>Sub Total Rft</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalRft = 0;
                                                    @endphp
                                                @foreach($glassConfigure->grillGlassTilesConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->unitSection->name }}</td>
                                                        <td>{{ $product->length }}</td>
                                                        <td> {{ $product->height }}</td>
                                                        <td> {{ number_format($product->quantity, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_area, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_rft, 2) }}</td>
                                                    </tr>
                                                    @php
                                                        $totalRft += $product->sub_total_rft;
                                                    @endphp
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th class="text-right" colspan="4">Single Floor Total</th>
                                                    <td> {{ number_format($glassConfigure->total_area_without_floor, 2) }} Sft</td>
                                                    <td> {{ number_format($totalRft, 2) }} Rft</td>

                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4">All Floor Total</th>
                                                        <td> {{ number_format($glassConfigure->total_area_with_floor, 2) }} Sft</td>
                                                        <td> {{ number_format($totalRft, 2) }} Rft</td>

                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4">Total Glass and Thai Cost</th>
                                                        <td>৳ {{ number_format($glassConfigure->total_grill_cost, 2) }} Taka</td>
                                                        <td>৳ {{ number_format($glassConfigure->total_thai_cost, 2) }} Taka</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($tilesConfigures)
                                @foreach($tilesConfigures as $tilesConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>
                                                Tiles Configure
                                                </strong></h3>
                                            <h4><u><i><strong>
                                                    Tiles
                                                    Configure No: {{$tilesConfigure->grill_glass_tiles_configure_no}}
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Tiles Configure No.</th>
                                                    <td>{{ $tilesConfigure->grill_glass_tiles_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tiles Number of Floor No.
                                                    </th>
                                                    <td>{{ $tilesConfigure->floor_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tiles Configure Date:</th>
                                                    <td>{{ $tilesConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tiles Configure Names:</th>
                                                    <td>{{ $tilesConfigure->configure_type }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tiles Size:</th>
                                                    <td>{{ $tilesConfigure->tiles_size }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $tilesConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Tiles Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $tilesConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor</th>
                                                    <td>{{ $tilesConfigure->estimateFloor->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor Unit</th>
                                                    <td>{{ $tilesConfigure->estimateFloorUnit->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Tiles Area(Single Floor)</th>
                                                    <td>{{ $tilesConfigure->total_area_without_floor }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Tiles Area(All Floor)</th>
                                                    <td>{{ $tilesConfigure->total_area_with_floor }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Unit Section Name</th>
                                                    <th>Length</th>
                                                    <th>Height/Width</th>
                                                    <th>Quantity</th>
                                                    <th>Sub Total Area</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($tilesConfigure->grillGlassTilesConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->unitSection->name }}</td>
                                                        <td>{{ $product->length }}</td>
                                                        <td> {{ $product->height }}</td>
                                                        <td> {{ number_format($product->quantity, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_area, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th class="text-right" colspan="4">Single Floor Total</th>
                                                    @if($tilesConfigure->configure_type == 1)
                                                    <td> {{ number_format($tilesConfigure->total_area_without_floor, 2) }} KG</td>
                                                    @else
                                                        <td> {{ number_format($tilesConfigure->total_area_without_floor, 2) }} Sft</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4">All Floor Total</th>
                                                    @if($tilesConfigure->configure_type == 1)
                                                        <td> {{ number_format($tilesConfigure->total_area_with_floor, 2) }} KG</td>
                                                    @else
                                                        <td> {{ number_format($tilesConfigure->total_area_with_floor, 2) }} Sft</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4">Total Cost</th>
                                                    @if($tilesConfigure->configure_type == 1)
                                                        <td>৳ {{ number_format($tilesConfigure->total_grill_cost, 2) }} Taka</td>
                                                    @else
                                                        <td>৳ {{ number_format($tilesConfigure->total_tiles_glass_cost, 2) }} Taka</td>
                                                    @endif
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($sandFillings)
                                @foreach($sandFillings as $sandFilling)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h4><u><i><strong>
                                                Sand Filling Configure
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <h3><b><u>Sand Filling Calculation</u></b></h3>
                                                <thead>
                                                <tr>
                                                    <th>Length</th>
                                                    <th>Width</th>
                                                    <th>Height</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Volume</th>
                                                    <th>Sub Total Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalArea = 0;
                                                        $totalPrice = 0;
                                                    @endphp
                                                @foreach($sandFilling->sandFillingConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ number_format($product->length, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->width, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->height, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->quantity, 2) }}</td>
                                                        <td>{{ number_format($product->unit_price, 2) }} Taka</td>
                                                        <td>{{ number_format($product->total_area, 2) }} Cft</td>
                                                        <td>{{ number_format($product->total_price, 2) }} Taka</td>
                                                    </tr>
                                                    @php
                                                        $totalArea += $product->total_area;
                                                        $totalPrice += $product->total_price;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <th class="text-right" colspan="5">Total</th>
                                                        <td><b>৳ {{ number_format($totalArea, 2) }} Cft</b></td>
                                                        <td><b>৳ {{ number_format($totalPrice, 2) }} Taka</b></td>
                                                </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($bricksSolings)
                                @foreach($bricksSolings as $bricksSoling)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h4><u><i><strong>
                                                Bricks Soling Configure
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <h3><b><u>Bricks Soling Calculation</u></b></h3>
                                                <thead>
                                                <tr>
                                                    <th>Length</th>
                                                    <th>Width</th>
                                                    <th>Nos</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Volume</th>
                                                    <th>Sub Total Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalArea = 0;
                                                        $totalPrice = 0;
                                                    @endphp
                                                @foreach($bricksSoling->bricksSolingConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ number_format($product->length, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->width, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->height, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->unit_price, 2) }} Taka</td>
                                                        <td>{{ number_format($product->total_area, 2) }} Cft</td>
                                                        <td>{{ number_format($product->total_price, 2) }} Taka</td>
                                                    </tr>
                                                    @php
                                                        $totalArea += $product->total_area;
                                                        $totalPrice += $product->total_price;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <th class="text-right" colspan="4">Total</th>
                                                        <td><b>৳ {{ number_format($totalArea, 2) }} Cft</b></td>
                                                        <td><b>৳ {{ number_format($totalPrice, 2) }} Taka</b></td>
                                                </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($paintConfigures)
                                @foreach($paintConfigures as $paintConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Paint</strong></h3>
                                            <h4><u><i><strong>Paint Configure No: {{$paintConfigure->paint_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Paint Configure No.</th>
                                                    <td>{{ $paintConfigure->paint_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Paint Floor Quantity</th>
                                                    <td>{{ $paintConfigure->floor_number }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Total Paint Liter Without Floor</th>
                                                    <td>{{ $paintConfigure->total_paint_liter_without_floor }} Liter</td>
                                                </tr>

                                                <tr>
                                                    <th>Total Paint Liter With Floor</th>
                                                    <td>{{ $paintConfigure->total_paint_liter_with_floor }} Liter</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Seller Liter Without Floor</th>
                                                    <td>{{ $paintConfigure->total_seller_liter_without_floor }} Liter</td>
                                                </tr>

                                                <tr>
                                                    <th>Total Seller Liter With Floor</th>
                                                    <td>{{ $paintConfigure->total_seller_liter_with_floor }} Liter</td>
                                                </tr>
                                                <tr>
                                                    <th>Paint Configure Date</th>
                                                    <td>{{ $paintConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $paintConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">Paint Info</th>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Project</th>
                                                    <td>{{ $paintConfigure->project->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor</th>
                                                    <td>{{ $paintConfigure->estimateFloor->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estimate Floor Unit</th>
                                                    <td>{{ $paintConfigure->estimateFloorUnit->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Paint Area(Single Floor)</th>
                                                    <td>{{ $paintConfigure->total_area_without_floor }} Cft</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Paint Area(All Floor)</th>
                                                    <td>{{ $paintConfigure->total_area_with_floor }} Cft</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Unit Section Name</th>
                                                    <th>Wall Direction</th>
                                                    <th>Paint Type</th>
                                                    <th>Length</th>
                                                    <th>Height/Width</th>
                                                    <th>Side</th>
                                                    <th>Sub Total Deduction</th>
                                                    <th>Sub Total Area</th>
                                                    <th>Sub Total Paint Liter</th>
                                                    <th>Sub Total Seller Liter</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($paintConfigure->paintConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->unitSection->name }}</td>
                                                        <td>
                                                            @if($product->wall_direction == 1)
                                                                East
                                                            @elseif($product->wall_direction == 2)
                                                                West
                                                            @elseif($product->wall_direction == 3)
                                                                North
                                                            @elseif($product->wall_direction == 4)
                                                                South
                                                            @else
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($product->paint_type == 1)
                                                                Wheathar Code
                                                            @else
                                                                Dis-Temper
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->length }}</td>
                                                        <td> {{ $product->height }}</td>
                                                        <td> {{ $product->side }}</td>
                                                        <td> {{ number_format($product->sub_total_deduction, 2) }} Cft</td>
                                                        <td> {{ number_format($product->sub_total_area, 2) }} Cft</td>
                                                        <td> {{ number_format($product->sub_total_paint_liter, 2) }} Liter</td>
                                                        <td> {{ number_format($product->sub_total_seller_liter, 2) }} Liter</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th class="text-right" colspan="7">Single Floor Total</th>
                                                    <td> {{ number_format($paintConfigure->total_area_without_floor, 2) }} Cft</td>
                                                    <td> {{ number_format($paintConfigure->total_paint_liter_without_floor, 2) }} Liter</td>
                                                    <td> {{ number_format($paintConfigure->total_seller_liter_without_floor, 2) }} Liter</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="7">All Floor Total</th>
                                                    <td> {{ number_format($paintConfigure->total_area_with_floor, 2) }} Cft</td>
                                                    <td> {{ number_format($paintConfigure->total_paint_liter_with_floor, 2) }} Liter</td>
                                                    <td> {{ number_format($paintConfigure->total_seller_liter_with_floor, 2) }} Liter</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($earthWorkConfigures)
                                @foreach($earthWorkConfigures as $earthWorkConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h4><u><i><strong>
                                                @if ($earthWorkConfigure->earth_work_type == 1)
                                                    Earth Cutting Configure
                                                @else
                                                    Earth Filling Configure
                                                @endif
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                               @if ($earthWorkConfigure->earth_work_type == 1)
                                                    <h3><b><u>Earth Working Calculation For Earth Cutting</u></b></h3>
                                               @else
                                                    <h3><b><u>Earth Working Calculation For Earth Filling</u></b></h3>
                                               @endif
                                                <thead>
                                                <tr>
                                                    <th>Length</th>
                                                    <th>Width</th>
                                                    <th>Height</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Volume</th>
                                                    <th>Sub Total Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalArea = 0;
                                                        $totalPrice = 0;
                                                    @endphp
                                                @foreach($earthWorkConfigure->earthWorkConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ number_format($product->length, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->width, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->height, 2) ?? ''}}</td>
                                                        <td>{{ number_format($product->quantity, 2) }}</td>
                                                        <td>{{ number_format($product->unit_price, 2) }} Taka</td>
                                                        <td>{{ number_format($product->total_area, 2) }} Cft</td>
                                                        <td>{{ number_format($product->total_price, 2) }} Taka</td>
                                                    </tr>
                                                    @php
                                                        $totalArea += $product->total_area;
                                                        $totalPrice += $product->total_price;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <th class="text-right" colspan="5">Total</th>
                                                        <td><b>৳ {{ number_format($totalArea, 2) }} Cft</b></td>
                                                        <td><b>৳ {{ number_format($totalPrice, 2) }} Taka</b></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($extraCostings)
                                @foreach($extraCostings as $extraCosting)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h4><u><i><strong>
                                                Extra Costing
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <h3><b><u>Extra Costing Calculation</u></b></h3>
                                                <thead>
                                                <tr>
                                                    <th colspan="4">Extra C. Product</th>
                                                    <th colspan="4">Costing Amount(Per Unit)</th>
                                                    <th colspan="4">Unit</th>
                                                    <th colspan="4">Total Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalUnit = 0;
                                                        $totalPrice = 0;
                                                    @endphp
                                                @foreach($extraCosting->products as $product)
                                                    <tr>
                                                        <td colspan="4">{{ $product->name ?? ''}}</td>
                                                        <td colspan="4">{{ number_format($product->costing_amount_per_unit, 2) ?? ''}}</td>
                                                        <td colspan="4">{{ $product->unit_id ?? ''}}</td>
                                                        <td colspan="4"> {{ number_format($product->total, 2) }} Taka</td>
                                                    </tr>
                                                    @php
                                                        $totalUnit += $product->unit_id;
                                                        $totalPrice += $product->total;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <th class="text-right" colspan="4"></th>
                                                    <th class="text-right" colspan="4">Total</th>
                                                        <td colspan="4"><b>৳ {{ number_format($totalUnit, 2) }} Cft</b></td>
                                                        <td colspan="4"><b>৳ {{ number_format($totalPrice, 2) }} Taka</b></td>
                                                </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($mobilizations)
                                @foreach($mobilizations as $mobilization)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h4><u><i><strong>
                                                Mobilization Cost
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <h3><b><u>Mobilization Calculation</u></b></h3>
                                                <thead>
                                                <tr>
                                                    <th colspan="4">Estimate Product</th>
                                                    <th colspan="4">Unit</th>
                                                    <th colspan="4">Quantity</th>
                                                    <th colspan="4">Remarks</th>
                                                    <th colspan="4">Unit Per Amount</th>
                                                    <th colspan="4">Total Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalUnit = 0;
                                                        $totalQuantity = 0;
                                                        $totalAmount = 0;
                                                        $totalPrice = 0;
                                                    @endphp
                                                @foreach($mobilization->products as $product)
                                                    <tr>
                                                        <td colspan="4">{{ $product->product->name ?? ''}}</td>
                                                        <td colspan="4">{{ $product->unit ?? ''}}</td>
                                                        <td colspan="4">{{ $product->quantity ?? ''}}</td>
                                                        <td colspan="4">{{ $product->remarks ?? ''}}</td>
                                                        <td colspan="4"> {{ number_format($product->amount, 2) }} Taka</td>
                                                        <td colspan="4">{{ number_format($product->unit * $product->quantity * $product->amount, 2)}}</td>
                                                    </tr>
                                                    @php
                                                        $totalUnit += $product->unit;
                                                        $totalQuantity += $product->quantity;
                                                        $totalAmount += $product->amount;
                                                        $totalPrice += $product->total;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <th class="text-right" colspan="4">Total</th>
                                                    <th colspan="4">{{ number_format($totalUnit, 2) }} Unit</th>
                                                    <th colspan="4">{{ number_format($totalQuantity, 2) }} Quantity</th>
                                                    <th class="text-right" colspan="4"></th>
                                                        <td colspan="4"><b>৳ {{ number_format($totalAmount, 2) }} Cft</b></td>
                                                        <td colspan="4"><b>৳ {{ number_format($mobilization->total, 2) }} Taka</b></td>
                                                </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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

