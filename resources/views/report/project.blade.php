@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Project Report
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
                    <form action="{{ route('report.project_statement') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Project</label>
                                    <select class="form-control" name="project">
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
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
            @if($incomes)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <div style="padding:10px; width:100%; text-align:center;">
                                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                <h4>Project Report</h4>
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">{{ date("F d, Y", strtotime(request()->get('start'))).' - '.date("F d, Y", strtotime(request()->get('end'))) }}</th>
                                </tr>
                            </table>

                            <div style="clear: both">
                                <table class="table table-bordered" style="width:50%; float:left">
                                    <tr>
                                        <th colspan="6" class="text-center">Debit</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" width="10%">Date</th>
                                        <th class="text-center" width="25%">Particular</th>
                                        <th class="text-center" width="20%">Note</th>
                                        <th class="text-center" width="15%">Payment Type</th>
                                        <th class="text-center" width="20%">Bank Details</th>
                                        <th class="text-center" width="10%">Amount</th>
                                    </tr>

                                    @foreach($incomes as $income)
                                        <tr>
                                            <td class="text-center">{{ $income->date->format('j F, Y') }}</td>
                                            <td>{{ $income->particular }}</td>
                                            <td class="text-center">{{ $income->note }}</td>
                                            <td class="text-center">
                                                @if($income->transaction_method == 1)
                                                    Cash
                                                @elseif($income->transaction_method == 2)
                                                    Bank
                                                @elseif($income->transaction_method == 3)
                                                    Mobile Banking
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($income->transaction_method == 2)
                                                    {{ $income->bank->name.' - '.$income->account->account_no }}
                                                @endif
                                            </td>
                                            <td class="text-center">৳ {{ number_format($income->amount,2) }}</td>
                                        </tr>
                                    @endforeach

                                    <?php
                                    $incomesCount = count($incomes);
                                    $expensesCount = count($expenses);

                                    if ($incomesCount > $expensesCount)
                                        $maxCount = $incomesCount;
                                    else
                                        $maxCount = $expensesCount;

                                    $maxCount += 2;
                                    ?>

                                    @for($i=count($incomes); $i<$maxCount; $i++)
                                        <tr>
                                            <td ><br><br></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endfor

                                    <tr>
                                        <th class="text-center" colspan="5">Total</th>
                                        <th class="text-center">৳ {{ number_format($incomes->sum('amount'),2) }}</th>
                                    </tr>
                                </table>
                                <table class="table table-bordered" style="width:50%; float:left">
                                    <tr>
                                        <th colspan="6" class="text-center">Credit</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" width="10%">Date</th>
                                        <th class="text-center" width="25%">Particular</th>
                                        <th class="text-center" width="20%">Note</th>
                                        <th class="text-center" width="15%">Payment Type</th>
                                        <th class="text-center" width="20%">Bank Details</th>
                                        <th class="text-center" width="10%">Amount</th>
                                    </tr>

                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td class="text-center">{{ $expense->date->format('j F, Y') }}</td>
                                            <td>{{ $expense->particular }}</td>
                                            <td class="text-center">{{ $expense->note }}</td>
                                            <td class="text-center">
                                                @if($expense->transaction_method == 1)
                                                    Cash
                                                @elseif($expense->transaction_method == 2)
                                                    Bank
                                                @elseif($expense->transaction_method == 3)
                                                    Mobile Banking
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($expense->transaction_method == 2)
                                                    {{ $expense->bank->name.' - '.$expense->account->account_no }}
                                                @endif
                                            </td>
                                            <td class="text-center">৳ {{ number_format($expense->amount,2) }}</td>
                                        </tr>
                                    @endforeach

                                    @for($i=count($expenses); $i<$maxCount; $i++)
                                        <tr>
                                            <td><br><br></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endfor

                                    <tr>
                                        <th class="text-center" colspan="5">Total</th>
                                        <th class="text-center">৳ {{ number_format($expenses->sum('amount'),2) }}</th>
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
