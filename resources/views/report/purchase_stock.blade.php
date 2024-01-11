@extends('layouts.app')

@section('title')
    Row Material Stock Report
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
                    <form action="{{ route('report.purchase_stock') }}">
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
                                            <option value="{{ $product->id }}" {{ request()->get('product') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
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
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%;margin-top: 20px" src="{{ asset('img/head_logo.jpeg') }}">
                                </div>
                                <div class="col-xs-8 text-center">
                                    <div style="padding:10px; width:100%; text-align:center;">
                                        <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                    </div>
                                </div>
                                <div class="col-xs-12 text-center">
                                    <h4>Row Material Stock Report</h4>
                                    <h4>{{ $product_single->name }} - {{ $selectedMonthInText }}</h4>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center" rowspan="2">Date</th>
                                    <th class="text-center" rowspan="2">Opening Stock ({{ $product_single->unit->name }})</th>
                                    <th class="text-center" colspan="4" class="text-center">Buy Report</th>
                                    <th class="text-center" rowspan="2">Total Quantity ({{ $product_single->unit->name }})</th>
                                    <th class="text-center" rowspan="2">Used Quantity ({{ $product_single->unit->name }})</th>
                                    <th class="text-center" rowspan="2">Balance ({{ $product_single->unit->name }})</th>
                                </tr>

                                <tr>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Quantity  ({{ $product_single->unit->name }})</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $totalBuyAmount = 0;
                                    $totalPrice = 0;
                                ?>
                                @foreach($report as $item)
                                    <tr>
                                        <td class="text-center" rowspan="{{ count($item['buyAmount']) > 1 ? count($item['buyAmount']) : '1' }}">{{ date('d-m-Y',strtotime($item['date'])) }}</td>
                                        <td class="text-center" rowspan="{{ count($item['buyAmount']) > 1 ? count($item['buyAmount']) : '1' }}">{{ $item['initialStock'] }}</td>

                                        @if(count($item['buyAmount']) > 0)
                                            @foreach($item['buyAmount'] as $b)
                                                @if($loop->first)
                                                    <td class="text-center">{{ $b['supplier'] }}</td>
                                                    <td class="text-center">{{ $b['quantity'] }}</td>
                                                    <td class="text-center"> {{ $b['unit_price'] }}</td>
                                                    <td class="text-center"> {{ $b['total_price'] }}</td>

                                                    <?php
                                                        $totalBuyAmount += $b['quantity'];
                                                        $totalPrice += $b['total_price'];
                                                    ?>
                                                @endif
                                            @endforeach
                                        @else
                                            <td></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center"> 0</td>
                                            <td class="text-center"> 0</td>
                                        @endif


                                        <td class="text-center" rowspan="{{ count($item['buyAmount']) > 1 ? count($item['buyAmount']) : '1' }}">{{ $item['totalQuantity'] }}</td>
                                        <td class="text-center" rowspan="{{ count($item['buyAmount']) > 1 ? count($item['buyAmount']) : '1' }}">{{ $item['usedQuantity'] }}</td>
                                        <td class="text-center" rowspan="{{ count($item['buyAmount']) > 1 ? count($item['buyAmount']) : '1' }}">{{ $item['finalQuantity'] }}</td>
                                    </tr>

                                    @foreach($item['buyAmount'] as $b)
                                        @if(!$loop->first)
                                            <td class="text-center">{{ $b['supplier'] }}</td>
                                            <td class="text-center">{{ $b['quantity'] }}</td>
                                            <td class="text-center"> {{ $b['unit_price'] }}</td>
                                            <td class="text-center"> {{ $b['total_price'] }}</td>

                                            <?php
                                                $totalBuyAmount += $b['quantity'];
                                                $totalPrice += $b['total_price'];
                                            ?>
                                        @endif
                                    @endforeach
                                @endforeach

                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">{{ $totalBuyAmount }}</th>
                                    <th></th>
                                    <th class="text-center"> {{ $totalPrice }}</th>
                                    <th class="text-center"></th>
                                    <th class="text-center">{{ $report->sum('usedQuantity') }}</th>
                                    <th class="text-center">{{ $item['finalQuantity'] }}</th>
                                </tr>
                                </tbody>
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
