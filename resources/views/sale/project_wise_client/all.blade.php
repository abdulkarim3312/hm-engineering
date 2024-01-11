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
    Project Wise Client
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
                    <form action="{{ route('project_wise_client') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project</label>
                                    <select class="form-control" name="project" required="">
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option {{ request()->get('project') == $project->id ? 'selected' : '' }} value="{{$project->id}}">{{$project->name}}</option>
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
            @if($customers)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">

                            <div style="clear: both">
                                <table class="table table-bordered" style="width:100%; float:left">
                                    <tr>
                                        <th rowspan="2" colspan="2" class="text-left">Client</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" width="20%">Total (in BDT)</th>
                                        <th class="text-right" width="15%">Paid (in BDT)</th>
                                        <th class="text-right" width="20%">Due (in BDT)</th>
                                    </tr>

                                    @foreach($customers as $row)
                                        <tr>
                                            <td colspan="2" class="text-left">{{ $row->client->name }}</td>
                                            <td class="text-right">{{ number_format($row->total,2) }}</td>
                                            <td class="text-right">{{ number_format($row->paid,2) }}</td>
                                            <td class="text-right">{{ number_format($row->due,2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th  colspan="2" class="text-right">Total</th>
                                        <th class="text-right"> {{ number_format($customers->sum('total'),2) }}</th>
                                        <th class="text-right"> {{ number_format($customers->sum('paid'),2) }}</th>
                                        <th class="text-right"> {{ number_format($customers->sum('due'),2) }}</th>
                                    </tr>
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
