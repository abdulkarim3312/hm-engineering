@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Ledger(Customer)
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
                    <form action="{{ route('report.customer_ledger') }}" >
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label> Ledger </label>
                                    <select class="form-control" name="account_sub_head" required>
                                        <option value="">Select Ledger</option>
                                        @foreach($accountSubheads as $row)
                                            <option {{ request()->get('account_sub_head') == $row->id ? 'selected' : '' }} value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')  }}" autocomplete="off" required>
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
                                               id="end" name="end" value="{{ request()->get('end')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
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
            @isset($ledgers)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <div style="padding:10px; width:100%; text-align:center;">
                                <h2>Quantum Properties Ltd.</h2>
                                <h4> “QUANTUM MUSTAFA TOWER” (Level-09), 18 Gaus-ul-Azam Avenue, </h4>
                                <h4>Sector-13, Uttara, Dhaka-1230, BD.</h4>
                                <h3><strong>Ledger(Customer)</strong></h3>
                                {{--                                <h4><strong>{{$group_summary->name}}</strong></h4>--}}
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">{{ date("F d, Y", strtotime(request()->get('start'))).' - '.date("F d, Y", strtotime(request()->get('end'))) }}</th>
                                </tr>
                            </table>

                            <div style="clear: both">
                                <table class="table table-bordered" style="width:100%; float:left">
                                    <tr>
                                        <th rowspan="2"  class="text-center"> Particular </th>
                                        <th colspan="2" class="text-center"> Opening balance </th>
                                        <th colspan="2" class="text-center"> Transaction </th>
                                        <th colspan="2" class="text-center"> closing </th>
                                    </tr>
                                    <tr>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>

                                    @foreach($ledgers as  $ledger)
                                        <tr>
                                            <td class="text-center">{{ $ledger->particular }}</td>
                                            <td class="text-center">{{ $ledger->accountSubHead->opening_balance_debit }}</td>
                                            <td class="text-center">{{ $ledger->accountSubHead->opening_balance_credit }}</td>
                                            <td class="text-center">{{($ledger->transaction_type==1)? $ledger->amount:'' }}</td>
                                            <td class="text-center">{{($ledger->transaction_type!=1)? $ledger->amount:'' }}</td>
                                            <td class="text-center">{{($ledger->transaction_type==1)? $ledger->amount:'' }}</td>
                                            <td class="text-center">{{($ledger->transaction_type!=1)? $ledger->amount:'' }}</td>

                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td class="text-center">Total</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{ ($ledger->transaction_type==1)? $ledgers->sum('amount'):'' }}</td>
                                        <td class="text-center">{{ ($ledger->transaction_type!=1)? $ledgers->sum('amount'):'' }}</td>
                                        <td class="text-center">{{ ($ledger->transaction_type==1)? $ledgers->sum('amount'):'' }}</td>
                                        <td class="text-center">{{ ($ledger->transaction_type!=1)? $ledgers->sum('amount'):'' }}</td>

                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @endisset
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
