@extends('layouts.app')


@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection
@section('title')
    Supplier Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('report.supplier_statement') }}">
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
                                            <option value="{{ $supplier->id }}" {{ request()->get('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name}}</option>
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
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>
                    <div id="prinarea">
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
                                    <h4><strong>Receipt</strong></h4>
                                </div>
                            </div>
                        </div>
                         <table id="table" class="table table-bordered table-striped">
                             <thead>
                             <tr>
                                 <th class="text-center">Sl</th>
                                 <th class="text-center">Name</th>
                                 <th class="text-center">Company Name</th>
                                 <th class="text-center">Total</th>
                                 <th class="text-center">Paid</th>
                                 <th class="text-center">Due</th>
                             </tr>
                             </thead>
                             <tbody>
                                 <?php
                                 $total = 0 ;
                                 $paid = 0;
                                 $due = 0;
                                  ?>
                             @foreach($orders as $order)
                             <?php
                                $total += $order->supplier->order_total;
                                $paid += $order->supplier->order_paid;
                                $due += $order->supplier->order_due;
                             ?>
                                 <tr>
                                     <td class="text-center">{{$loop->iteration}}</td>
                                     <td >{{$order->supplier->name}}</td>
                                     <td >{{$order->supplier->company_name}}</td>
                                     <td class="text-center"> {{number_format($order->supplier->order_total,2)}}</td>
                                     <td class="text-center"> {{number_format($order->supplier->order_paid,2)}}</td>
                                     <td class="text-center"> {{number_format($order->supplier->order_due,2)}}</td>
                                 </tr>
                             @endforeach
                             </tbody>
                             <tfoot>
                             <tr>
                                 <th class="text-center" colspan="3">Total</th>
                                 <th class="text-center"> {{number_format($total)}}</th>
                                 <th class="text-center"> {{number_format($paid)}}</th>
                                 <th class="text-center"> {{number_format($due)}}</th>
                                 {{-- <th class="text-center"> {{number_format($suppliers->sum('order_total',2))}}</th> --}}
                                 {{-- <th class="text-center"> {{number_format($suppliers->sum('order_paid',2))}}</th>
                                 <th class="text-center"> {{number_format($suppliers->sum('order_due'),2)}}</th> --}}
                             </tr>
                             </tfoot>
                         </table>
                         {{ $orders->appends($appends)->links() }}
                     </div>
                </div>
            </div>
        </div>
    </div>
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

    </script>
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
