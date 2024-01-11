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
    Estimation & Costing Summary
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
                    <form action="{{ route('estimation_costing_summary') }}">
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
                                    <h4><strong>Estimation & Costing Summary</strong></h4>
                                </div>
                            </div>
                            <hr>
                            @if($pileConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Pile Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Pile Quantity</th>
                                                <th class="text-center">Total Bar/Rod Kg</th>
                                                <th class="text-center">Total Cement</th>
                                                <th class="text-center">Total Sands</th>
                                                <th class="text-center">Total Aggregate</th>
                                                <th class="text-center">Total Picked</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$pileConfigures->sum('pile_quantity')}}</td>
                                                <td class="text-center">{{$pileConfigures->sum('total_kg')}} Kg</td>
                                                <td class="text-center">{{$pileConfigures->sum('total_cement_bag')}} Bag</td>
                                                <td class="text-center">{{$pileConfigures->sum('total_sands')}} Cft</td>
                                                <td class="text-center">{{$pileConfigures->sum('total_aggregate')}} Cft</td>
                                                <td class="text-center">{{$pileConfigures->sum('total_picked')}} Pcs</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">{{$pileConfigures->sum('pile_quantity')}}</td>
                                                <td class="text-center">৳ {{number_format($pileConfigures->sum('total_pile_bar_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($pileConfigures->sum('total_pile_cement_bag_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($pileConfigures->sum('total_pile_sands_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($pileConfigures->sum('total_pile_aggregate_price')),2}} Taka</td>
                                                <td class="text-center">৳ {{number_format($pileConfigures->sum('total_pile_picked_price')),2}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($beamConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Beam Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Beam Quantity</th>
                                                <th class="text-center">Total Bar/Rod Kg</th>
                                                <th class="text-center">Total Cement</th>
                                                <th class="text-center">Total Sands</th>
                                                <th class="text-center">Total Aggregate</th>
                                                <th class="text-center">Total Picked</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$beamConfigures->sum('beam_quantity')}}</td>
                                                <td class="text-center">{{$beamConfigures->sum('total_kg')}} Kg</td>
                                                <td class="text-center">{{$beamConfigures->sum('total_cement_bag')}} Bag</td>
                                                <td class="text-center">{{$beamConfigures->sum('total_sands')}} Cft</td>
                                                <td class="text-center">{{$beamConfigures->sum('total_aggregate')}} Cft</td>
                                                <td class="text-center">{{$beamConfigures->sum('total_picked')}} Pcs</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">{{$beamConfigures->sum('beam_quantity')}}</td>
                                                <td class="text-center">৳ {{number_format($beamConfigures->sum('total_beam_bar_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($beamConfigures->sum('total_beam_cement_bag_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($beamConfigures->sum('total_beam_sands_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($beamConfigures->sum('total_beam_aggregate_price')),2}} Taka</td>
                                                <td class="text-center">৳ {{number_format($beamConfigures->sum('total_beam_picked_price')),2}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($columnConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Column Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Column Quantity</th>
                                                <th class="text-center">Total Bar/Rod Kg</th>
                                                <th class="text-center">Total Cement</th>
                                                <th class="text-center">Total Sands</th>
                                                <th class="text-center">Total Aggregate</th>
                                                <th class="text-center">Total Picked</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$columnConfigures->sum('column_quantity')}}</td>
                                                <td class="text-center">{{$columnConfigures->sum('total_kg')}} Kg</td>
                                                <td class="text-center">{{$columnConfigures->sum('total_cement_bag')}} Bag</td>
                                                <td class="text-center">{{$columnConfigures->sum('total_sands')}} Cft</td>
                                                <td class="text-center">{{$columnConfigures->sum('total_aggregate')}} Cft</td>
                                                <td class="text-center">{{$columnConfigures->sum('total_picked')}} Pcs</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">{{$columnConfigures->sum('column_quantity')}}</td>
                                                <td class="text-center">৳ {{number_format($columnConfigures->sum('total_column_bar_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($columnConfigures->sum('total_column_cement_bag_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($columnConfigures->sum('total_column_sands_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($columnConfigures->sum('total_column_aggregate_price')),2}} Taka</td>
                                                <td class="text-center">৳ {{number_format($columnConfigures->sum('total_column_picked_price')),2}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($commonConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Slab/P.Cap/Mat/R.Wall Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Slab/P.Cap/Mat/R.Wall Quantity</th>
                                                <th class="text-center">Total Bar/Rod Kg</th>
                                                <th class="text-center">Total Cement</th>
                                                <th class="text-center">Total Sands</th>
                                                <th class="text-center">Total Aggregate</th>
                                                <th class="text-center">Total Picked</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$commonConfigures->sum('costing_segment_quantity')}}</td>
                                                <td class="text-center">{{$commonConfigures->sum('total_kg')}} Kg</td>
                                                <td class="text-center">{{$commonConfigures->sum('total_cement_bag')}} Bag</td>
                                                <td class="text-center">{{$commonConfigures->sum('total_sands')}} Cft</td>
                                                <td class="text-center">{{$commonConfigures->sum('total_aggregate')}} Cft</td>
                                                <td class="text-center">{{$commonConfigures->sum('total_picked')}} Pcs</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">{{$commonConfigures->sum('costing_segment_quantity')}}</td>
                                                <td class="text-center">৳ {{number_format($commonConfigures->sum('total_common_bar_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($commonConfigures->sum('total_common_cement_bag_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($commonConfigures->sum('total_common_sands_price'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($commonConfigures->sum('total_common_aggregate_price')),2}} Taka</td>
                                                <td class="text-center">৳ {{number_format($commonConfigures->sum('total_common_picked_price')),2}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($bricksConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Bricks Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Floor Quantity</th>
                                                <th class="text-center">Total Bricks</th>
                                                <th class="text-center">Total Cement</th>
                                                <th class="text-center">Total Sands</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$bricksConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">{{$bricksConfigures->sum('total_bricks')}} Pcs</td>
                                                <td class="text-center">{{$bricksConfigures->sum('total_cement_bag')}} Bag</td>
                                                <td class="text-center">{{$bricksConfigures->sum('total_sands')}} Cft</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">{{$bricksConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">৳ {{number_format($bricksConfigures->sum('total_bricks_cost'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($bricksConfigures->sum('total_bricks_cement_cost'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($bricksConfigures->sum('total_bricks_sands_cost'),2)}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($plasterConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Plaster Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Floor Quantity</th>
                                                <th class="text-center">Total Cement</th>
                                                <th class="text-center">Total Sands</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$plasterConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">{{$plasterConfigures->sum('total_cement_bag')}} Bag</td>
                                                <td class="text-center">{{$plasterConfigures->sum('total_sands')}} Cft</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">{{$plasterConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">৳ {{number_format($plasterConfigures->sum('total_plaster_cement_cost'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($plasterConfigures->sum('total_plaster_sands_cost'),2)}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($grillConfigures || $glassConfigures || $tilesConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Grill/Glass/Tiles Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Floor Quantity</th>
                                                <th class="text-center">Total Area</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">Grill</td>
                                                <td class="text-center">{{$grillConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">{{$grillConfigures->sum('total_area_with_floor')}} Kg</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">Grill</td>
                                                <td class="text-center">{{$grillConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">৳ {{number_format($grillConfigures->sum('total_grill_cost'),2)}} Taka</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">Glass</td>
                                                <td class="text-center">{{$glassConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">{{$glassConfigures->sum('total_area_with_floor')}} Sft</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">Glass</td>
                                                <td class="text-center">{{$glassConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">৳ {{number_format($glassConfigures->sum('total_tiles_glass_cost'),2)}} Taka</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">Tiles</td>
                                                <td class="text-center">{{$tilesConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">{{$tilesConfigures->sum('total_area_with_floor')}} Sft</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">Tiles</td>
                                                <td class="text-center">{{$tilesConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">৳ {{number_format($tilesConfigures->sum('total_tiles_glass_cost'),2)}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($paintConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Paint Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Floor Quantity</th>
                                                <th class="text-center">Total Paint</th>
                                                <th class="text-center">Total Seller</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$paintConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">{{$paintConfigures->sum('total_paint_liter_with_floor')}} Liter</td>
                                                <td class="text-center">{{$paintConfigures->sum('total_seller_liter_with_floor')}} Liter</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">{{$paintConfigures->sum('floor_number')}}</td>
                                                <td class="text-center">৳ {{number_format($paintConfigures->sum('total_paint_cost'),2)}} Taka</td>
                                                <td class="text-center">৳ {{number_format($paintConfigures->sum('total_seller_cost'),2)}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($earthWorkConfigures)
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="7" class="text-center">Paint Estimate And Costing</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Total Area</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Estimation</td>
                                                <td class="text-center">{{$earthWorkConfigures->sum('total_area')}} Sft</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Costing</td>
                                                <td class="text-center">৳ {{$earthWorkConfigures->sum('total_price')}} Taka</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
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

