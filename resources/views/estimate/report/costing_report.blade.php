@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
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
                                    <select class="form-control" name="project" required>
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
                                    <input class="btn btn-primary form-control" type="submit" value="Submit">
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
            @if($projects)
                <section class="panel">

                    <div class="panel-body">
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
                            @foreach ($costings as $costing)
                                <div style="clear: both">
                                    @if ($costing->costing_type_id == 1)
                                        <h4 class="text-center">Estimation and Costing of Slab</h4>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-center">Slab Size(max)</th>
                                                <th class="text-center">Total Slab</th>
                                                <th class="text-center">Slab Ratio</th>
                                                <th class="text-center">Admixture per bag</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">{{$costing->size}}</td>
                                                <td class="text-center">{{$costing->type_quantity}}</td>
                                                <td class="text-center">{{$costing->ratio}}</td>
                                                <td class="text-center">{{$costing->admixture_per_bag}}</td>
                                            </tr>
                                        </table>
                                        <table class="table table-bordered" style="width:100%; float:left">
                                            <tr>
                                                <th class="text-center">Volume(Cft)</th>
                                                <th class="text-center">Reinforcement(kg)</th>
                                                <th class="text-center">Cement(Bag)</th>
                                                <th class="text-center">Sand(Cft)</th>
                                                <th class="text-center">Brick Chip(Cft)</th>
                                                <th class="text-center">Admixture(Liter)</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">{{$costing->volume}}</td>

                                                @foreach($costing->estimateProducts as $estimateProduct)
                                                @if ($estimateProduct->estimate_product_id == 1)
                                                    <td class="text-center">{{$estimateProduct->costing_amount??''}}</td>
                                                @endif
                                                @endforeach
                                                @foreach($costing->estimateProducts as $estimateProduct)
                                                @if ($estimateProduct->estimate_product_id == 2)
                                                    <td class="text-center">{{$estimateProduct->quantity??''}}</td>
                                                @endif
                                                @endforeach
                                                @foreach($costing->estimateProducts as $estimateProduct)
                                                @if ($estimateProduct->estimate_product_id == 3)
                                                    <td class="text-center">{{$estimateProduct->costing_amount??''}}</td>
                                                @endif
                                                @endforeach
                                                @foreach($costing->estimateProducts as $estimateProduct)
                                                @if ($estimateProduct->estimate_product_id == 4)
                                                    <td class="text-center">{{$estimateProduct->costing_amount??''}}</td>
                                                @endif
                                                @endforeach
                                                @foreach($costing->estimateProducts as $estimateProduct)
                                                    @if ($estimateProduct->estimate_product_id == 3)
                                                        <td class="text-center">{{($estimateProduct->quantity * $costing->admixture_per_bag) /1000}} </td>
                                                    @endif

                                                @endforeach
                                            </tr>
                                        </table>
                                        @elseif ($costing->costing_type_id == 2)
                                        <h4 class="text-center">Estimation and Costing of Beam</h4>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-center">Beam Size(max)</th>
                                                <th class="text-center">Total Beam</th>
                                                <th class="text-center">Beam Ratio</th>
                                                <th class="text-center">Admixture per bag</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">{{$costing->size}}</td>
                                                <td class="text-center">{{$costing->type_quantity}}</td>
                                                <td class="text-center">{{$costing->ratio}}</td>
                                                <td class="text-center">{{$costing->admixture_per_bag}}</td>
                                            </tr>
                                        </table>
                                        <table class="table table-bordered" style="width:100%; float:left">
                                            <tr>
                                                <th class="text-center">Volume(Cft)</th>
                                                <th class="text-center">Reinforcement(kg)</th>
                                                <th class="text-center">Cement(Bag)</th>
                                                <th class="text-center">Sand(Cft)</th>
                                                <th class="text-center">Brick Chip(Cft)</th>
                                                <th class="text-center">Admixture(Liter)</th>
                                            </tr>
                                            @foreach($costing->estimateProducts as $estimateProduct)
                                                <tr>
                                                    <td class="text-center">{{$costing->volume}}</td>
                                                    <td class="text-center">{{$estimateProduct->costing_amount}}</td>
                                                    <td class="text-center">{{$estimateProduct->quantity}}</td>
                                                    <td class="text-center">{{$estimateProduct->costing_amount}}</td>
                                                    <td class="text-center">{{$estimateProduct->costing_amount}}</td>
                                                    <td class="text-center">{{($estimateProduct->quantity * $costing->admixture_per_bag) /1000}} </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                </div>
                            @endforeach
                            <hr>
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

    <script>
        $(function () {
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
