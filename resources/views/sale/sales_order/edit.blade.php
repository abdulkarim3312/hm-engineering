@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        #payment_div{display: none;}
    </style>
@endsection

@section('title')
    Sales Order Edit
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
                <div class="box-header with-border">
                    <h3 class="box-title">Order Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('sales_order_edit',['order'=> $order->id]) }}">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('client') ? 'has-error' :'' }}">
                                    <label>Client</label>

                                    <select class="form-control select2" style="width: 100%;" name="client" data-placeholder="Select Client" required>
                                        <option value="">Select Client</option>

                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client',$order->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                                    <label>Project</label>

                                    <select class="form-control project select2" style="width: 100%;" name="project" id="project" required>
                                        <option value="">Select Project</option>

                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{old('project',$order->project_id) == $project->id?"selected":''}}>{{ $project->name }}</option>
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
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ $order->date }}" autocomplete="off">
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
                                        <input type="text" class="form-control" id="note" name="note" value="{{ $order->note }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="25%">Flat/Shop</th>
                                    <th width="15%"> Price</th>
                                    <th> Car Parking</th>
                                    <th> Utility</th>
                                    <th> Others</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="flat-container">
                                   @foreach($order->flats as $product)
                                        <tr class="project-item">
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control  select2" style="width: 100%;" name="flat" id="flat" required>
                                                        <option value="">Select Flat</option>
{{--                                                        @foreach($flats as $flat)--}}
{{--                                                            <option value="{{$flat->id}}" {{$product->pivot->flat_id==$flat->id?"selected":''}}>{{$flat->name}}</option>--}}
{{--                                                        @endforeach--}}
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control price" value="{{$product->pivot->price}}" id="price" name="price" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control car" id="car" value="{{$product->pivot->car}}" name="car" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control uty" id="uty" value="{{$product->pivot->utility}}" name="uty" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control other" id="other" value="{{$product->pivot->other}}" name="other" required>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
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
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>Payment Step</label>--}}
{{--                                    <select class="form-control" id="payment_step" name="payment_step">--}}
{{--                                        <option value="1" {{ $order->payment_step == '1' ? 'selected' : '' }}>Booking Money</option>--}}
{{--                                        <option value="1" {{ $order->payment_step == '2' ? 'selected' : '' }}>Down Payment</option>--}}
{{--                                        <option value="1" {{ $order->payment_step == '3' ? 'selected' : '' }}>Installment</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                                <div class="form-group {{ $errors->has('financial_year') ? 'has-error' :'' }}">--}}
{{--                                    <label for="financial_year">Select Financial Year <span--}}
{{--                                            class="text-danger">*</span></label>--}}
{{--                                    <select class="form-control select2" name="financial_year" id="financial_year">--}}
{{--                                        <option value="">Select Year</option>--}}
{{--                                        @for($i=2022; $i <= date('Y'); $i++)--}}
{{--                                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>--}}
{{--                                        @endfor--}}
{{--                                    </select>--}}
{{--                                    @error('financial_year')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>Payment Type </label>--}}
{{--                                    <select class="form-control select2" id="payment_type" name="payment_type">--}}
{{--                                        <option value="" >Select Payment Type</option>--}}
{{--                                        <option value="1" {{ $order->receiptPayment->payment_type == '1' ? 'selected' : '' }}>Cheque</option>--}}
{{--                                        <option value="2" {{ $order->receiptPayment->payment_type == '2' ? 'selected' : '' }}>Cash</option>--}}

{{--                                    </select>--}}
{{--                                    @error('payment_type')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="form-group {{ $errors->has('account') ? 'has-error' :'' }}">--}}
{{--                                    <label>Bank/Cash Account <span class="text-danger">*</span></label>--}}
{{--                                    <select class="form-control select2" id="account" name="account">--}}
{{--                                        <option value="">Select Bank/Cash Account</option>--}}
{{--                                            <option value="{{ $order->receiptPayment->account_head_id }}" selected>{{ $order->receiptPayment->accountHead->name }} | {{ $order->receiptPayment->accountHead->account_code }}</option>--}}
{{--                                    </select>--}}
{{--                                    @error('account')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="form-group  bank-area {{ $errors->has('cheque_date') ? 'has-error' :'' }}" style="display: none">--}}
{{--                                    <label>Cheque Date <span class="text-danger">*</span></label>--}}
{{--                                    <div class="input-group date">--}}
{{--                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>--}}
{{--                                        <input type="text" class="form-control pull-right date-picker"--}}
{{--                                               id="cheque_date" name="cheque_date" value="{{ old('cheque_date',date('Y-m-d'))  }}" autocomplete="off">--}}
{{--                                    </div>--}}
{{--                                    @error('cheque_date')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="form-group  bank-area {{ $errors->has('cheque_no') ? 'has-error' :'' }}" style="display: none">--}}
{{--                                    <label>Cheque No. <span class="text-danger">*</span></label>--}}
{{--                                    <input type="text" class="form-control"--}}
{{--                                           id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}">--}}

{{--                                    @error('cheque_no')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="form-group bank-area" style="display: none">--}}
{{--                                    <label for="issuing_bank_name">Issuing Bank Name</label>--}}
{{--                                    <input type="text" value="" id="issuing_bank_name" name="issuing_bank_name" class="form-control" placeholder="Enter Issuing Bank Name">--}}
{{--                                </div>--}}
{{--                                <div class="form-group bank-area" style="display: none">--}}
{{--                                    <label for="issuing_branch_name">Issuing Branch Name </label>--}}
{{--                                    <input type="text" value="" id="issuing_branch_name" name="issuing_branch_name" class="form-control" placeholder="Enter Issuing Bank Branch Name">--}}
{{--                                </div>--}}

{{--                            </div>--}}
                            <div class="col-md-offset-6 col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="4" class="text-right"> Sub Total</th>
                                        <th id="product-sub-total">0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right"> VAT (%)</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('vat') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="vat" id="vat" value="{{ empty(old('vat')) ? ($errors->has('vat') ? '' : $order->vat_percent) : old('vat') }}">
                                                <span id="vat_total">0.00</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right"> Discount</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount" id="discount" value="{{ empty(old('discount')) ? ($errors->has('discount') ? '' : $order->discount) : old('discount') }}">
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
                                                <input type="text" class="form-control" name="paid" id="paid" disabled
                                                       value="{{ empty(old('paid')) ? ($errors->has('paid') ? '' : $order->paid) : old('paid') }}">
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
                    <!-- /.box-body -->


                    <div class="box-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <input type="hidden" name="refund" id="refund">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('themes/backend/dist/js/sweetalert2@9.js') }}"></script>
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

            var floorSelected = '{{ empty(old('floor')) ? ($errors->has('floor') ? '' : $order->floor->id) : old('floor') }}';
            var flatSelected = '{{ $order->flat_id }}';

            //alert(flatSelected);

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
                var projectId = $('#project').val();
                $('#flat').html('<option value="">Select Flat/Shop</option>');
                if (floorId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('sale.get_all_flat') }}",
                        data: {floorId: floorId,projectId:projectId}
                    }).done(function (response) {
                        $.each(response, function( index, item ) {
                            if (flatSelected == item.flat_id)
                                $('#flat').append('<option value="'+item.flat_id+'" selected >'+item.flat.name+'-'+item.flat.size+'</option>');
                            else
                                $('#flat').append('<option value="'+item.flat_id+'">'+item.flat.name+'-'+item.flat.size+'</option>');
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

