@extends('layouts.app')

@section('style')

    <style>
        .img-overlay {
            position: absolute;
            left: 0;
            top: 150px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.2;
        }

        .img-overlay img {
            width: 400px;
            height: auto;
        }
    </style>
@endsection

@section('title')
    Sale Inventory View Report
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
                    <form action="{{ route('sale_inventory.floor_wise_view') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project</label>
                                    <select class="form-control" name="project" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option {{ request()->get('project') == $project->project_id ? 'selected' : '' }} value="{{$project->project_id}}">{{$project->project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
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
            @if($floorWiseProjects)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%;margin-top: 20px" src="{{ asset('img/head_logo.jpeg') }}">
                                </div>
                                <div class="col-xs-8 text-left">
                                    <div style="padding:10px; width:100%; text-align:center;">
                                        <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                                        <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                                        <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                                        <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                    </div>
                                </div>
                                <div class="col-xs-12 text-left">
                                    <h4><strong>Receipt</strong></h4>
                                </div>
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">{{$projectName->project->name}}</th>
                            </table>

                            <div style="clear: both">
                                <div class="img-overlay">
                                    <img src="{{ asset('img/logo.png') }}">
                                </div>

                                <table class="table table-bordered" style="width:100%; float:left">
                                    @foreach($floorWiseProjects as $key => $floorWiseProject)
                                        <tr>
                                            <th class="text-left">{{$floorWiseProject->floor->name}}</th>

                                            @foreach($floorWiseProject->floor->flat as $key => $item)
                                                <th class="text-left flat-item">
                                                    {{$item->name}}
                                                    @if(count($item->saleOrders) > 0)
                                                        @foreach($item->saleOrders as $key => $saleOrder)
                                                            @if($saleOrder->flat_id == $item->id)
                                                                ({{$saleOrder->client->name??''}})(<span class="label label-danger">Sold</span>)
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="label label-success">Available</span>
                                                    @endif

                                                </th>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection

@section('script')
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
