@extends('layouts.app')

@section('style')
    <style>
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('title')
    Sales Order
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ route('trading_sales_order.create') }}">
        @csrf
        <input type="hidden" name="type" value="1">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Order Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('customer') ? 'has-error' :'' }}" id="form-group-customer">
                                    <label>Customer *</label>
                                    <select class="form-control select2 customer" style="width: 100%;" id="customer" name="customer" required>
                                        <option value="">Select Customer </option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" @if (old('customer') == $customer->id) selected @endif>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="customer_name" id="customer-name" value="{{ old('customer_name') }}">
                                    @error('customer')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('warehouse') ? 'has-error' :'' }}" id="form-group-warehouse">
                                    <label>Warehouse *</label>
                                    <select class="form-control select2 warehouse" style="width: 100%;" id="warehouse" name="warehouse" required>
                                        <option value="">Select Warehouse </option>
                                        @foreach (App\Model\Warehouse::where('status',1)->get() as $warehouse)
                                            <option value="{{ $warehouse->id }}" @if (old('warehouse') == $warehouse->id) selected @endif>{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date *</label>
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
                                <div class="form-group {{ $errors->has('received_by') ? 'has-error' :'' }}">
                                    <label>Received By</label>
                                    <input class="form-control" type="text" name="received_by" value="{{ old('received_by') }}">
                                    @error('received_by')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('supporting_document') ? 'has-error' :'' }}">
                                    <label>Supporting Document</label>
                                    <div class="form-group">
                                        <input type="file" class="form-control" id="supporting_document" name="supporting_document" value="{{ old('supporting_document') }}">
                                    </div>
                                    <!-- /.input group -->
                                    @error('supporting_document')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Products</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Cost</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control product select2" style="width: 100%;" name="product[]" data-selected="{{ old('product.'.$loop->index) }}" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ old('product.'.$loop->parent->index) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control unit" name="unit[]" style="width: 100%;" value="{{ old('unit.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" style="width: 100%;" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" style="width: 100%;" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="total-cost">৳0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="7" class="available-quantity" style="font-weight: bold"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product" style="margin-bottom: 10px">Add Product</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Payment</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
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
                                <div class="form-group {{ $errors->has('payment_type') ? 'has-error' :'' }}">
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
                                <div class="form-group {{ $errors->has('account_head') ? 'has-error' :'' }}">
                                    <label>Bank/Cash Account <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="account_head" name="account">
                                        <option>Select Bank/Cash Account</option>
                                        @foreach ($accountHeads as $accountHead)
                                            <option>{{ $accountHead->name.'|'.$accountHead->account_code }}</option>
                                        @endforeach
                                        {{-- @if (old('account_head') != '')
                                            <option value="{{ old('account_head') }}" selected>{{ old('account_name') }}</option>
                                        @endif --}}
                                    </select>
                                    @error('account_head')
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
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="4" class="text-right">Product Sub Total</th>
                                        <th id="product-sub-total">৳0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Product VAT (%)</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('vat') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="vat" id="vat" value="{{ empty(old('vat')) ? ($errors->has('vat') ? '' : '0') : old('vat') }}">
                                                <span id="vat_total">৳0.00</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Product Discount</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount" id="discount" value="{{ empty(old('discount')) ? ($errors->has('discount') ? '' : '0') : old('discount') }}">
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
                                    <tr id="tr-next-payment">
                                        <th colspan="4" class="text-right">Next Payment Date</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('next_payment') ? 'has-error' :'' }}">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right" id="next_payment" name="next_payment" value="{{ old('next_payment') }}" autocomplete="off">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product select2" style="width: 100%;" name="product[]" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" step="any" style="width: 100%;" class="form-control unit" name="unit[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" style="width: 100%;" class="form-control quantity" name="quantity[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control unit_price" style="width: 100%;"  name="unit_price[]">
                </div>
            </td>

            <td class="total-cost">৳0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>

        <tr>
            <td colspan="7" class="available-quantity" style="font-weight: bold"></td>
        </tr>
    </template>
@endsection

@section('script')
    <script>
        $(function () {
            intSelect2();
            //Initialize Select2 Elements
            $('.select2').select2()

            //Date picker
            $('#date, #next_payment').datepicker({
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

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);
                $('#product-container').append(item);

                initProduct();
            });

            $('body').on('change', '.product', function (e) {
                var productId = $(this).val();
                var warehouseId = $('#warehouse').val();
                //alert(warehouseId);
                $this = $(this);
                var index = $('.' + e.target.name.slice(0, -2)).index(this);

                //$this.closest('tr').find('.unit_price').prop('readonly', false);
                $this.closest('tr').find('.unit').prop('readonly', false);
                $this.closest('tr').find('.unit').val(' ');
                $this.closest('tr').find('.unit_price').val(' ');
                $this.closest('tr').find('.quantity').val(' ');

                if (productId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('product_stock_inventory') }}",
                        data: { productId: productId, warehouseId: warehouseId }
                    }).done(function( response ) {
                        if (response.success) {
                            //$this.closest('tr').find('.unit_price').prop('readonly', true);
                            $this.closest('tr').find('.unit').prop('readonly', true);
                            $this.closest('tr').find('.quantity').val(1);
                            $this.closest('tr').find('.quantity').attr({
                                "max" : response.count,
                                "min" : 1
                            });
                            $this.closest('tr').find('.unit_price').val(response.data.last_unit_price);
                            $this.closest('tr').find('.unit').val(response.data.unit);
                            $('.available-quantity:eq('+index+')').html('Available: ' + response.count);
                            calculate();
                        } else {
                            $this.closest('tr').find('.quantity').val(1);
                            $this.closest('tr').find('.unit_price').val('');
                            $this.closest('tr').find('.unit').val('');
                            $('.available-quantity:eq('+index+')').html('');
                            calculate();
                        }
                    });
                }
            });
            $('.warehouse').trigger('change');
            $('.product').trigger('change');

            $('body').on('click', '.btn-remove', function () {
                var index = $('.btn-remove').index(this);
                $(this).closest('.product-item').remove();

                $('.available-quantity:eq('+index+')').closest('tr').remove();
                calculate();
            });

            $('body').on('keyup', '.quantity, .unit_price, .service_quantity, .service_unit_price, #vat, #service_vat, #discount, #service_discount, #paid', function () {
                calculate();
            });

            $('body').on('change', '.quantity, .unit_price, .service_quantity, .service_unit_price', function () {
                calculate();
            });

            calculate();

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '1' || $(this).val() == '3') {
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

            // Service
            $('#btn-add-service').click(function () {
                var html = $('#template-service').html();
                var item = $(html);

                $('#service-container').append(item);

                if ($('.product-item').length + $('.service-item').length >= 1 ) {
                    $('.btn-remove').show();
                    $('.btn-remove-service').show();
                }
            });

            $('body').on('click', '.btn-remove-service', function () {
                $(this).closest('.service-item').remove();
                calculate();

                if ($('.product-item').length + $('.service-item').length <= 1 ) {
                    $('.btn-remove').hide();
                    $('.btn-remove-service').hide();
                }
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
        });

        function calculate() {
            var productSubTotal = 0;
            var serviceSubTotal = 0;

            var vat = $('#vat').val();
            var discount = $('#discount').val();
            var serviceVat = $('#service_vat').val();
            var serviceDiscount = $('#service_discount').val();
            var paid = $('#paid').val();

            if (vat == '' || vat < 0 || !$.isNumeric(vat))
                vat = 0;

            if (discount == '' || discount < 0 || !$.isNumeric(discount))
                discount = 0;

            if (paid == '' || paid < 0 || !$.isNumeric(paid))
                paid = 0;

            if (serviceVat == '' || serviceVat < 0 || !$.isNumeric(serviceVat))
                serviceVat = 0;

            if (serviceDiscount == '' || serviceDiscount < 0 || !$.isNumeric(serviceDiscount))
                serviceDiscount = 0;

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();


                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq('+i+')').html('৳' + (quantity * unit_price).toFixed(2) );
                productSubTotal += quantity * unit_price;
            });

            $('.service-item').each(function(i, obj) {
                var quantity = $('.service_quantity:eq('+i+')').val();
                var unit_price = $('.service_unit_price:eq('+i+')').val();


                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.service-total-cost:eq('+i+')').html('৳' + (quantity * unit_price).toFixed(2) );
                serviceSubTotal += quantity * unit_price;
            });


            var productTotalVat = (productSubTotal * vat) / 100;
            var serviceTotalVat = (serviceSubTotal * serviceVat) / 100;


            $('#product-sub-total').html('৳' + productSubTotal.toFixed(2));
            $('#service-sub-total').html('৳' + serviceSubTotal.toFixed(2));

            $('#vat_total').html('৳' + productTotalVat.toFixed(2));
            $('#service_vat_total').html('৳' + serviceTotalVat.toFixed(2));

            var total = parseFloat(productSubTotal) + parseFloat(serviceSubTotal) +
                parseFloat(productTotalVat) + parseFloat(serviceTotalVat) -
                parseFloat(discount) - parseFloat(serviceDiscount);

            var due = parseFloat(total) - parseFloat(paid);
            $('#final-amount').html('৳' + total.toFixed(2));
            $('#due').html('৳' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));

            if (due > 0) {
                $('#tr-next-payment').show();
            } else {
                $('#tr-next-payment').hide();
            }
        }

        function initProduct() {
            $('.product, .warehouse, .product_item').select2();
        }

        function intSelect2(){
            $('.select2').select2()
            $('#account_head').select2({
                ajax: {
                    url: "{{ route('account_head_code.json') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        console.log(params);
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        console.log(response);
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            $('#account_head').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#account_head").index(this);
                $('#account_name:eq('+index+')').val(data.text);
            });

        }

    </script>
@endsection
