@extends('layouts.app')

@section('title')
    Client Report
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>

                    <div id="prinarea">
                        <div class="row">
                            <div class="col-xs-5 text-left">
                                <div class="img">
                                    <img src="{{ asset('img/logo.png') }}" height="124" width="132" style="float: left;border: 1.5px solid #4f56be;border-radius: 50%;">
                                </div>
                                <div class="logo" style="margin-left: 45px">
                                    <i><h2 style="margin-left: 103px;
                                                font-weight: bold;
                                                color: #4f56be!important;
                                                font-family: Monotype Corsiva;
                                                font-size: 40px;">Mukta</h2></i>
                                    <hr style="margin-top: -18px;margin-left: 99px;border: 2px solid #c00505;width: 121px;border-radius: 1px">
                                    <p style="margin-top: -23px;
                                            font-size: 15px;
                                            color: #f5f507!important;
                                            font-family: Monotype Corsiva;
                                            margin-left: 97px;">
                                        Homes LTD.  </p>
                                </div>
                            </div>
                            <div class="col-xs-7 text-center">
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
                             @foreach($clients as $client)
                                 <tr>
                                     <td class="text-center">{{$loop->iteration}}</td>
                                     <td>{{$client->name}}</td>
                                     <td>{{$client->company_name}}</td>
                                     <td class="text-center">৳ {{number_format($client->total,2)}}</td>
                                     <td class="text-center">৳ {{number_format($client->paid,2)}}</td>
                                     <td class="text-center">৳ {{number_format($client->due,2)}}</td>
                                 </tr>
                             @endforeach
                             </tbody>
                             <tfoot>
                             <tr>
                                 <th class="text-center" colspan="3">Total</th>
                                 <th class="text-center">৳ {{number_format($clients->sum('total',2))}}</th>
                                 <th class="text-center">৳ {{number_format($clients->sum('paid',2))}}</th>
                                 <th class="text-center">৳ {{number_format($clients->sum('due'),2)}}</th>
                             </tr>
                             </tfoot>
                         </table>

                     </div>

                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>


        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
