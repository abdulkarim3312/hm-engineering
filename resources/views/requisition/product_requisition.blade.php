@extends('layouts.app')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        select.form-control.product {
            width: 138px !important;
        }
        input.form-control.quantity {
            width: 90px;
        }
        input.form-control.unit_price,input.form-control.selling_price{
            width: 130px;
        }
        th {
            text-align: center;
        }
        select.form-control {
            min-width: 120px;
        }
        .cart-box-body{
            padding: 20px 30px;
        }
        .box-header.with-border{
            /* padding-left: 30px; */
        }
        .cart-box-footer{
            padding: 16px 30px;
            border-top: 1px solid #f5f5f5;
        }
    </style>
@endsection
@section('title')
    Purchases
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Purchases Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('product.requisition') }}">
                    @csrf
                    <div class="card-body box-body">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                                    <label for="project">Project</label>

                                    <select id="project" class="form-control select2 project" style="width: 100%;" name="project" data-placeholder="Select Project">
                                        <option value="">Select One</option>
                                        @foreach($projects as $project)
                                            <option {{empty(old('$project'))? '': 'selected'}} value="{{ $project->id }}" {{ old('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('segment') ? 'has-error' :'' }}">
                                    <label for="segment">Project Segment</label>

                                    <select id="segment" class="form-control select2 segment" style="width: 100%;" name="segment" data-placeholder="Select Segment">
                                        <option value="">Select One</option>
                                    </select>
                                    @error('segment')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label for="date">Date</label>

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

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>

                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>

                                            <th>Total Cost</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody id="product-container">
                                        @if (old('product') != null && sizeof(old('product')) > 0)
                                            {{-- @foreach(old('category') as $item) --}}
                                                <tr class="product-item">
                                                    <td>
                                                        <div class="form-group {{ $errors->has('product.') ? 'has-error' :'' }}">
                                                            <select class="form-control select2 product" style="width: 100%;" data-selected-product="{{ old('product.') }}" name="product[]">
                                                                <option value="">Select Product</option>
                                                                @foreach($products as $product)
                                                                    <option {{old('product.')== $product->id ? 'selected' : ''}} value="{{$product->id}}">{{$product->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td >
                                                        <div class="form-group {{ $errors->has('quantity.') ? 'has-error' :'' }}">
                                                            <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.') }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group {{ $errors->has('unit_price.') ? 'has-error' :'' }}">
                                                            <input type="text" step="any" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.') }}">
                                                        </div>
                                                    </td>

                                                    <td  class="total-cost">৳0.00</td>
                                                    <td  class="text-center">
                                                        <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                                    </td>
                                                </tr>
                                            {{-- @endforeach --}}
                                        @else
                                            <tr class="product-item">

                                                <td >
                                                    <div class="form-group">
                                                        <select class="form-control select2 product" style="width: 100%;" name="product[]">
                                                            <option value="">Select Product</option>
                                                            @foreach($products as $product)
                                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>

                                                <td >
                                                    <div class="form-group">
                                                        <input type="number" step="any" style="width: 80%;" class="form-control quantity" name="quantity[]">
                                                    </div>
                                                </td>
                                                <td >
                                                    <div class="form-group">
                                                        <input type="text" step="any" style="width: 100%;" class="form-control unit_price" name="unit_price[]">
                                                    </div>
                                                </td>

                                                <td  class="total-cost">৳ 0.00</td>
                                                <td class="text-center">
                                                    <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td>
                                                <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add Product</a>
                                            </td>
                                            <th colspan="2" class="text-right">Total Amount</th>
                                            <th id="total-amount">৳0.00</th>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="4" class="text-right">Sub Total</th>
                                        <th id="product-sub-total">৳0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">VAT(%)</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('vat') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="vat" id="vat" value="{{ empty(old('vat')) ? ($errors->has('vat') ? '' : '0') : old('vat') }}">
                                                <span id="vat_total">৳0.00</span>
                                                <input type="hidden" class="form-control" name="total_vat" id="total_vat" value="{{ empty(old('total_vat')) ? ($errors->has('total_vat') ? '' : '0') : old('total_vat') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Discount (%)</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount" id="discount" value="{{ empty(old('discount')) ? ($errors->has('discount') ? '' : '0') : old('discount') }}">
                                                <span id="discount_total">৳0.00</span>
                                                <input type="hidden" class="form-control" name="total_discount" id="total_discount" value="{{ empty(old('total_discount')) ? ($errors->has('total_discount') ? '' : '0') : old('total_discount') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <th id="final-amount">৳0.00</th>
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
                                        <th id="due">৳0.00</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer box-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control select2 product" style="width: 100%;" name="product[]">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td >
                <div class="form-group">
                    <input type="number" step="any" style="width: 80%;" class="form-control quantity" name="quantity[]">
                </div>
            </td>
            <td >
                <div class="form-group">
                    <input type="text" step="any" style="width: 100%;" class="form-control unit_price" name="unit_price[]">
                </div>
            </td>

            <td  class="total-cost">৳ 0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {

            //Date picker
            $('#date, #date-refund').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            var message = '{{ session('message') }}';

            if (!window.performance || window.performance.navigation.type != window.performance.navigation.TYPE_BACK_FORWARD) {
                if (message != '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    });
                }
            }

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '1' || $(this).val() == '3' || $(this).val() == '4') {
                    $('#modal-bank-info').hide();
                } else {
                    $('#modal-bank-info').show();
                }
            });

            $('#modal-pay-type').trigger('change');

            var selectedBranch = '{{ old('branch') }}';
            var selectedAccount = '{{ old('account') }}';

            $('#modal-bank').change(function () {
                var bankId = $(this).val();
                $('#modal-branch').html('<option value="">Select Branch</option>');
                $('#modal-account').html('<option value="">Select Account</option>');

                if (bankId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_branch') }}",
                        data: { bankId: bankId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedBranch == item.id)
                                $('#modal-branch').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#modal-branch').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('#modal-branch').trigger('change');
                    });
                }

                $('#modal-branch').trigger('change');
            });

            $('#modal-branch').change(function () {
                var branchId = $(this).val();
                $('#modal-account').html('<option value="">Select Account</option>');

                if (branchId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bank_account') }}",
                        data: { branchId: branchId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedAccount == item.id)
                                $('#modal-account').append('<option value="'+item.id+'" selected>'+item.account_no+'</option>');
                            else
                                $('#modal-account').append('<option value="'+item.id+'">'+item.account_no+'</option>');
                        });
                    });
                }
            });

            $('#modal-bank').trigger('change');



            // select Project
            var selectedSegment='{{old('segment')}}'

            $('body').on('change','#project', function () {
                var projectId = $(this).val();
                var itemProject = $(this);
                $('#segment').html('<option value="">Select</option>');
                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_segment') }}",
                        data: {projectId:projectId}
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (selectedSegment == item.id)
                                $('#segment').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#segment').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });

                }
            });

            $('#project').trigger('change');

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);

                $('#product-container').append(item);
                initProduct();

                if ($('.product-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('keyup', '.quantity, .unit_price,  #vat, #discount, #paid', function () {
                calculate();
            });

            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }
            initProduct();
            calculate();
        });

        function calculate() {
            var subTotal = 0;

            var vat = $('#vat').val();
            var discount = $('#discount').val();
            var paid = $('#paid').val();

            if (vat == '' || vat < 0 || !$.isNumeric(vat))
                vat = 0;
            if (discount == '' || discount < 0 || !$.isNumeric(discount))
                discount = 0;
            if (paid == '' || paid < 0 || !$.isNumeric(paid))
                paid = 0;

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq('+i+')').html('৳' + (quantity * unit_price).toFixed(2) );
                subTotal += quantity * unit_price;
            });

            var productTotalVat = (subTotal * vat) / 100;

            var productTotalDiscount = (subTotal * discount) / 100;

            $('#vat_total').html('৳' + productTotalVat.toFixed(2));
            $('#total_vat').val( productTotalVat.toFixed(2));

            $('#discount_total').html('৳' + productTotalDiscount.toFixed(2));
            $('#total_discount').val( productTotalDiscount.toFixed(2));

            $('#total-amount').html('৳' + subTotal.toFixed(2));
            $('#product-sub-total').html('৳' + subTotal.toFixed(2));


            var total = parseFloat(subTotal) + parseFloat(productTotalVat) - parseFloat(productTotalDiscount);
            var due = parseFloat(total) - parseFloat(paid);
            $('#final-amount').html('৳' + total.toFixed(2));
            $('#due').html('৳' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));
        }

        function initProduct() {
            $('.select2').select2();
        }



        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });

    </script>
@endsection
