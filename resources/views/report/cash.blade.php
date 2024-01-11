@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
          href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Cashbook
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
                    <form action="{{ route('report.cash') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')  }}"
                                               autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')  }}"
                                               autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label> &nbsp;</label>

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
            @if($result)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button>
                        <br>
                        <hr>

                        <div class="adv-table" id="prinarea">
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%;margin-top: 20px;" src="{{ asset('img/head_logo.jpeg') }}">
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
                                    <h4>Cashbook Report</h4>
                                </div>
                            </div>
                            <div style="clear: both">
                                <table class="table table-bordered" style="width:100%; float:left">
                                    <tr>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Particular</th>
{{--                                        <th class="text-center">Vch. Type</th>--}}
{{--                                        <th class="text-center">Vch. Number</th>--}}
                                        <th class="text-center">Debit in(BDT)</th>
                                        <th class="text-center">Credit in(BDT)</th>
                                        <th class="text-center">Balance</th>
                                    </tr>
                                    @php
                                        $totalDebit=0;
                                        $totalCredit=0;
                                    @endphp
                                    @foreach($result as $item)
                                        <tr>
                                            <td class="text-center">
                                                @if (!empty($item['date']))
                                                    {{ date('d-m-Y',strtotime($item['date'])) }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{  $item['particular'] }}</td>
                                            <td class="text-center">৳ {{ number_format((double)$item['credit'],2) }}</td>
                                            <td class="text-center">৳ {{ number_format((double)$item['debit'],2) }}</td>
                                            <td class="text-center">৳ {{ number_format((double)$item['balance'],2) }}</td>
{{--                                            <td class="text-center">--}}
{{--                                                --}}{{-- @if($income->transaction_type==1)--}}
{{--                                                    RV#{{$income->salepayment->receipt_no}}--}}
{{--                                                @elseif($income->transaction_type==3)--}}
{{--                                                    PV#{{$income->purchasePayment->purchaseOrder->order_no}}--}}
{{--                                                @endif --}}
{{--                                            </td>--}}
{{--                                            <td class="text-center">{{$item->transaction_type==3? number_format($item->amount,2):'' }}</td>--}}
{{--                                            <td class="text-center">{{$item->transaction_type==1? number_format($item->amount,2):'' }}</td>--}}
                                        </tr>
{{--                                        @php--}}
{{--                                            if ($item->transaction_type==3){--}}
{{--                                             $totalDebit +=$item->amount;--}}
{{--                                             }--}}
{{--                                             elseif ($item->transaction_type==1){--}}
{{--                                             $totalCredit +=$item->amount;--}}
{{--                                             }--}}
{{--                                        @endphp--}}
                                    @endforeach
                                    <tr>
                                        <th class="text-center" colspan="2">Total</th>
                                        <th class="text-center">৳ {{ number_format((double)$metaData['total_credit'],2) }}</th>
                                        <th class="text-center">৳ {{ number_format((double)$metaData['total_debit'],2) }}</th>
                                        <th class="text-center"></th>
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
    <!-- bootstrap datepicker -->
    <script
        src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

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

            $('body').html($('#' + print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
