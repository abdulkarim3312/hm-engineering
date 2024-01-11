@extends('layouts.app')

@section('title')
    Salary Process
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
                    <h3 class="box-title">Salary Process Information</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <form action="{{ route('payroll.salary_process.index') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div  class="col-md-3">
                                <div class="form-group {{ $errors->has('payment_type') ? 'has-error' :'' }}">
                                    <label>Employee <span class="text-danger">*</span></label>
                                    <select class="form-control" id="employee" name="employee">
                                        <option >Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_type')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
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
                          </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('year') ? 'has-error' :'' }}">
                                    <label>Year <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="year" id="year">
                                        <option value="">Select Year</option>
                                        @for($i=2017; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('year')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('month') ? 'has-error' :'' }}">
                                    <label>Month <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="month">
                                        <option value="">Select Month</option>
                                        <option value="1"{{ old('month') == 1 ? 'selected' : '' }}>January</option>
                                        <option value="2"{{ old('month') == 2 ? 'selected' : '' }}>February</option>
                                        <option value="3"{{ old('month') == 3 ? 'selected' : '' }}>March</option>
                                        <option value="4"{{ old('month') == 4 ? 'selected' : '' }}>April</option>
                                        <option value="5"{{ old('month') == 5 ? 'selected' : '' }}>May</option>
                                        <option value="6"{{ old('month') == 6 ? 'selected' : '' }}>June</option>
                                        <option value="7"{{ old('month') == 7 ? 'selected' : '' }}>July</option>
                                        <option value="8"{{ old('month') == 8 ? 'selected' : '' }}>August</option>
                                        <option value="9"{{ old('month') == 9 ? 'selected' : '' }}>September</option>
                                        <option value="10{{ old('month') == 10 ? 'selected' : '' }}">October</option>
                                        <option value="11{{ old('month') == 11 ? 'selected' : '' }}">November</option>
                                        <option value="12{{ old('month') == 12 ? 'selected' : '' }}">December</option>
                                    </select>
                                    @error('month')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Process Date <span class="text-danger">*</span></label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right date-picker"
                                                name="date" value="{{ date('Y-m-d')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
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


            {{--  var selectedMonth = '{{ old('month') }}';--}}
            {{--$('#year').change(function () {--}}
            {{--    var year = $(this).val();--}}
            {{--    $('#month').html('<option value="">Select Month</option>');--}}

            {{--    if (year != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('get_month') }}",--}}
            {{--            data: { year: year }--}}
            {{--        }).done(function( response ) {--}}
            {{--            $.each(response, function( index, item ) {--}}
            {{--                if (selectedMonth == item.id)--}}
            {{--                    $('#month').append('<option selected value="'+item.id+'">'+item.name+'</option>');--}}
            {{--                else--}}
            {{--                    $('#month').append('<option value="'+item.id+'">'+item.name+'</option>');--}}
            {{--            });--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}
            {{--$('#year').trigger('change');--}}
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
