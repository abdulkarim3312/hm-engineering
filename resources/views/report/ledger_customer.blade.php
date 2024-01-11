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
                                    <select class="form-control" name="account_head_sub_type_id" required>
                                        <option value="">Select Ledger</option>
                                        @foreach($accountSubheads as $row)
                                            <option {{ request()->get('account_head_sub_type_id') == $row->id ? 'selected' : '' }} value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Client</label>
                                    <select class="form-control" name="client" required>
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                            <option {{ request()->get('client') == $client->id ? 'selected' : '' }} value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                    <!-- /.input group -->
                                </div>
                            </div> --}}
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
            @if($transactions)
                <section class="box">

                    <div class="box-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea" style="padding: 0 5px!important;">

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
                                    <h4><strong>Transaction Details</strong></h4>
                                    <h4>{{ $accountSubhead->name??'' }}</h4>
                                </div>
                            </div>
                            @if(request()->get('account_head_sub_type_id') == 2)
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">
                                        @if (request()->get('start') != '' && request()->get('end') != '')
                                            {{ date("d-m-Y",strtotime(request()->get('start')))}} to {{ date("d-m-Y",strtotime(request()->get('end')))}}
                                        @else
                                            {{ $first_date ? date("d-m-Y",strtotime($first_date->date)) : ''}} to {{ date("d-m-Y")}}
                                        @endif

                                    </th>
                                </tr>
                            </table>
                            <div style="clear: both">
                                <table class="table table-bordered" style="width:100%; float:left">
                                    <tr>
                                        <th width="10%">Transaction Date</th>
                                        <th width="8%">Image</th>
                                        <th width="8%">Client ID</th>
                                        <th width="40%">Particulars</th>
                                        <th width="10%">Vch. Type</th>
                                        <th width="10%"> Payment Method </th>
                                        <th width="15%" class="text-right" width="20%">Debit(in BDT)</th>
                                        <th width="15%" class="text-right" width="10%">Credit(in BDT)</th>
                                    </tr>
                                    @php
                                        $total_received = 0;
                                        $total_payment = 0;
                                    @endphp
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="text-center">{{ $transaction->date->format('d-m-Y') }}</td>
                                            <td>
                                                <img width="70" src="{{ asset($transaction->client->image) }}" alt="no image">
                                            </td>
                                            <td>{{ $transaction->client->id_no }}</td>
                                            <td>{{ $transaction->particular }}</td>
                                            <td>
                                                @if ($transaction->transaction_type == 1)
                                                    Received
                                                @else
                                                    Payment
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction->transaction_method == 1) Cash
                                                @elseif($transaction->transaction_method == 2) Bank
                                                @elseif($transaction->transaction_method == 4) Cheque
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                @if ($transaction->transaction_type != 1)
                                                    {{ number_format($transaction->amount,2) }}
                                                    @php
                                                        $total_payment +=  $transaction->amount;
                                                    @endphp
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if ($transaction->transaction_type == 1)
                                                    {{ number_format($transaction->amount,2) }}
                                                    @php
                                                        $total_received +=  $transaction->amount;
                                                    @endphp
                                                @endif

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"><strong>Narration:</strong> {{ $transaction->note }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- <tr>
                                        <th class="text-right" colspan="4">Current Total:</th>
                                        <th class="text-right">{{ number_format($orders->sum('total')+$opening_balance) }}</th>
                                        <th class="text-right">{{ number_format($incomes->sum('amount'),2) }}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Balance c/d:</th>
                                        <th colspan="2" class="text-right">{{ number_format($orders->sum('total')+$opening_balance - $incomes->sum('amount'),2)  }} </th>
                                    </tr> --}}
                                    <tr>
                                        <th class="text-right" colspan="4">Total:</th>
                                        <th class="text-right">{{ number_format($total_payment,2) }}</th>
                                        <th class="text-right">{{ number_format($total_received,2) }} </th>
                                    </tr>
                                </table>
                            </div>
                            @else
                                <table class="table table-bordered" style="margin-bottom: 0px">
                                    <tr>
                                        <th class="text-center">
                                            @if (request()->get('start') != '' && request()->get('end') != '')
                                                {{ date("d-m-Y",strtotime(request()->get('start')))}} to {{ date("d-m-Y",strtotime(request()->get('end')))}}
                                            @else
                                                {{ $first_date ? date("d-m-Y",strtotime($first_date->date)) : ''}} to {{ date("d-m-Y")}}
                                            @endif

                                        </th>
                                    </tr>
                                </table>
                                <div style="clear: both">
                                    <table class="table table-bordered" style="width:100%; float:left">
                                        <tr>
                                            <th width="10%">Transaction Date</th>
                                            <th width="40%">Particulars</th>
                                            <th width="10%">Vch. Type</th>
                                            <th width="10%"> Payment Method </th>
                                            <th width="15%" class="text-right" width="20%">Debit(in BDT)</th>
                                            <th width="15%" class="text-right" width="10%">Credit(in BDT)</th>
                                        </tr>
                                        @php
                                            $total_received = 0;
                                            $total_payment = 0;
                                        @endphp
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td class="text-center">{{ $transaction->date->format('d-m-Y') }}</td>
                                                <td>{{ $transaction->particular }}</td>
                                                <td>
                                                    @if ($transaction->transaction_type == 1)
                                                        Received
                                                    @else
                                                        Payment
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($transaction->transaction_method == 1) Cash
                                                    @elseif($transaction->transaction_method == 2) Bank
                                                    @elseif($transaction->transaction_method == 4) Cheque
                                                    @endif
                                                </td>

                                                <td class="text-right">
                                                    @if ($transaction->transaction_type != 1)
                                                        {{ number_format($transaction->amount,2) }}
                                                        @php
                                                            $total_payment +=  $transaction->amount;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    @if ($transaction->transaction_type == 1)
                                                        {{ number_format($transaction->amount,2) }}
                                                        @php
                                                            $total_received +=  $transaction->amount;
                                                        @endphp
                                                    @endif

                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"><strong>Narration:</strong> {{ $transaction->note }}</td>
                                            </tr>
                                        @endforeach

                                        {{-- <tr>
                                            <th class="text-right" colspan="4">Current Total:</th>
                                            <th class="text-right">{{ number_format($orders->sum('total')+$opening_balance) }}</th>
                                            <th class="text-right">{{ number_format($incomes->sum('amount'),2) }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-right" colspan="4">Balance c/d:</th>
                                            <th colspan="2" class="text-right">{{ number_format($orders->sum('total')+$opening_balance - $incomes->sum('amount'),2)  }} </th>
                                        </tr> --}}
                                        <tr>
                                            <th class="text-right" colspan="4">Total:</th>
                                            <th class="text-right">{{ number_format($total_payment,2) }}</th>
                                            <th class="text-right">{{ number_format($total_received,2) }} </th>
                                        </tr>
                                    </table>
                                </div>
                            @endif
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
