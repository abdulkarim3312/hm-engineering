<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        .bottom-title{
            border-top: 1.5px solid black;
            text-align: center;
        }
        .img-overlay {
            position: absolute;
            left: 0;
            top: 50px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.2;
        }

        .img-overlay img {
            width: 300px;
            height: auto;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid #000000 !important;
            padding: 2px 2px !important;
            font-size: 13px;
        }
        
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="img-overlay">
        <img src="{{ asset('img/logo.png') }}">
    </div>
    <div>
        <div class="row">
            <div class="col-xs-4 text-left">
                <div class="logo-area-report">
                    <img width="35%" src="{{ asset('img/head_logo.jpeg') }}">
                </div>
            </div>
            <div class="col-xs-8 text-center" style="margin-left: -123px;">
                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
            </div>
        </div>
<hr>
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th style="float:left">Project Name:</th>
                        <td style="float:left">
                            {{ $billStatement->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th style="float:left">Project Address:</th>
                        <td style="float:left">{{ $billStatement->address }}</td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <th style="float:left">Cheque Holder Name: </th>
                        <td style="float:left">{{ $billStatement->cheque_holder_name??'' }}</td>
                    </tr>
                    <tr>
                        <th style="float:left">Type Of Work (Trade): </th>
                        <td style="float:left">
                            @if ($billStatement->trade == 1)
                                Civil Contractor
                            @elseif ($billStatement->trade == 2)    
                                Paint Contractor
                            @elseif ($billStatement->trade == 3)  
                                Sanitary Contractor  
                            @elseif ($billStatement->trade == 4)    
                                Tiles Contractor
                            @elseif ($billStatement->trade == 5)    
                                MS Contractor
                            @elseif ($billStatement->trade == 6)    
                                Wood Contractor
                            @elseif ($billStatement->trade == 7)  
                                Electric Contractor  
                            @elseif ($billStatement->trade == 8)   
                                Thai Contractor 
                            @elseif ($billStatement->trade == 9)
                                Other Contractor      
                            @else
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th style="float:left;">Approval Date:</th>
                        <td style="float:left">{{ $billStatement->approved_date }}</td>
                    </tr>
                    <tr>
                        <th style="float:left;">Approval Date:</th>
                        <td style="float:left">{{ $billStatement->approved_note }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Item Code</th>
                        <th width="15%">Description of Work</th>
                        <th width="5%">Bill No</th>
                        <th>Approval Quantity</th>
                        <th width="7%">Unit</th>
                        <th width="7%">Rate</th>
                        <th>Approval T. Amount</th>
                        <th>Approval Payable(%)</th>
                        <th>Approval Payable Amount</th>
                        <th>Approval Deduct SD Money</th>
                        <th>Approval Net Amoount</th>
                        <th>Advanced Amoount</th>
                        <th>Approval Amoount</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($billStatement->billStatementDescription as $product)
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td>{{ $product->item_code ?? ''}}</td>
                            <td>{{ $product->work_description ?? ''}}</td>
                            <td>{{ $product->bill_no ?? ''}}</td>
                            <td>{{ $product->app_quantity }}</td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->rate }}</td>
                            <td> {{ number_format($product->app_t_amount,2) }}</td>
                            <td> {{ $product->app_payable }} %</td>
                            <td> {{ number_format($product->app_payable_a,2) }}</td>
                            <td> {{ number_format($product->app_deduct_money, 2) }}</td>
                            <td> {{ number_format($product->app_n_amount, 2) }}</td>
                            <td> {{ number_format($product->advance_amount, 2) }}</td>
                            <td> {{ number_format($product->approve_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <div class="row" style="margin-top: 50px !important;">
            <div class="col-xs-3" style="margin-top: 25px;">
                <div class="text-left" style="margin-left: 10px;">
                    <h5 class="bottom-title">Received By</h5>
                </div>
            </div>
            <div class="col-xs-3" style="margin-top: 25px">
                <div class="text-center">
                    <h5 class="bottom-title" style="text-align: center!important;">Prepared By</h5>
                </div>
            </div>
            <div class="col-xs-3" style="margin-top: 25px">
                <div class="text-right">
                    <h5 class="bottom-title">Checked By</h5>
                </div>
            </div>
            <div class="col-xs-3" style="margin-top: 25px">
                <div class="text-right">
                    <h5 class="bottom-title">Approved By</h5>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
