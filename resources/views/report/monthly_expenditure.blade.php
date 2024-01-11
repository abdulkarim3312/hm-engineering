@extends('layouts.app')

@section('title')
    Monthly Expenditure
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
                    <form action="{{ route('report.monthly_expenditure') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Year</label>

                                    <select class="form-control" name="year" required>
                                        <option value="">Select Year</option>
                                        @for($i=2020; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ request()->get('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Month</label>

                                    <select class="form-control" name="month" required>
                                        <option value="">Select Month</option>
                                        <option value="1" {{ request()->get('month') == '1' ? 'selected' : '' }}>January</option>
                                        <option value="2" {{ request()->get('month') == '2' ? 'selected' : '' }}>February</option>
                                        <option value="3" {{ request()->get('month') == '3' ? 'selected' : '' }}>March</option>
                                        <option value="4" {{ request()->get('month') == '4' ? 'selected' : '' }}>April</option>
                                        <option value="5" {{ request()->get('month') == '5' ? 'selected' : '' }}>May</option>
                                        <option value="6" {{ request()->get('month') == '6' ? 'selected' : '' }}>June</option>
                                        <option value="7" {{ request()->get('month') == '7' ? 'selected' : '' }}>July</option>
                                        <option value="8" {{ request()->get('month') == '8' ? 'selected' : '' }}>August</option>
                                        <option value="9" {{ request()->get('month') == '9' ? 'selected' : '' }}>September</option>
                                        <option value="10" {{ request()->get('month') == '10' ? 'selected' : '' }}>October</option>
                                        <option value="11" {{ request()->get('month') == '11' ? 'selected' : '' }}>November</option>
                                        <option value="12" {{ request()->get('month') == '12' ? 'selected' : '' }}>December</option>
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
            @if($result)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
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
                                    <h4>Statement of Monthly Expenditure</h4>
                                    <h4>For the Month of {{ $selectedMonthInText }}</h4>
                                    <h4>Date: {{ date('d.m.Y') }}</h4>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center">Sl. No.</th>
                                    <th class="text-center">Head of Expenditure</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Total</th>
                                </tr>


                                @foreach($result as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item['particular'] }}</td>
                                        <td class="text-center"> {{ number_format($item['dhaka_office'], 2) }}</td>
                                        <td class="text-center"> {{ number_format($item['total'], 2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <th></th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center"> {{ number_format($result->sum('dhaka_office'), 2) }}</th>
                                    <th class="text-center"> {{ number_format($result->sum('total'), 2) }}</th>
                                </tr>
                            </table>

                            <div style="padding:30px; margin-top: 80px; width:100%; clear: both">
                                <div style="width: 50%; float: left; text-align: center">
                                    Accounts Officer
                                </div>



                                <div style="width: 50%; float: left; text-align: center">
                                    Managing Director
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
