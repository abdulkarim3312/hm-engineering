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

    Cost Calculation
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
                    <form action="{{ route('cost_calculation') }}">
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
                                    <label>Segment</label>
                                    <select class="form-control select2" name="segment" id="segment">
                                        <option value="">Select Segment</option>
                                    </select>
                                </div>
                            </div>

                            @foreach($estimateProducts as $estimateProduct)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Estimate Product {{$estimateProduct->estimateProduct->name}}</label>
                                        <select class="form-control select2" name="estimate_product_type[]" required>
                                            <option value="">Select {{$estimateProduct->estimateProduct->name}} Type</option>

                                            @foreach(\App\Models\EstimateProductType::where('estimate_product_id',$estimateProduct->estimate_product_id)->get() as $estimateProduct)
                                                <option {{ (collect(request()->get('estimate_product_type'))->contains($estimateProduct->id)) ? 'selected':''}}
                                                        value="{{$estimateProduct->id}}">{{$estimateProduct->name}}-{{$estimateProduct->unit_price}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
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
            @if($assignSegmentItems)
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
                                    <h4><strong>Segment Wise Cost Calculation</strong></h4>
                                </div>
                            </div>
                            <hr>
                            @foreach($assignSegmentItems as $assignSegmentItem)
                                @php
                                    $segmentTotal = 0;
                                @endphp
                                <div class="row">
                                    <div style="padding:5px; width:100%; text-align:center;">
                                        <h4><strong>Segment Name: {{$assignSegmentItem->segmentConfigure->costingSegment->name }}</strong></h4>
                                        <h5><strong>Minimum Volume: {{$assignSegmentItem->minimum_volume }}(Square feet)</strong></h5>
                                        <h5><strong>Assign Volume: {{$assignSegmentItem->assign_volume }}(Square feet)</strong></h5>
                                        <h5><strong>Total Segment Quantity: {{$assignSegmentItem->segment_quantity }}</strong></h5>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Costing Product</th>
                                            <th class="text-center">Minimum Quantity</th>
                                            <th class="text-center">Assign Quantity</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-center">
                                            @foreach($assignSegmentItem->assignSegmenProducts as $product)
                                                {{$product->estimateProduct->name}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItem->segmentConfigure->segmentConfigureProducts as $product)
                                                {{$product->minimum_quantity}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItem->assignSegmenProducts as $product)
                                                {{$product->quantity}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItem->assignSegmenProducts as $product)
                                                {{$product->estimateProduct->unit->name}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItem->assignSegmenProducts as $product)
                                                @foreach($estimateProducts as $estimateProduct)
                                                    @if($estimateProduct->estimate_product_id == $product->estimate_product_id)
                                                        ৳{{$estimateProduct->unit_price}}<br><hr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItem->assignSegmenProducts as $product)
                                                @foreach($estimateProducts as $estimateProduct)
                                                    @if($estimateProduct->estimate_product_id == $product->estimate_product_id)
                                                        @php
                                                            $segmentTotal += $product->quantity * $estimateProduct->unit_price;
                                                        @endphp
                                                        ৳{{$product->quantity * $estimateProduct->unit_price}}<br><hr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="5">Segment Total</th>
                                        <th class="text-center">
                                            @php
                                                $grandTotal += $segmentTotal;
                                            @endphp
                                            ৳{{$segmentTotal}}
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                                <hr style="border-top: 2px solid #3425adf7;">
                            @endforeach
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center">Project Total Cost</th>
                                    <th class="text-center">
                                        ৳{{$grandTotal}} Taka
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </section>
            @endif
            @if($assignSegmentItemSingle)
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
                            <hr>
                            <div class="row">
                                <div style="padding:5px; width:100%; text-align:center;">
                                    <h3><strong>Project Name: {{$projectName->name }}</strong></h3>
                                    <h4><strong>Segment Wise Cost Calculation</strong></h4>
                                </div>
                            </div>
                            <hr>

                                @php
                                    $segmentTotal = 0;
                                @endphp
                                <div class="row">
                                    <div style="padding:5px; width:100%; text-align:center;">
                                        <h4><strong>Segment Name: {{$assignSegmentItemSingle->segmentConfigure->costingSegment->name }}</strong></h4>
                                        <h5><strong>Minimum Volume: {{$assignSegmentItemSingle->minimum_volume }}(Square feet)</strong></h5>
                                        <h5><strong>Assign Volume: {{$assignSegmentItemSingle->assign_volume }}(Square feet)</strong></h5>
                                        <h5><strong>Total Segment Quantity: {{$assignSegmentItemSingle->segment_quantity }}</strong></h5>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Costing Product</th>
                                            <th class="text-center">Minimum Quantity</th>
                                            <th class="text-center">Assign Quantity</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-center">
                                            @foreach($assignSegmentItemSingle->assignSegmenProducts as $product)
                                                {{$product->estimateProduct->name}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItemSingle->segmentConfigure->segmentConfigureProducts as $product)
                                                {{$product->minimum_quantity}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItemSingle->assignSegmenProducts as $product)
                                                {{$product->quantity}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItemSingle->assignSegmenProducts as $product)
                                                {{$product->estimateProduct->unit->name}}<br><hr>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItemSingle->assignSegmenProducts as $product)
                                                @foreach($estimateProducts as $estimateProduct)
                                                    @if($estimateProduct->estimate_product_id == $product->estimate_product_id)
                                                        ৳{{$estimateProduct->unit_price}}<br><hr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($assignSegmentItemSingle->assignSegmenProducts as $product)
                                                @foreach($estimateProducts as $estimateProduct)
                                                    @if($estimateProduct->estimate_product_id == $product->estimate_product_id)
                                                        @php
                                                            $segmentTotal += $product->quantity * $estimateProduct->unit_price;
                                                        @endphp
                                                        ৳{{$product->quantity * $estimateProduct->unit_price}}<br><hr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="5">Segment Total</th>
                                        <th class="text-center">
                                            ৳{{$segmentTotal}}
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                                <hr style="border-top: 2px solid #3425adf7;">
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
