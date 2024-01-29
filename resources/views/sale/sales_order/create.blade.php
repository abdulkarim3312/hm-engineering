@extends('layouts.app')

@section('style')
    <style>
        #payment_div{display: none;}
    </style>
@endsection

@section('title')
    Sales Order
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Information</h3>
                </div>
                <form method="POST" action="{{ route('sales_order.create') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('client') ? 'has-error' :'' }}">
                                    <label>Client <span style="color: red">*</span></label>

                                    <select class="form-control select2" style="width: 100%;" name="client" data-placeholder="Select Client" required>
                                        <option value="">Select Client</option>

                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                                    <label>Project <span style="color: red">*</span></label>

                                    <select class="form-control project select2" style="width: 100%;" name="project" id="project" required>
                                        <option value="">Select Project</option>

                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('floor') ? 'has-error' :'' }}">
                                    <label>Floor <span style="color: red">*</span></label>
                                    <select class="form-control floor select2" name="floor" id="floor">
                                        <option value="">Select Floor</option>
                                    </select>

                                    @error('floor_id')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Note</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 hide">
                                <div class="form-group {{ $errors->has('closing_balance') ? 'has-error' :'' }}">
                                    <input type="checkbox" name="closing_balance" value="1" id="closing_balance" onchange="showPayment()">
                                    <label for="closing_balance"> Previous All Balance </label>
                                    @error('closing_balance')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 hide" id="payment_div">
                                <div class="form-group {{ $errors->has('payment') ? 'has-error' :'' }}">
                                    <label> Previous Amount </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="payment" name="payment" value="{{ old('payment') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('payment')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th width="30%">Flat/Shop  <span class="text-danger">*</span></th>
                                    <th width="15%"> Price  <span class="text-danger">*</span></th>
                                    <th> Car Parking</th>
                                    <th> Utility</th>
                                    <th> Others</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="flat-container">
                                <tr class="project-item">
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control flat" style="width: 100%" name="flat" id="flat">
                                                <option value="">Select Flat/Shop</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control price" id="price" name="price" value="{{old('price',0)}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control car" id="car" name="car" value="{{old('car',0)}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control uty" id="uty" name="uty" value="{{old('uty',0)}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control other" id="other" name="other" value="{{old('other',0)}}">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th colspan="3" class="text-right">Total Amount : </th>
                                    <th class="text-right" id="total-amount"> 0.00 </th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Step</label>
                                    <select class="form-control" id="payment_step" name="payment_step">
                                        <option value="1" {{ old('payment_step') == '1' ? 'selected' : '' }}>Booking Money</option>
                                        {{-- <option value="1" {{ old('payment_step') == '2' ? 'selected' : '' }}>Down Payment</option>
                                        <option value="1" {{ old('payment_step') == '3' ? 'selected' : '' }}>Installment</option> --}}
                                    </select>
                                </div>
                                <div class="form-group {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                                        <label for="financial_year">Select Financial Year <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" name="financial_year" id="financial_year">
                                            <option value="">Select Year</option>
                                            @for($i=2017; $i <= date('Y'); $i++)
                                                <option value="{{ $i }}" {{ old('financial_year') == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>
                                            @endfor
                                        </select>
                                        @error('financial_year')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                <div class="form-group">
                                        <label>Payment Type </label>
                                        <select class="form-control select2" id="payment_type" name="payment_type">
                                            <option value="">Select Payment Type</option>
                                            <option {{ old('payment_type') == 1 ? 'selected' : '' }} value="1">Cheque</option>
                                            <option {{ old('payment_type') == 2 ? 'selected' : '' }} value="2">Cash</option>
                                            <option {{ old('payment_type') == 3 ? 'selected' : '' }} value="3">BGM</option>
                                            <option {{ old('payment_type') == 4 ? 'selected' : '' }} value="4">LC</option>
                                            <option {{ old('payment_type') == 5 ? 'selected' : '' }} value="5">Chalan</option>
                                            <option {{ old('payment_type') == 6 ? 'selected' : '' }} value="6">TT</option>
                                            <option {{ old('payment_type') == 7 ? 'selected' : '' }} value="7">BFTN</option>
                                        </select>
                                        @error('payment_type')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
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
                                <div class="form-group  bank-area {{ $errors->has('cheque_date') ? 'has-error' :'' }}" style="display: none">
                                        <label>Cheque Date <span class="text-danger">*</span></label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" class="form-control pull-right date-picker"
                                                   id="cheque_date" name="cheque_date" value="{{ old('cheque_date',date('Y-m-d'))  }}" autocomplete="off">
                                        </div>
                                        @error('cheque_date')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                <div class="form-group  bank-area {{ $errors->has('cheque_no') ? 'has-error' :'' }}" style="display: none">
                                        <label>Cheque No. <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}">

                                        @error('cheque_no')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                <div class="form-group bank-area" style="display: none">
                                    <label for="issuing_bank_name">Issuing Bank Name</label>
                                    <input type="text" value="" id="issuing_bank_name" name="issuing_bank_name" class="form-control" placeholder="Enter Issuing Bank Name">
                                </div>
                                <div class="form-group bank-area" style="display: none">
                                    <label for="issuing_branch_name">Issuing Branch Name </label>
                                    <input type="text" value="" id="issuing_branch_name" name="issuing_branch_name" class="form-control" placeholder="Enter Issuing Bank Branch Name">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th colspan="4" class="text-right"> Sub Total</th>
                                        <th id="product-sub-total">0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right"> VAT (%)</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('vat') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="vat" id="vat" value="{{ empty(old('vat')) ? ($errors->has('vat') ? '' : '0') : old('vat') }}">
                                                <span id="vat_total">0.00</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right"> Discount</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount" id="discount" value="{{ empty(old('discount')) ? ($errors->has('discount') ? '' : '0') : old('discount') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <th id="final-amount">0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Paid</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('paid') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="paid" id="paid" value="{{ empty(old('paid')) ? ($errors->has('paid') ? '' : '0') : old('paid') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Due</th>
                                        <th id="due">0.00</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </form>
            </div>
@endsection

@section('script')
    <script>
        $(function () {
            //Initialize Select2 Elements
            var flatSelected = '1';
            $('.select2').select2();
                intSelect2();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
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

            var floorSelected = '{{ old('floor') }}';
            var flatSelected = '{{ old('flat') }}';

            $('#project').change(function () {
                var projectId = $(this).val();

                $('#floor').html('<option value="">Select Floor</option>');

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('floor.get_floor') }}",
                        data: { projectId: projectId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (floorSelected == item.id)
                                $('#floor').append('<option value="'+item.id+'" selected>'+item.name+'-'+item.size+'</option>');
                            else
                                $('#floor').append('<option value="'+item.id+'">'+item.name+'-'+item.size+'</option>');
                        });
                        $('#floor').trigger('change');
                    });
                }
            });

            $('#project').trigger('change');

            $('body').on('change', '#floor', function () {
                var floorId = $(this).val();
                $('#flat').html('<option value="">Select Flat/Shop</option>');
                if (floorId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('sale.get_flat') }}",
                        data: {floorId: floorId}
                    }).done(function (response) {
                        $.each(response, function( index, item ) {
                            if (flatSelected == item.id)
                            $('#flat').append('<option value="'+item.id+'" selected >'+item.name+'-'+item.size+'</option>');
                            else
                            $('#flat').append('<option value="'+item.id+'">'+item.name+'-'+item.size+'</option>');
                        });
                    });
                }
            });
            $('#floor').trigger('change');

            $('body').on('click', '.btn-remove', function () {
                var index = $('.btn-remove').index(this);
                $(this).closest('.project-item').remove();
                calculate();
            });



            $('#flat').click(function () {
                var html = $('#template-project').html();
                var item = $(html);

                $('#flat-container').append(item);

                initFlat();

                if ($('.project-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.project-item').remove();
                calculate();

                if ($('.project-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });


            $('body').on('keyup', '#other,#price,#car,#uty, #vat, #paid, #discount', function () {
                calculate();
            });

            if ($('.project-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initFlat();
            calculate();


        });
        function calculate() {
            var productSubTotal = 0;
            var productTotalVat = 0;

            $('.project-item').each(function(i, obj) {

                var price = parseFloat($('#price').val());
                var car = parseFloat($('#car').val());
                var uty = parseFloat($('#uty').val());
                var other = parseFloat($('#other').val());

                var vat = parseFloat($('#vat').val());
                var discount = parseFloat($('#discount').val());
                var paid = parseFloat($('#paid').val());



                if (price == '' || price < 0 || !$.isNumeric(price))
                    price = 0;
                if (car == '' || car < 0 || !$.isNumeric(car))
                    car = 0;
                if (uty == '' || uty < 0 || !$.isNumeric(uty))
                    uty = 0;
                if (other == '' || other < 0 || !$.isNumeric(other))
                    other = 0;
                if (vat == '' || vat < 0 || !$.isNumeric(vat))
                    vat = 0;
                if (discount == '' || discount < 0 || !$.isNumeric(discount))
                    discount = 0;

                if (paid == '' || paid < 0 || !$.isNumeric(paid))
                    paid = 0;

                productSubTotal += price + car + uty + other;
                var productTotalVat = (productSubTotal* vat) / 100;

                $('#product-sub-total').html('' + productSubTotal.toFixed(2));
                $('#vat_total').html('' + productTotalVat.toFixed(2));

                var total = parseFloat(productSubTotal) + parseFloat(productTotalVat) - parseFloat(discount);

                var due = parseFloat(total) - parseFloat(paid);
                $('#final-amount').html('' + total.toFixed(2));
                $('#due').html('' + due.toFixed(2));
                $('#total').val(total.toFixed(2));
                $('#due_total').val(due.toFixed(2));

                if (due > 0) {
                $('#tr-next-payment').show();
                }
                else {
                    $('#tr-next-payment').hide();
                }
            });

            $('#total-amount').html(' ' + productSubTotal.toFixed(2));
            //$('#total-amount').html(total);
        }
        function initFlat() {
            $('.flat').select2();
        }
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
    <script type="text/javascript">
        function showPayment()
        {
            if($('#closing_balance').is(":checked"))
                $("#payment_div").show();
            else
                $("#payment_div").hide();
        }
    </script>

@endsection
