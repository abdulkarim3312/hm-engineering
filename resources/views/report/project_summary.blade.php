@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Project Sales Summary
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('report.project_summary') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project</label>

                                    <select class="form-control" name="project">
                                        <option value="">Select Project</option>
                                        <option value="all" {{ request()->get('project') == 'all' ? 'selected' : '' }}>All Project</option>

                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ request()->get('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>	&nbsp;</label>

                                    <input class="btn btn-primary form-control" type="submit" value="Search">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($project_single != '')
    <div class="row">
        <div class="col-sm-12" style="min-height:300px">
            <section class="panel">
                <div class="panel-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                    <div  id="prinarea">
                        <div class="col-xs-12 text-center">
                            <div class="col-xs-4 text-center">
                                <img style="width: 35%" src="{{ asset('img/head_logo.jpeg') }}">
                            </div>
                            <h2 style="margin-right: 352px;">{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>Name of the Project: {{ $project_single->name??'' }}</h4>
                            <h3><strong>Project Sales Summary</strong></h3>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                        </table>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Order No.</th>
                                        <th class="text-center">Project Name</th>
                                        <th class="text-center">Client Name</th>
                                        <th class="text-center">Mobile No.</th>
                                        <th class="text-center">Floor No.</th>
                                        <th class="text-center">Flat No.</th>
                                        <th class="text-center">Total (in BDT)</th>
                                        <th class="text-center">Paid Amount (in BDT)</th>
                                        <th class="text-center">Dues (in BDT)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="text-left">{{ $order->order_no }}</td>
                                        <td class="text-left">{{ $order->project->name ?? '' }}</td>
                                        <td class="text-left">{{ $order->client->name ?? '' }}</td>
                                        <td class="text-left">{{ $order->client->mobile_no ?? ''}}</td>
                                        <td class="text-center">{{ $order->floor->name ?? ''}}</td>
                                        <td class="text-center">{{ $order->flat->name ?? ''}}</td>
                                        <td class="text-right">{{ number_format($order->total, 2) }}</td>
                                        <td class="text-right">{{ number_format($order->paid, 2) }}</td>
                                        <td class="text-right">{{ number_format($order->due, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                    <td colspan="6" class="text-right"><strong>Total</strong></td>
                                    <td class="text-right"><strong>{{ number_format($orders->sum('total'), 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($orders->sum('paid'), 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($orders->sum('due'), 2) }}</strong></td>
                                </tfoot>
                            </table>
                            {{ $orders->appends($appends)->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @endif
@endsection

@section('script')

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
