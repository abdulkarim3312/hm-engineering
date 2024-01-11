@extends('layouts.app')

@section('style')

    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Purchase Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('report.purchase') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')  }}" autocomplete="off" >
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')  }}" autocomplete="off" >
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier</label>

                                    <select class="form-control" name="supplier">
                                        <option value="">All Supplier</option>

                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request()->get('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Purchase ID</label>

                                    <input type="text" class="form-control" name="purchaseId" value="{{ request()->get('purchaseId') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product</label>

                                    <select class="form-control" name="product">
                                        <option value="">All Product</option>

                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ request()->get('product') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
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

    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>
                    <div id="prinarea">
                        <div class="row">
                            <div class="col-xs-4 text-left">
                                <img style="width: 35%" src="{{ asset('img/head_logo.jpeg') }}">
                            </div>
                            <div class="col-xs-8 text-center" style="margin-left: -133px;">
                                <div style="padding:10px; width:100%; text-align:center;">
                                    <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                                    <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                                    <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                                    <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order No.</th>
                                    <th>Supplier</th>
                                    <th>Total</th>
                                    <th class="print_remove">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($orders as $order)

                                    <tr>
                                        <td>{{ $order->date->format('j F, Y') }}</td>
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->supplier->name }}</td>
                                        <td>{{ number_format($order->total, 2) }}</td>
                                        <td class="print_remove"><a href="{{ route('purchase_receipt.details', ['order' => $order->id]) }}">View Invoice</a></td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <td>{{ number_format($orders->sum('total'), 2) }}</td>
                                    <td></td>

                                </tr>
                                </tfoot>
                            </table>

                            {{ $orders->appends($appends)->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('script')
    <!-- date-range-picker -->

    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });

    </script>
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            $('.print_remove').remove();
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
