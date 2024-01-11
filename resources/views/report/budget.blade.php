@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Budget Wise Report
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
                    <form action="{{ route('report.budget_wise_report') }}">
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
            @if($projects)
                <section class="panel">

                    <div class="panel-body">
{{--                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>--}}

                        <div class="adv-table" id="prinarea">
                            <div style="padding:10px; width:100%; text-align:center;">
                                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                <h3><strong>Budget Summary</strong></h3>
{{--                                <h4><strong>{{($salepayment->name)?$salepayment->name:''}}</strong></h4>--}}
                            </div>
{{--                            <table class="table table-bordered" style="margin-bottom: 0px">--}}
{{--                                <tr>--}}
{{--                                    <th class="text-center">{{ date("F d, Y", strtotime(request()->get('start'))).' - '.date("F d, Y", strtotime(request()->get('end'))) }}</th>--}}
{{--                                </tr>--}}
{{--                            </table>--}}

                            <div style="clear: both">
                                <table class="table table-bordered" style="width:100%; float:left">
                                    <tr>
                                        <th class="text-center">Project Name</th>
                                        <th class="text-center">Budget Amount</th>
                                        <th class="text-center">Expense Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    @if(count($bugets)>0)
                                    @foreach ($bugets as $budget)
                                        <tr>
                                            @if ($loop->first)
                                                <th  class="text-center" width="20%">{{$budget->project->name??''}}</th>
                                                <th class="text-center" width="15%">{{$budget->sum('total')??''}}</th>
                                                <th class="text-center" width="20%">{{ $expenses->sum('total') }}</th>
                                                @if (!empty($budget))
                                                    <th class="text-center" width="20%">
                                                        <a class="btn btn-info btn-sm" href="{{ route('budget_report.details', ['budget' => $budget->id]) }}">Details</a>
                                                    </th>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th></th>
                                        <th class="text-center">Total = {{ $bugets->sum('total') }}</th>
                                        <th class="text-center">Total = {{ $expenses->sum('total') }}</th>
                                    </tr>

                                @endif
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
