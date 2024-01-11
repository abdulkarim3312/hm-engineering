@extends('layouts.app')

@section('title')
  Stock Report
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
                    <form action="{{ route('report.sale_stock') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Year</label>

                                    <select class="form-control" name="year" required>
                                        <option value="">Select Year</option>
                                        @for($i=2020; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ request()->get('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Month</label>

                                    <select class="form-control" name="month" required>
                                        <option value="">Select Month</option>
                                        <option value="1" {{ request()->get('month') == '1' ? 'selected' : '' }}>January</option>
                                        <option value="2" {{ request()->get('month') == '2' ? 'selected' : '' }}>February</option>
                                        <option value="3" {{ request()->get('month') == '3' ? 'selected' : '' }}>March</option>
                                        <option value="4" {{ request()->get('month') == '4' ? 'selected' : '' }}>April</option>
                                        <option value="5" {{ request()->get('month') == '5' ? 'selected' : '' }}>May</option>
                                        <option value="6" {{ request()->get('month') == '6' ? 'selected' : '' }}>June</option>
                                        <option value="7" {{ request()->get('month') == '7' ? 'selected' : '' }}>July</option>
                                        <option value="8" {{ request()->get('month') == '8' ? 'selected' : '' }}>August</option>
                                        <option value="9" {{ request()->get('month') == '9' ? 'selected' : '' }}>September</option>
                                        <option value="10" {{ request()->get('month') == '10' ? 'selected' : '' }}>October</option>
                                        <option value="11" {{ request()->get('month') == '11' ? 'selected' : '' }}>November</option>
                                        <option value="12" {{ request()->get('month') == '12' ? 'selected' : '' }}>December</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product</label>

                                    <select class="form-control" name="product" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->flat->id }}" {{ request()->get('product') == $product->flat->id ? 'selected' : '' }}>{{ $product->flat->name }}</option>
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
            @if($report)
                <?php

                ?>
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <div style="padding:10px; width:100%; text-align:center;">
                                <h2>National Development Company Ltd.</h2>
                                <h4>Corporate Office : Plot # 314/A, Road # 18, Block # E , Bashundhara R/A, Dhaka-1229</h4>
                                <h4>Stock Report</h4>

                                <h4>{{ $product_single->name }} - {{ $selectedMonthInText }}</h4>
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th width="16.66%" class="text-center">Date</th>
                                    <th width="16.66%" class="text-center">Opening Stock ({{ $product_single->unit->name }})</th>
                                    <th width="16.66%" class="text-center">Production ({{ $product_single->unit->name }})</th>
                                    <th width="16.66%" class="text-center">Total Quantity ({{ $product_single->unit->name }})</th>
                                    <th width="16.66%" class="text-center">Sale ({{ $product_single->unit->name }})</th>
                                    <th width="16.66%" class="text-center">Balance ({{ $product_single->unit->name }})</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php

                                    $totalProduction=0;
                                    $totalQuantity=0;
                                    $totalSale=0;
                                    $totalBalance=0;
                                ?>
                                @foreach($report as $item)



                                    <tr>
                                        <td class="text-center">{{date('d-m-Y',strtotime( $item['date'])) }}</td>
                                        <td class="text-center">{{ $item['initialStock'] }}</td>
                                        <td class="text-center">{{ $item['production'] }}</td>
                                        <td class="text-center">{{ $item['totalQuantity'] }}</td>
                                        <td class="text-center">{{ $item['sale'] }}</td>
                                        <td class="text-center">{{ $item['balance'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">{{ $report->sum('production') }}</th>
                                    <th class="text-center">{{ $item['totalQuantity'] }}</th>
                                    <th class="text-center">{{ $report->sum('sale') }}</th>
                                    <th class="text-center">{{ $item['balance'] }}</th>

                                </tr>
                                </tfoot>
                            </table>


                        </div>
                    </div>
                </section>
            @endisset
        </div>
    </div>
@endsection

@section('script')
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
