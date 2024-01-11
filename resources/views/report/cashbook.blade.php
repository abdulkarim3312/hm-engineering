@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

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
                    <form action="{{ route('report.cashbook') }}">
                        <div class="row">
                            <div class="col-md-4">
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

                            <div class="col-md-4">
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
            @if($result)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <div style="padding:10px; width:100%; text-align:center;">
                                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                <h4>Cashbook Report</h4>
                            </div>
                            @foreach($result as $item)
                                <table class="table table-bordered" style="margin-bottom: 0px">
                                    <tr>
                                        <th class="text-center">{{ date("F d, Y", strtotime($item['date'])) }}</th>
                                    </tr>
                                </table>

                                <div style="clear: both">
                                    <table class="table table-bordered" style="width:50%; float:left">
                                        <tr>
                                            <th colspan="5" class="text-center">Debit</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" width="30%">Particular</th>
                                            <th class="text-center" width="20%">Note</th>
                                            <th class="text-center" width="15%">Payment Type</th>
                                            <th class="text-center" width="20%">Bank Details</th>
                                            <th class="text-center" width="15%">Amount</th>
                                        </tr>

                                        <img src="{{ asset('img/logo.png') }}"
                                             style="border-radius: 50%;
                                     opacity: 0.3;
                                     width: 370px;
                                     height: 400px;
                                     position: absolute;
                                     border-radius: 50%;
                                     border: 1.5px solid #4f56be;
                                     margin-left: 215px;
                                     margin-top: 180px;
                                     z-index: 1;
                                     "
                                        >

                                        @foreach($item['incomes'] as $income)
                                            <tr>
                                                <td class="text-center">{{ $income->particular }}</td>
                                                <td class="text-center">{{ $income->note }}</td>
                                                <td class="text-center">{{ $income->transaction_method == 1 ? 'Cash' : 'Bank' }}</td>
                                                <td class="text-center">
                                                    @if ($income->transaction_method == 2)
                                                        {{ $income->bank.' - '.$income->bank_account }}
                                                    @endif
                                                </td>
                                                <td class="text-center">৳ {{ number_format($income->amount,2) }}</td>
                                            </tr>
                                        @endforeach

                                        <?php
                                        $incomesCount = count($item['incomes']);
                                        $expensesCount = count($item['expenses']);

                                        if ($incomesCount > $expensesCount)
                                            $maxCount = $incomesCount;
                                        else
                                            $maxCount = $expensesCount;

                                        $maxCount += 2;
                                        ?>

                                        @for($i=count($item['incomes']); $i<$maxCount; $i++)
                                            <tr>
                                                <td><br><br></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endfor

                                        <tr>
                                            <th class="text-center" colspan="4">Total</th>
                                            <th class="text-center">৳ {{ number_format($item['incomes']->sum('amount'),2) }}</th>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered" style="width:50%; float:left">
                                        <tr>
                                            <th colspan="5" class="text-center">Credit</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" width="30%">Particular</th>
                                            <th class="text-center" width="20%">Note</th>
                                            <th class="text-center" width="15%">Payment Type</th>
                                            <th class="text-center" width="20%">Bank Details</th>
                                            <th class="text-center" width="15%">Amount</th>
                                        </tr>

                                        @foreach($item['expenses'] as $expense)
                                            <tr>
                                                <td>{{ $expense->particular }}</td>
                                                <td class="text-center">{{ $expense->note }}</td>
                                                <td class="text-center">{{ $expense->transaction_method == 1 ? 'Cash' : 'Bank' }}</td>
                                                <td class="text-center">
                                                    @if ($expense->transaction_method == 2)
                                                        {{ $expense->bank.' - '.$expense->bank_account }}
                                                    @endif
                                                </td>
                                                <td class="text-center">৳ {{ number_format($expense->amount,2) }}</td>
                                            </tr>
                                        @endforeach

                                        @for($i=count($item['expenses']); $i<$maxCount; $i++)
                                            <tr>
                                                <td><br><br></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endfor

                                        <tr>
                                            <th class="text-center" colspan="4">Total</th>
                                            <th class="text-center">৳ {{ number_format($item['expenses']->sum('amount'),2) }}</th>
                                        </tr>
                                    </table>
                                </div>
                            @endforeach
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
