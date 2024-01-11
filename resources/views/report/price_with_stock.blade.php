@extends('layouts.app')

@section('title')
  Price With Stock Report
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
                    <form action="{{ route('report.price.with.stock') }}">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product</label>

                                    <select class="form-control" name="product" required>
                                        <option value="all">All Product</option>
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
    @isset($stocks)
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>

                    <div id="prinarea">
                        <div style="padding:10px; width:100%; text-align:center;">
                            <h2>National Development Company Ltd.</h2>
                            <h4>Corporate Office : Plot # 314/A, Road # 18, Block # E , Bashundhara R/A, Dhaka-1229</h4>
                            <h4>Price With Stock Report</h4>
                            <h5>Stock Report upto {{date('d-m-Y')}}</h5>
                        </div>
                        <table id="table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Flat</th>
                                <th class="text-center">Unit Price (TK.)</th>
                                <th class="text-center">Total Price (TK.)</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($stocks as $stock)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td >{{$stock->project->name}}</td>
                                    <td >{{$stock->flat->name}}</td>
                                    <td class="text-right">৳ {{number_format($stock->price,2)}}</td>
                                    <td class="text-right">৳ {{number_format($stock->quantity*$stock->price,2)}}</td>
                                </tr>
                            @endforeach

                            </tbody>

                            <tfoot>
                            <tr>
                                <th class="text-center" style="border-right: 1px solid #fff !important;">Total= </th>
                                <th class="text-right" colspan="2">{{number_format($stocks->sum('quantity',2))}}</th>
                                <th colspan="2" class="text-right">৳ {{number_format($stocks->sum('quantity')*$stocks->sum('price'),2)}}</th>
                            </tr>
                            </tfoot>

                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
    @endisset
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
