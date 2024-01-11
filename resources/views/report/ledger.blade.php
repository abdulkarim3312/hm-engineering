@extends('layouts.app')
@section('title','Ledger Report')
@section('style')
    <style>
        .row{
            margin: 0;
        }
        hr {
            margin-top: 0rem;
            margin-bottom: 0rem;
            border: 0;
            border-top: 1px solid black;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000 !important;
            vertical-align: middle;
            border-bottom-width: 1px !important;
            border-left-width: 1px !important;
            text-align: center;
            padding: 0.2rem !important;
        }
        @media print{
            @page {
                size: auto;
                margin: 20px !important;
            }
        }
        button, html input[type=button], input[type=reset], input[type=submit] {
            background: #367FA9;
            color: #fff;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- jquery validation -->
            <div class="box box-outline box-default">
                <div class="box-header">
                    <h3 class="box-title">Data Filter</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ route('report.ledger') }}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" id="start_date" autocomplete="off"
                                           name="start_date" class="form-control date-picker"
                                           placeholder="Enter Start Date" value="{{ request()->get('start_date') ?? $currentDate  }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">End Date <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" id="end_date" autocomplete="off"
                                           name="end_date" class="form-control date-picker"
                                           placeholder="Enter Start Date" value="{{ request()->get('end_date') ?? date('Y-m-d')  }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="account_head">Account Head <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="account_head" id="account_head">
                                        <option value="">All</option>
                                        @foreach($accountHeads as $accountHead)
                                            <option value="{{ $accountHead->id }}">Account Name: {{ $accountHead->name }}|Account Code {{ $accountHead->account_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <input type="submit" name="search" class="btn btn-primary form-control" value="Search">
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->
    </div>
    @if(count($accountHeadsSearch) > 0 && request('search'))
    <div class="row">
        <div class="col-md-12">
            <div class="box box-outline box-primary">
                <div class="box-header">
                    <a href="#" onclick="getprint('printArea')" class="btn btn-default btn-lg"><i class="fa fa-print"></i></a>
                </div>
                <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive-sm" id="printArea">

                            <div class="row ">
                                <div class="col-12">
                                    <h1 class="text-center m-0" style="font-size: 40px !important;font-weight: bold">
                                        <img height="50px" src="{{ asset('img/logo.png') }}" alt="">
                                        {{ config('app.name') }}
                                    </h1>

                                    <h3 class="text-center m-0 mb-2" style="font-size: 19px !important;">Ledger for : {{ date('d-m-Y',strtotime(request('start_date'))) }} to {{ date('d-m-Y',strtotime(request('end_date'))) }}</h3>

                                </div>
                            </div>
                            @foreach($accountHeadsSearch as $accountHead)
                                <?php
                                    $previousLedger = previousLedger(request('start_date'),request('end_date'),$accountHead->id);
                                    $incomeExpenses = ledger(request('start_date'),request('end_date'),$accountHead->id);
                                    $debitOpening = $previousLedger['debitOpening'];
                                    $creditOpening = $previousLedger['creditOpening'];
                                    $accountOpeningHead = $previousLedger['accountOpeningHead'];
                                 ?>
                                <div class="table-responsive">

                                <table class="table table-bordered" style="">
                                    <tr>
                                        <th colspan="6" style="font-size: 25px">
                                            A/C Name :
                                            @if(count($incomeExpenses) > 0)
                                            {{ $incomeExpenses[0]->accountHead->name ?? '' }}-({{ $incomeExpenses[0]->accountHead->account_code ?? '' }})
                                            @else
                                                {{ $accountOpeningHead->name ?? '' }}-({{ $accountOpeningHead->account_code ?? '' }})
                                            @endif

                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Voucher#</th>
                                        <th class="text-left">Particular</th>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                    </tr>
                                    @if($debitOpening != null || $creditOpening != null)
                                    <tr>
                                        <td>{{ request('start_date') }}</td>
                                        <td></td>
                                        <td class="text-left">Opening Balance</td>
                                        <td class="text-right" style="text-align: right">{{ number_format($debitOpening,2) }}</td>
                                        <td class="text-right" style="text-align: right">{{ number_format($creditOpening,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right" class="text-right">Monthly Total:</td>
                                        <td class="text-right" style="text-align: right">{{ number_format($debitOpening,2) }}</td>
                                        <td class="text-right" style="text-align: right">{{ number_format($creditOpening,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 30px!important;text-align: right" colspan="3" class="text-right"><b>Monthly Balance:</b></td>
                                        <td style="padding-bottom: 30px!important;text-align: right" class="text-right"><b>{{ number_format($debitOpening - $creditOpening,2) }}</b></td>
                                    </tr>
                                    @endif
                                    <?php
                                        $accountHeadDebitTotal = 0;
                                        $accountHeadCreditTotal = 0;
                                    ?>
                                    @foreach($monthsArray as $monthArray)
                                        <?php
                                            $start = \Carbon\Carbon::parse($monthArray.'-01');
                                            $end = \Carbon\Carbon::parse($monthArray.'-31');
                                        ?>
                                    @if($incomeExpenses->whereBetween('date',[$start,$end])->count() > 0)
                                        <?php
                                            $debitTotal = 0;
                                            $creditTotal = 0;
                                         ?>

                                        @foreach($incomeExpenses->whereBetween('date',[$start,$end]) as $incomeExpense)
                                            <tr>
                                                <td>{{ $incomeExpense->date->format('d-m-Y') }}</td>
                                                <td>
                                                    @if($incomeExpense->balance_transfer_id)
                                                        {{ $incomeExpense->balanceTransfer->voucher_no ?? ''  }}
                                                    @else
                                                        {{ $incomeExpense->receipt_payment_no }}
                                                    @endif
                                                </td>
                                                <td class="text-left" style="text-align: left">
                                                    @if($incomeExpense->vat_chaild_account_head_id != null)
                                                      VDS - {{ $incomeExpense->client->name ?? '' }} {{ $incomeExpense->notes ? (' - '.$incomeExpense->notes ): '' }}
                                                    @elseif($incomeExpense->ait_chaild_account_head_id != null)
                                                        TDS - {{ $incomeExpense->client->name }} {{ $incomeExpense->notes ? (' - '.$incomeExpense->notes ): '' }}
                                                    @elseif($incomeExpense->balance_transfer_id)
                                                        {{ $incomeExpense->accountHead->name ?? '' }} {{ $incomeExpense->notes ? (' - '.$incomeExpense->notes ): '' }}
                                                    @else
                                                    {{ $incomeExpense->client->name ?? '' }} {{ $incomeExpense->notes ? (' - '.$incomeExpense->notes ): '' }}
                                                    @endif
                                                </td>

                                                <td class="text-right" style="text-align: right">
                                                    @if(in_array($incomeExpense->transaction_type,[1]))
                                                        <?php
                                                            $debitTotal += $incomeExpense->amount;
                                                        ?>
                                                        {{ number_format($incomeExpense->amount,2) }}
                                                    @else
                                                        0.00
                                                    @endif

                                                </td>
                                                <td class="text-right" style="text-align: right">
                                                    @if(in_array($incomeExpense->transaction_type,[2]))
                                                        <?php
                                                        $creditTotal += $incomeExpense->amount;
                                                        ?>
                                                        {{ number_format($incomeExpense->amount,2) }}
                                                    @else
                                                        0.00
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="3" class="text-right" style="text-align: right">Monthly Total:</td>
                                            <td class="text-right" style="text-align: right">{{ number_format($debitTotal,2) }}</td>
                                            <td class="text-right" style="text-align: right">{{ number_format($creditTotal,2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-bottom: 30px!important;text-align: right" colspan="4" class="text-right"><b>Monthly Balance:</b></td>
                                            <td style="padding-bottom: 30px!important;text-align: right" class="text-right"><b>{{ number_format(($creditTotal - $debitTotal),2) }}</b></td>
                                        </tr>
                                        <?php

                                            $accountHeadDebitTotal += $debitTotal;
                                            $accountHeadCreditTotal += $creditTotal;
                                        ?>
                                    @endif
                                    @endforeach

                                    <tr>
                                        <td colspan="3" style="text-align: right" class="text-right">Account Total:</td>
                                        <td class="text-right" style="text-align: right">{{ number_format($accountHeadDebitTotal + $debitOpening,2) }}</td>
                                        <td class="text-right" style="text-align: right">{{ number_format($accountHeadCreditTotal + $creditOpening,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 30px!important;text-align: right" colspan="4" class="text-right"><b>Account Balance:</b></td>
                                        <td style="padding-bottom: 30px!important;text-align: right" class="text-right"><b>{{ number_format(($accountHeadCreditTotal + $creditOpening) - ($accountHeadDebitTotal + $debitOpening) ,2) }}</b></td>
                                    </tr>
                                </table>

                            </div>

                            @endforeach

                        </div>
                    </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    @endif
@endsection

@section('script')
    <script>

        var APP_URL = '{!! url()->full()  !!}';

        function getprint(print) {
            $('.print-heading').css('display','block');
            $('.extra_column').remove();
            $('body').html($('#' + print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
