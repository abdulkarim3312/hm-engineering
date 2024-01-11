@extends('layouts.app')
@section('title')
    Labour Bill Process
@endsection

@section('content')
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('error') }}
        </div>
    @endif

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Labour Bill Information</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <form action="{{ route('labour.bill.add') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Labour Employee </label>
                                    <select class="form-control select2" id="labour_employee" name="labour_employee">
                                        <option value="">All Employee</option>
                                        @foreach ($labourEmployees as $labourEmployee)
                                            <option value="{{ $labourEmployee->id }}" {{ old('labour_employee') == $labourEmployee->id ? 'selected' : '' }}>{{ $labourEmployee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('labour_employee')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                                    <label for="financial_year">Select Financial Year <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" name="financial_year" id="financial_year">
                                        <option value="">Select Year</option>
                                        @for($i=2022; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ old('financial_year') == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>
                                        @endfor
                                    </select>
                                    @error('financial_year')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Type </label>
                                    <select class="form-control select2" id="payment_type" name="payment_type">
                                        <option value="">Select Payment Type</option>
                                        <option value="1">Bank</option>
                                        <option value="2">Cash</option>

                                    </select>
                                </div>
                                @error('payment_type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div  class="col-md-3">
                                <div class="form-group {{ $errors->has('account') ? 'has-error' :'' }}">
                                    <label>Bank/Cash Account <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="account" name="account">
                                        <option value="">Select Bank/Cash Account</option>
                                        @if (old('account') != '')
                                            <option value="{{ old('account') }}" selected>{{ old('account_name') }}</option>
                                        @endif
                                    </select>
                                    @error('account')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 bank-area" style="display: none">
                                <div class="form-group {{ $errors->has('cheque_date') ? 'has-error' :'' }}">
                                    <label>Cheque Date <span class="text-danger">*</span></label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right date-picker"
                                               id="cheque_date" name="cheque_date" value="{{ old('cheque_date',date('Y-m-d'))  }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->
                                    @error('cheque_date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 bank-area" style="display: none">
                                <div class="form-group {{ $errors->has('cheque_no') ? 'has-error' :'' }}">
                                    <label>Cheque No. <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control"
                                           id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}">

                                    @error('cheque_no')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
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
                                               id="start_date" name="start_date" value="{{ old('end_date', date('Y-m-d', strtotime('-7 days')))  }}" autocomplete="off" required>
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
                                               id="end_date" name="end_date" value="{{ old('end_date',date('Y-m-d'))  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Process Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="date" name="date" value="{{ date('Y-m-d')  }}" autocomplete="off" required>
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
@endsection

@section('script')

    <script>
        $(function () {
            // Select2
            $('.select2').select2();
            //Date picker
            intSelect2();

            $("#payment_type").change(function (){
                var payType = $(this).val();

                if(payType != ''){
                    if(payType == 1){
                        $(".bank-area").show();
                    }else{
                        $(".bank-area").hide();
                    }
                }
            })
            $("#payment_type").trigger("change");


            $('#start_date, #end_date, #date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: 'bottom'
            });

        });
        function intSelect2(){
            $('.select2').select2()
            $('#account').select2({
                ajax: {
                    url: "{{ route('account_head_code.json') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            $('#account').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#account").index(this);
                $('#account_name:eq('+index+')').val(data.text);
            });

        }
    </script>
@endsection
