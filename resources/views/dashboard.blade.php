@extends('layouts.app')
@section('style')
    <style>
        .header .info-box {
            display: block;
            min-height: 40px;
            background: #fff;
            /* width: 100%; */
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 2px;
            margin-bottom: 15px;
        }
        .header .info-box .info-box-content {
            padding: 5px 10px;
            margin-left: 0px;
            background-color: #36b177;
            color: white;
        }
        .vl {
            border-left: 6px solid #36b177;
            height: 50px;
            margin-left: 167px;
            margin-right: 167px;
            margin-top: -15px;
        }
        .col-md-3 .card_width{
            width: 90%;
        }
        .sub_header_box .sub_header{
            min-height: 60px;
            background-color: #36b177;
            color: white;
        }
        hr.new5 {
            border: 6px solid #36b177;
            border-radius: 2px;
        }
        .header_arrow .vl{
            border-left: 6px solid #36b177;
            height: 20px;
            /* margin-left: 167px;
            margin-right: 167px; */
        }
        .margin_top{
            margin-top: 13px;
        }
        .card_mt{
            margin-top: 35px;
        }
    </style>
@endsection
@section('content')

     @can('dashboard')
    <div class="row">
        <div class="col-md-4 col-sm-3 col-xs-3">
        </div>
        <div class="col-md-4 header col-sm-4 col-xs-4">
            <div class="info-box">
                <div class="info-box-content">
                    <h4 class="text-center text-bold">HM. ENGINEERING TECHNOLOGY</h4>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-3 col-xs-3">
        </div>
    <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-3 col-xs-3">
        </div>
        <div class="col-md-4 header col-sm-4 col-xs-4">
            <div class="vl"></div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-3 col-xs-3">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12"  style="margin-top: -20px;">
            <hr class="new5">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-left: -74px;margin-top: -5px;">
            <div class="col-md-3 header_arrow col-sm-3 col-xs-3">
                <div class="vl"></div>
            </div>
            <div class="col-md-3 header_arrow col-sm-3 col-xs-3">
                <div class="vl" style="margin-left: 136px;"></div>
            </div>
            <div class="col-md-2 header_arrow col-sm-4 col-xs-4">
                <div class="vl" style="margin-left: 100px;"></div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 header_arrow col-sm-3 col-xs-3">
                <div class="vl" style="margin-left: 136px;"></div>
            </div>
            <div class="col-md-2 header_arrow col-sm-3 col-xs-3">
                <div class="vl" style="margin-left: 145px;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12 sub_header_box">
            <a href="{{ route('auto_cad_training') }}">
                <div class="info-box card_width sub_header">
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text text-bold margin_top"> HM. AutoCAD Training Center</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 sub_header_box">
           <a href="{{ route('chemical_treatment') }}">
            <div class="info-box sub_header" style="width: 76%;margin-left: -22px;">
                <div class="info-box-content" style="margin-left: 0px;">
                    <span class="info-box-text text-bold margin_top"> HM. Chemical Treatment </span>
                </div>
            </div>
           </a>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 sub_header_box">
            <a href="{{ route('design_construction') }}">
                <div class="info-box card_width sub_header" style="margin-left: -75px;width: 138%;">
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text text-bold margin_top"> HM. Design & Construction </span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 sub_header_box">
            <a href="{{ route('steel_structure') }}">
                <div class="info-box card_width sub_header" style="margin-left: -21px; width: 104%;">
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text text-bold margin_top"> HM. Steel Structure </span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12 sub_header_box">
           <a href="{{ route('interior_design') }}">
            <div class="info-box card_width sub_header" style="margin-left: -17px; width: 109%;">
                <div class="info-box-content" style="margin-left: 0px;">
                    <span class="info-box-text text-bold margin_top"> HM. Interior Design</span>
                </div>
            </div>
           </a>
        </div>
    </div>
    {{-- <div class="row card_mt">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"> Today Sale Amount</span>
                    <span class="info-box-number">৳ {{ number_format($todaySale, 2) }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-star-half"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"> Today due Amount</span>
                    <span class="info-box-number">৳ {{ number_format($todayDue, 2) }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"> Total Sale Amount </span>
                    <span class="info-box-number">৳ {{ number_format($totalSale, 2) }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"> Today Expense </span>
                    <span class="info-box-number">৳ {{ number_format($todayExpense, 2) }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"> Total Expense </span>
                    <span class="info-box-number">৳ {{ number_format($totalExpense, 2) }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>


    </div> --}}

    {{-- <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"> Today Sale Order </h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th> Order No. </th>
                                <th> Customer Name </th>
                                <th> Total </th>
                                <th> Order Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($todaySaleReceipt as $receipt)
                            <tr>
                                <td><a href="{{ route('sale_receipt.details', ['order' => $receipt->id]) }}">{{ $receipt->order_no }}</a></td>
                                <td>{{ $receipt->client->name }}</td>
                                <td>৳{{ number_format($receipt->total, 2) }}</td>
                                <td>{{ $receipt->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    {{ $todaySaleReceipt->links() }}
                </div>
                <!-- /.box-footer -->
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Today purchase oder </h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th> Order Number </th>
                                <th> Supplier </th>
                                <th> Total </th>
                                <th> Oder Date </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($todayPurchaseReceipt as $receipt)
                                <tr>
                                    <td><a href="{{ route('purchase_receipt.details', ['order' => $receipt->id]) }}">{{ $receipt->order_no }}</a></td>
                                    <td>{{ $receipt->supplier->name }}</td>
                                    <td>৳{{ number_format($receipt->total, 2) }}</td>
                                    <td>{{ $receipt->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    {{ $todaySaleReceipt->links() }}
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div> --}}


    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"> Sale History </h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="">
                    <canvas id="chart-sales-amount" width="100%" height="30"></canvas>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"> Order History </h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="">
                    <canvas id="chart-order-count" width="100%" height="30"></canvas>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
     @endcan
@endsection

@section('script')
    <script src="{{ asset('themes/backend/plugins/chartjs/Chart.bundle.min.js') }}"></script>
    <script>

        var ctx = document.getElementById('chart-sales-amount');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                // labels: saleAmountLabel,
                datasets: [{
                    // label: 'Sales Amount',
                    // data: saleAmount,
                    backgroundColor: 'rgba(60, 141, 188, 0.2)',
                    borderColor:  'rgba(60,141,188,1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: false,
                },
                tooltips: {
                    displayColors: false,
                    callbacks: {
                        label: function (tooltipItems, data) {
                            return   "৳" + tooltipItems.yLabel;
                        }
                    }
                }
            }
        });

        var ctx2 = document.getElementById("chart-order-count").getContext('2d');
        var myChart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                // labels: saleAmountLabel,
                datasets: [{
                    // data: orderCount,
                    backgroundColor: 'rgba(60, 141, 188, 0.2)',
                    borderColor:  'rgba(60,141,188,1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: false,
                },
                tooltips: {
                    displayColors: false
                }
            }
        });
    </script>
@endsection
