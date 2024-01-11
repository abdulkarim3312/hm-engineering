@extends('layouts.app')

@section('title')
    Loan Payment Details Information
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <hr>
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="text-center"> Date </th>
                            <th class="text-center"> Principle </th>
                            <th class="text-center"> Payment </th>
                            <th class="text-center"> Payment Type </th>
                            <th class="text-center"> Remaining Blance </th>
                            <th class="text-center"> Transaction Type </th>
                            <th class="text-center"> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td class="text-center">{{date("Y-m-d",strtotime($payment->date))}}</td>
                                <td class="text-center">{{number_format($payment->projectLoan->total ?? 0,2)}}</td>
                                <td class="text-center">{{number_format($payment->amount,2)}}</td>
                                <td class="text-center">
                                    @if ($payment->type ==1)
                                        <label class="label label-primary">Taken </label>
                                    @elseif ($payment->type == 2)
                                        <label class="label label-primary">Payment</label>
                                    @elseif ($payment->type == 3)
                                        <label class="label label-primary">Given </label>
                                    @elseif($payment->type == 4)
                                        <label class="label label-primary"> Receive </label>
                                    @endif
                                </td>
                                <td class="text-center">{{number_format($payment->due,2)}}</td>
                                 <td class="text-center">
                                    @if($payment->transaction_method==1)
                                        Cash
                                    @else
                                        Bank
                                    @endif

                                </td>
                                <td>
                                    <a target="_blank" class="btn btn-success " href="{{route('loan_payment_print',['payment'=>$payment->id])}}" > Print  </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- /.modal -->

@endsection

@section('script')

    <script>
        $(function () {
            $('#table').DataTable();

        });

    </script>

@endsection
