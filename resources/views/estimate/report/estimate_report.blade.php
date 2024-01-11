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
                                            @foreach($pileConfigure->pileConfigureProducts as $product)
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
                                            @endforeach
                                            </tbody>
                                            <tr>
                                                <th class="text-right" colspan="7">Total Ton/KG</th>
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
                                                <th>Tie</th>
                                                <td>{{ $beamConfigure->tie_length }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tie</th>
                                                <td>{{ $beamConfigure->tie_width }}</td>
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
                                                        <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>

                                            <tr>
                                                <th colspan="6" class="text-right">Main Bar Total</th>
                                                <th> {{ number_format($mainTotalKg, 2) }}</th>
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
                                                        <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>

                                            <tr>
                                                <th colspan="6" class="text-right" >Extra Bar Total</th>
                                                <td> {{ number_format($extraTotalKg, 2) }}</td>
                                                <td> {{ number_format($extraTotalTon, 3) }}</td>
                                            </tr>

                                            <tr>
                                                <th colspan="6" class="text-right" >Total Ton/KG</th>
                                                <td> {{ number_format($beamConfigure->total_kg, 2) }}</td>
                                                <td> {{ number_format($beamConfigure->total_ton, 3) }}</td>
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
                                                <h4>Straight Rod Description</h4>
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

                            @if($commonConfigures)
                                @foreach($commonConfigures as $commonConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>{{$commonConfigure->costingSegment->name}}</strong></h3>
                                            <h4><u><i><strong>{{$commonConfigure->costingSegment->name}} Configure No: {{$commonConfigure->common_configure_no}}</strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Common Configure No.</th>
                                                    <td>{{ $commonConfigure->common_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Common Configure Date</th>
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
                                                <tr>
                                                    <th>Total Piked</th>
                                                    <td>{{ $commonConfigure->total_picked }} Pcs</td>
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

                            @if($bricksConfigures)
                                @foreach($bricksConfigures as $bricksConfigure)

                                    <div class="row">
                                        <div style="padding:5px; width:100%; text-align:center;">
                                            <h3><strong>Bricks</strong></h3>
                                            <h4><u><i><strong>Bricks Configure No: {{$bricksConfigure->bricks_configure_no}}</strong></i></u></h4>
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
                                                    <th class="text-right" colspan="6">Total</th>
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
                                                    <th>Plaster Configure No.</th>
                                                    <td>{{ $plasterConfigure->plaster_configure_no }}</td>
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
                                                    <th>Project Name</th>
                                                    <th>Floor</th>
                                                    <th>Unit</th>
                                                    <th>Unit Section</th>
                                                    <th>Plaster Area</th>
                                                    <th>Plaster Side</th>
                                                    <th>Plaster Thickness</th>
                                                    <th>Sub Total Area</th>
                                                    <th>Sub Total Cement</th>
                                                    <th>Sub Total Sands</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($plasterConfigure->plasterConfigureProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->bricksConfigureProduct->project->name }}</td>
                                                        <td>{{ $product->bricksConfigureProduct->estimateFloor->name }}</td>
                                                        <td>{{ $product->bricksConfigureProduct->estimateFloorUnit->name }}</td>
                                                        <td>{{ $product->bricksConfigureProduct->unitSection->plaster_area }}</td>
                                                        <td>
                                                            @if($product->bricksConfigureProduct->wall_direction == 1)
                                                                East
                                                            @elseif($product->bricksConfigureProduct->wall_direction == 2)
                                                                West
                                                            @elseif($product->bricksConfigureProduct->wall_direction == 3)
                                                                North
                                                            @elseif($product->bricksConfigureProduct->wall_direction == 4)
                                                                South
                                                            @else
                                                            @endif
                                                        </td>
                                                        <td> {{ $product->plaster_side }}</td>
                                                        <td> {{ $product->plaster_thickness }}</td>
                                                        <td> {{ number_format($product->sub_total_dry_area, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_cement, 2) }}</td>
                                                        <td> {{ number_format($product->sub_total_sands, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tr>
                                                    <th class="text-right" colspan="7">Total</th>
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
                                                    @if($grillGlassTilesConfigure->configure_type == 1)
                                                    Grill
                                                    @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                    Glass
                                                    @else
                                                    Tiles
                                                    @endif
                                                </strong></h3>
                                            <h4><u><i><strong>
                                                    @if($grillGlassTilesConfigure->configure_type == 1)
                                                        Grill
                                                    @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                        Glass
                                                    @else
                                                        Tiles
                                                    @endif
                                                    Configure No: {{$grillGlassTilesConfigure->grill_glass_tiles_configure_no}}
                                            </strong></i></u></h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>@if($grillGlassTilesConfigure->configure_type == 1)
                                                            Grill Configure No.
                                                        @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                            Glass Configure No.
                                                        @else
                                                            Tiles Configure No.
                                                        @endif

                                                    </th>
                                                    <td>{{ $grillGlassTilesConfigure->grill_glass_tiles_configure_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@if($grillGlassTilesConfigure->configure_type == 1)
                                                            Grill Configure No.
                                                        @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                            Glass Configure No.
                                                        @else
                                                            Tiles Configure No.
                                                        @endif

                                                    </th>
                                                    <td>{{ $grillGlassTilesConfigure->floor_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@if($grillGlassTilesConfigure->configure_type == 1)
                                                            Grill Configure Date
                                                        @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                            Glass Configure Date
                                                        @else
                                                            Tiles Configure Date
                                                        @endif

                                                    </th>
                                                    <td>{{ $grillGlassTilesConfigure->date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Note </th>
                                                    <td>{{ $grillGlassTilesConfigure->note??'' }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center">
                                                        @if($grillGlassTilesConfigure->configure_type == 1)
                                                            Grill Info
                                                        @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                            Glass Info
                                                        @else
                                                            Tiles Info
                                                        @endif
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
                                                    <th>
                                                        @if($grillGlassTilesConfigure->configure_type == 1)
                                                            Total Grill KG(Single Floor)
                                                        @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                            Total Glass Area(Single Floor)
                                                        @else
                                                            Total Tiles Area(Single Floor)
                                                        @endif
                                                    </th>
                                                    <td>{{ $grillGlassTilesConfigure->total_area_without_floor }}</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        @if($grillGlassTilesConfigure->configure_type == 1)
                                                            Total Grill KG(All Floor)
                                                        @elseif($grillGlassTilesConfigure->configure_type == 2)
                                                            Total Glass Area(All Floor)
                                                        @else
                                                            Total Tiles Area(All Floor)
                                                        @endif
                                                    </th>
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
                                                    @if($grillGlassTilesConfigure->configure_type == 1)
                                                        <td> {{ number_format($grillGlassTilesConfigure->total_area_without_floor, 2) }} KG</td>
                                                    @else
                                                        <td> {{ number_format($grillGlassTilesConfigure->total_area_without_floor, 2) }} Area</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4">All Floor Total</th>
                                                    @if($grillGlassTilesConfigure->configure_type == 1)
                                                        <td> {{ number_format($grillGlassTilesConfigure->total_area_with_floor, 2) }} KG</td>
                                                    @else
                                                        <td> {{ number_format($grillGlassTilesConfigure->total_area_with_floor, 2) }} Area</td>
                                                    @endif
                                                </tr>
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

                                <div class="row">
                                    <div style="padding:5px; width:100%; text-align:center;">
                                        <h3><strong>Earth Work</strong></h3>
{{--                                            <h4><u><i><strong>Paint Configure No: {{$earthWorkConfigure->paint_configure_no}}</strong></i></u></h4>--}}
                                    </div>
                                </div>
                                <hr>
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Estimate Project</th>
                                        <th>Length</th>
                                        <th>Width</th>
                                        <th>Height</th>
                                        <th>Total Volume</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($earthWorkConfigures as $earthWorkConfigure)
                                        <tr>
                                            <td>{{$earthWorkConfigure->project->name}}</td>
                                            <td>{{$earthWorkConfigure->length}}</td>
                                            <td>{{$earthWorkConfigure->width}}</td>
                                            <td>{{$earthWorkConfigure->height}}</td>
                                            <td>{{$earthWorkConfigure->total_area}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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

