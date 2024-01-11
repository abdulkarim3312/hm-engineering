@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
   Income & Expense Report
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
                    <form action="{{ route('report.income_statement') }}">
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
@if (isset($incomes) && isset($expenses) && !empty($incomes) && !empty($expenses))
    <div class="row">

        <div class="col-sm-12" style="min-height:300px" >
           <div class="box">
               <div class="box-body">
                   <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br>

                   <div id="prinarea">
                       <div style="padding:10px; width:100%; text-align:center;">
                           <h2>National Development Company Ltd.</h2>
                           <h4>Corporate Office : Plot # 314/A, Road # 18, Block # E , Bashundhara R/A, Dhaka-1229</h4>
                           <h4>Income & Expense Report</h4>
                       </div>

                       <table class="table table-bordered">
                           <thead>
                           <tr>

                               <th class="text-center" width="50%">Particular</th>
                               <th class="text-center" width="25%">Amount(Tk.)</th>
                               <th class="text-center" width="25%">Amount(Tk.)</th>
                           </tr>

                           </thead>
                           <tbody>
                           <tr>
                               <td> <strong>Income</strong></td>
                               <td></td>
                               <td></td>
                           </tr>
                           <?php
                           $counter = 0;
                           if (sizeof($incomes) > 0)  {
                               $old = $incomes[0]->account_head_type_id;
                           }

                           $sum = 0;
                           ?>

                           @foreach ($incomes as $income)
                               @if ($old == $income->account_head_type_id)
                                   <tr>
                                       <td >{{$income->particular}}</td>
                                       <td class="text-center">{{$income->amount}}</td>
                                       <?php $sum += $income->amount; ?>
                                       <td class="text-center">
                                           @if ($loop->last)
                                               {{$sum}}
                                           @endif

                                       </td>
                                   </tr>

                               @elseif ($old != $income->account_head_type_id)
                                   <tr>
                                       <td></td>
                                       <td></td>
                                       <td class="text-center">{{ $sum }}</td>
                                   </tr>
                                   <?php $sum = 0 ?>
                                   <tr>
                                       <td>{{$income->particular}}</td>
                                       <td class="text-center">{{$income->amount}}</td>
                                       <td></td>
                                   </tr>

                                   <?php
                                   $old = $income->account_head_type_id;
                                   $sum += $income->amount;
                                   ?>
                               @endif
                           @endforeach
{{--                           <tr>--}}
{{--                               <td></td>--}}
{{--                               <td></td>--}}
{{--                               <td class="text-center">{{ $sum }}</td>--}}
{{--                           </tr>--}}

{{--                            Transaction Income--}}
                           <?php
                           $counter = 0;
                           if (sizeof($transactionIncomes) > 0)  {
                               $old = $transactionIncomes[0]->account_head_sub_type_id;
                           }

                           $sum = 0;
                           ?>

                           @foreach ($transactionIncomes as $income)
                               @if ($old == $income->account_head_sub_type_id)
                                   <tr>
                                       <td >{{$income->particular}}</td>
                                       <td class="text-center">{{$income->amount}}</td>
                                       <?php $sum += $income->amount; ?>
                                       <td class="text-center">{{ $sum }}</td>
                                   </tr>

                               @elseif ($old != $income->account_head_sub_type_id)
{{--                                   <tr>--}}
{{--                                       <td></td>--}}
{{--                                       <td></td>--}}
{{--                                       <td class="text-center">{{ $sum }}</td>--}}
{{--                                   </tr>--}}
                                   <?php $sum = 0 ?>
                                   <tr>
                                       <td>{{$income->particular}}</td>
                                       <td class="text-center">{{$income->amount}}</td>
                                       <?php
                                       $old = $income->account_head_sub_type_id;
                                       $sum += $income->amount;
                                       ?>
                                       <td class="text-center">{{ $sum }}</td>
                                   </tr>


                               @endif
                           @endforeach
{{--                           <tr>--}}
{{--                               <td></td>--}}
{{--                               <td></td>--}}
{{--                               <td class="text-center">{{ $sum }}</td>--}}
{{--                           </tr>--}}
                           <tr>
                               <th class="text-center" colspan="2">Total Income</th>
                               <th class="text-center">{{ $incomes->sum('amount') + $transactionIncomes->sum('amount') }}</th>
                           </tr>
{{--                           End Trasaction Income--}}

                           <tr>
                               <td> <strong>Expense</strong></td>
                               <td></td>
                               <td></td>
                           </tr>
                           <tr>
                               <th class="text-center" >Particular</th>
                               <th class="text-center" >Office Tk.</th>
                               <th class="text-center" >Factory Tk.</th>
                           </tr>
                           <?php
                               $factory=0;
                               $office=0;
                           ?>

                           @foreach ($expenses as $expense)

                                   <tr>
                                       <td >{{$expense->particular}}</td>


                                           <td class="text-center">
                                               @if ($expense->location==1)
                                                   <?php
                                                   $office+=$expense->amount;
                                                   ?>
                                               {{$expense->amount}}
                                               @endif
                                           </td>


                                           <td class="text-center">
                                               @if($expense->location==2)
                                                   <?php
                                                   $factory+=$expense->amount;
                                                   ?>
                                               {{$expense->amount}}
                                               @endif
                                           </td>

                                   </tr>

                           @endforeach


                           {{--                            Transaction Expense--}}

                           @foreach ($transactionExpenses as $expense)

                                   <tr>
                                       <td >{{$expense->particular}}</td>
                                       <td class="text-center">
                                           @if ($expense->location==1)
                                               <?php
                                               $office+=$expense->amount;
                                               ?>
                                               {{$expense->amount}}
                                           @endif
                                       </td>
                                       <td class="text-center">
                                           @if ($expense->location==2)
                                               <?php
                                               $factory+=$expense->amount;
                                               ?>
                                               {{$expense->amount}}
                                           @endif
                                       </td>
                                   </tr>

                           @endforeach
                           <tr>
                               <th class="text-center">Total</th>
                               <th class="text-center">{{$office}}</th>
                               <th class="text-center">{{$factory}}</th>
                           </tr>
                           <tr>
                               <th class="text-center" colspan="2">Total Expense</th>
                               <th class="text-center">{{ $expenses->sum('amount') + $transactionExpenses->sum('amount') }}</th>
                           </tr>
                           {{--                           End Trasaction Income--}}
                           </tbody>
                           <tfoot>
                           <tr>
                               <th class="text-center" colspan="2">Net Income:</th>
                               <th class="text-center"> {{(($incomes->sum('amount') + $transactionIncomes->sum('amount'))) -  ($expenses->sum('amount') + $transactionExpenses->sum('amount'))}}</th>
                           </tr>
                           </tfoot>
                       </table>

                   </div>

               </div>
           </div>
        </div>

    </div>

@endif

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
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
