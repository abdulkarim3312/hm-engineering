@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('title')
    Purchase Requisition Product
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Purchase Requisition Product Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{route('requisition_purchase_order',[$requisition->id])}}">
                    @csrf

                    <div class="card-body box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Requisition No.</th>
                                        <td>{{ $requisition->requisition_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Requisition Date</th>
                                        <td>{{ $requisition->date->format('d-m-Y, h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>AdminStatus</th>
                                        <th>
                                            @if($requisition->status == 0)
                                                <span class="badge badge-warning text-white" style="background: #FFC107; color:#000000;">Pending</span>
                                            @elseif($requisition->status == 1)
                                                <span class="badge badge-info text-white" style="background: #31D2F2;">Approved</span>
                                            @elseif($requisition->status == 3)
                                                <span class="badge badge-success text-white" style="background: #04D89D;">Delivered</span>
                                            @elseif($requisition->status == 4)
                                                <span class="badge badge-success text-white" style="background: #04D89D;">Received</span>
                                            @endif
                                        </th>
                                    <tr>
                                    <tr>
                                        <th>Accounts status</th>
                                        <th>
                                            @if($requisition->status == 0)
                                                <span class="badge badge-warning text-white" style="background: #FFC107; color:#000000;">Pending</span>
                                            @elseif($requisition->status == 1)
                                                <span class="badge badge-info text-white" style="background: #31D2F2;">Approved</span>
                                            @endif
                                        </th>
                                    <tr>
                                    @if($requisition->approved_note)
                                        <tr>
                                            <th>Requisition Approved Note</th>
                                            <td>{{ $requisition->approved_note }}</td>
                                        </tr>
                                    @endif
                                    @if($requisition->account_note)
                                        <tr>
                                            <th>Accounts Note</th>
                                            <td>{{ $requisition->account_note }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Requisition Info</th>
                                    </tr>
                                    <tr>
                                        <th>Project Name</th>
                                        <td>{{ $requisition->project->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Segment Name</th>
                                        <td>{{ $requisition->segment->name??''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $requisition->project->address?? '' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('supplier') ? 'has-error' :'' }}">
                                    <label for="supplier">Supplier</label>
                                    <select id="supplier" class="form-control select2 supplier" style="width: 100%;" name="supplier" data-placeholder="Select Supplier">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Req.Quantity</th>
                                    <th>Approve Quantity</th>
                                    <th>Approve Unit Price</th>
                                    <th>Remaining Quantity</th>
                                    <th>Purchase Quantity</th>
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
                                                    <input type="hidden" name="product_requisition_id[]"  value="{{ old('product_requisition_id.'.$loop->index) }}">
                                                    <input type="hidden" name="purchase_product[]"  value="{{ old('purchase_product.'.$loop->index) }}">
                                                    <input readonly type="text" value="{{ old('product.'.$loop->index) }}" name="product[]" class="form-control">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" readonly class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('approved_quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control approved_quantity" name="approved_quantity[]" value="{{ old('approved_quantity.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('approved_unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control approved_unit_price" name="approved_unit_price[]" value="{{ old('approved_unit_price.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('remaining_quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control remaining_quantity" name="unit_price[]" value="{{ old('remaining_quantity.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('purchase_quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control purchase_quantity" name="purchase_quantity[]" value="{{ old('purchase_quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('purchase_unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control purchase_unit_price" name="purchase_unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="total-cost">৳0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($requisition->requisitionDetails as $projectSegmentDetails)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group">
                                                    <input type="hidden" name="product_requisition_id[]"  value="{{ $projectSegmentDetails->id }}">
                                                    <input type="hidden" name="purchase_product[]"  value="{{ $projectSegmentDetails->purchase_product_id }}">
                                                    <input readonly type="text" name="product[]" value="{{ $projectSegmentDetails->name }}" class="form-control">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ $projectSegmentDetails->quantity ?? ''}}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control approved_quantity" name="approved_quantity[]" value="{{ $projectSegmentDetails->approved_quantity ?? ''}}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control approved_unit_price" name="approved_unit_price[]" value="{{ $projectSegmentDetails->approved_unit_price ?? ''}}" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control remaining_quantity" name="remaining_quantity[]" value="{{ $projectSegmentDetails->remaining_quantity ?? ''}}" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control purchase_quantity" name="purchase_quantity[]" value="{{ $projectSegmentDetails->remaining_quantity ?? ''}}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control purchase_unit_price" name="purchase_unit_price[]" value="{{ $projectSegmentDetails->unit_price ?? ''}}">
                                                </div>
                                            </td>

                                            <td class="total-cost">৳0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th colspan="7" class="text-right">Total Amount</th>
                                    <th id="total-amount">৳0.00</th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                                    <textarea name="note" class="form-control" rows="3" placeholder="Enter purchase note">{{ old('note') }}</textarea>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
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
                    <!-- /.box-body -->

                    <div class="card-footer box-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
        });
    </script>
    <script>
        $(function () {

            //Date picker
            $('#date').datepicker({
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

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('keyup', '.purchase_quantity, .purchase_unit_price,  #vat, #discount, #paid', function () {
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
                var approve_quantity = $('.approved_quantity:eq('+i+')').val();

                var quantity = $('.purchase_quantity:eq('+i+')').val();

                var unit_price = $('.purchase_unit_price:eq('+i+')').val();

                /*if(parseFloat(quantity) > parseFloat(approve_quantity)){
                    window.alert("Purchase Quantity can not more than Approved Quantity");
                    $('.purchase_quantity:eq('+i+')').val("");
                }*/

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
