{{--@extends('layouts.app')--}}


{{--@section('title')--}}
{{--    Trading Purchase Order--}}
{{--@endsection--}}

{{--@section('content')--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="box">--}}
{{--                <div class="box-header with-border">--}}
{{--                    <h3 class="box-title">Order Information</h3>--}}
{{--                </div>--}}
{{--                <!-- /.box-header -->--}}
{{--                <!-- form start -->--}}
{{--                <form method="POST" action="{{ route('purchase_order.create') }}" enctype="multipart/form-data">--}}
{{--                    @csrf--}}

{{--                    <div class="box-body">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group {{ $errors->has('supplier') ? 'has-error' :'' }}">--}}
{{--                                    <label>Supplier</label>--}}

{{--                                    <select class="form-control select2" style="width: 100%;" name="supplier" data-placeholder="Select Supplier">--}}
{{--                                        <option value="">Select Supplier</option>--}}

{{--                                        @foreach($suppliers as $supplier)--}}
{{--                                            <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }} - {{ $supplier->company_name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}

{{--                                    @error('supplier')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">--}}
{{--                                    <label>Date</label>--}}

{{--                                    <div class="input-group date">--}}
{{--                                        <div class="input-group-addon">--}}
{{--                                            <i class="fa fa-calendar"></i>--}}
{{--                                        </div>--}}
{{--                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">--}}
{{--                                    </div>--}}
{{--                                    <!-- /.input group -->--}}

{{--                                    @error('date')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group {{ $errors->has('supplier_invoice') ? 'has-error' :'' }}">--}}
{{--                                    <label>Supplier's invoice</label>--}}

{{--                                    <div class="form-group">--}}
{{--                                        <input type="file" class="form-control" id="supplier_invoice" name="supplier_invoice" value="{{ old('supplier_invoice') }}">--}}
{{--                                    </div>--}}
{{--                                    <!-- /.input group -->--}}

{{--                                    @error('supplier_invoice')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">--}}
{{--                                    <label>Note</label>--}}

{{--                                    <div class="form-group">--}}
{{--                                        <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">--}}
{{--                                    </div>--}}
{{--                                    <!-- /.input group -->--}}

{{--                                    @error('note')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group {{ $errors->has('requisition') ? 'has-error' :'' }}">--}}
{{--                                    <label>Requisition No</label>--}}

{{--                                    <select class="form-control select2" style="width: 100%;" name="requisition" id="requisition" data-placeholder="Select Requisition">--}}
{{--                                        <option value="">Select Requisition</option>--}}

{{--                                        @foreach($requisitions as $requisition)--}}
{{--                                            <option value="{{ $requisition->id }}" {{ old('requisition') == $requisition->id ? 'selected' : '' }}>{{ $requisition->requisition_no }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}

{{--                                    @error('requisition')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group {{ $errors->has('segment') ? 'has-error' :'' }}">--}}
{{--                                    <label>Segment</label>--}}

{{--                                    <select class="form-control segment select2" style="width: 100%;" name="segment" id="segment" data-placeholder="Select Segment">--}}
{{--                                        <option value="">Select Segment</option>--}}
{{--                                    </select>--}}

{{--                                    @error('segment')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group {{ $errors->has('requisition') ? 'has-error' :'' }}">--}}
{{--                                    <label>Requisition No</label>--}}

{{--                                    <select class="form-control requisition select2" style="width: 100%;" name="requisition" id="requisition" data-placeholder="Select Requisition">--}}
{{--                                        <option value="">Select Requisition</option>--}}
{{--                                    </select>--}}

{{--                                    @error('requisition')--}}
{{--                                    <span class="help-block">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="table-responsive">--}}
{{--                            <table class="table table-bordered table-striped">--}}
{{--                                <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>Product Name</th>--}}
{{--                                        <th>Requisition Available</th>--}}
{{--                                        <th width="15%">Unit</th>--}}
{{--                                        <th width="15%">Quantity</th>--}}
{{--                                        <th width="15%">Unit Price</th>--}}
{{--                                        <th>Total Cost</th>--}}
{{--                                        <th></th>--}}
{{--                                    </tr>--}}
{{--                                </thead>--}}

{{--                                <tbody id="product-container">--}}
{{--                                @if (old('product') != null && sizeof(old('product')) > 0)--}}
{{--                                    @foreach(old('product') as $item)--}}
{{--                                        <tr class="product-item">--}}
{{--                                            <td>--}}
{{--                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">--}}
{{--                                                    <input type="text" step="any" name="product-name[]" class="form-control product-name"--}}
{{--                                                           value="{{ old('product-name.'.$loop->index) }}" readonly>--}}
{{--                                                    <input type="hidden" name="product[]" class="form-control product">--}}
{{--                                                </div>--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                <div class="form-group {{ $errors->has('approved_requisition.'.$loop->index) ? 'has-error' :'' }}">--}}
{{--                                                    <input type="number" step="any" class="form-control approved_requisition" name="approved_requisition[]"--}}
{{--                                                           value="{{ old('approved_requisition.'.$loop->index) }}" readonly>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                <div class="form-group {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}">--}}
{{--                                                    <input type="text" step="any" class="form-control unit" name="unit[]"--}}
{{--                                                           value="{{ old('unit.'.$loop->index) }}" readonly>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">--}}
{{--                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">--}}
{{--                                                </div>--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">--}}
{{--                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">--}}
{{--                                                </div>--}}
{{--                                            </td>--}}

{{--                                            <td class="total-cost"> 0.00</td>--}}
{{--                                            <td class="text-center">--}}
{{--                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                @else--}}
{{--                                    <tr class="product-item">--}}
{{--                                        <td>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <select class="form-control select2 product" style="width: 100%;" name="product[]" data-placeholder="Select Product" required>--}}
{{--                                                    <option value="">Select Product</option>--}}

{{--                                                </select>--}}

{{--                                                <input type="hidden" name="product-name[]" class="product-name">--}}
{{--                                            </div>--}}
{{--                                        </td>--}}

{{--                                        <td>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <input type="number" step="any" class="form-control approved_requisition" name="approved_requisition[]" readonly>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}

{{--                                        <td>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <input type="text" step="any" class="form-control unit" name="unit[]" readonly>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}

{{--                                        <td>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <input type="number" step="any" class="form-control quantity" name="quantity[]">--}}
{{--                                            </div>--}}
{{--                                        </td>--}}

{{--                                        <td>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <input type="text" class="form-control unit_price" name="unit_price[]">--}}
{{--                                            </div>--}}
{{--                                        </td>--}}

{{--                                        <td class="total-cost"> 0.00</td>--}}
{{--                                        <td class="text-center">--}}
{{--                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endif--}}
{{--                                </tbody>--}}

{{--                                <tfoot>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add Product</a>--}}
{{--                                        </td>--}}
{{--                                        <th colspan="5" class="text-right">Total Amount</th>--}}
{{--                                        <th id="total-amount"> 0.00 </th>--}}
{{--                                        <td></td>--}}
{{--                                    </tr>--}}
{{--                                </tfoot>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- /.box-body -->--}}

{{--                    <div class="box-footer">--}}
{{--                        <button type="submit" class="btn btn-primary">Save</button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <template id="template-product">--}}
{{--        <tr class="product-item">--}}
{{--            <td>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="text" step="any" name="product-name[]" class="form-control product-name" readonly>--}}
{{--                    <input type="hidden" name="product[]" class="form-control product">--}}
{{--                </div>--}}
{{--            </td>--}}

{{--            <td>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="number" step="any" class="form-control approved_requisition" name="approved_requisition[]" readonly>--}}
{{--                </div>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="text" step="any" class="form-control unit" name="unit[]" readonly>--}}
{{--                </div>--}}
{{--            </td>--}}

{{--            <td>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="number" step="any" class="form-control quantity" name="quantity[]">--}}
{{--                </div>--}}
{{--            </td>--}}

{{--            <td>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="text" class="form-control unit_price" name="unit_price[]">--}}
{{--                </div>--}}
{{--            </td>--}}

{{--            <td class="total-cost"> 0.00</td>--}}
{{--            <td class="text-center">--}}
{{--                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--    </template>--}}
{{--@endsection--}}

{{--@section('script')--}}

{{--    <script>--}}
{{--        $(function () {--}}
{{--            //Date picker--}}
{{--            $('#date').datepicker({--}}
{{--                autoclose: true,--}}
{{--                format: 'yyyy-mm-dd'--}}
{{--            });--}}

{{--            $('body').on('change', '#requisition', function (e) {--}}

{{--                    var requisitionId = $(this).val();--}}
{{--                    $this = $(this);--}}

{{--                    $('#product-container').html("");--}}

{{--                    if (requisitionId != '') {--}}
{{--                        $.ajax({--}}
{{--                            method: "GET",--}}
{{--                            url: "{{ route('requisition_product.details') }}",--}}
{{--                            data: {  requisitionId: requisitionId }--}}
{{--                        }).done(function( response ) {--}}
{{--                            $.each(response, function( index, single_item ) {--}}

{{--                                var html = $('#template-product').html();--}}
{{--                                var itemHtml = $(html);--}}

{{--                                $('#product-container').append(itemHtml);--}}
{{--                                //$("#dialog").empty().append(html);--}}

{{--                                 var item = $('.product-item').last();--}}
{{--                                 //item.hide();--}}

{{--                                item.closest('tr').find('.product-name').val(single_item.name);--}}
{{--                                item.closest('tr').find('.product').val(single_item.purchase_product_id);--}}
{{--                                item.closest('tr').find('.approved_requisition').val(single_item.approved_quantity);--}}
{{--                                item.closest('tr').find('.unit').val(single_item.unit.name);--}}
{{--                                item.closest('tr').find('.quantity').val(single_item.approved_quantity);--}}
{{--                                item.closest('tr').find('.quantity').attr({--}}
{{--                                    "max" : single_item.approved_quantity,--}}
{{--                                    "min" : 1--}}
{{--                                });--}}
{{--                               // item.closest('tr').find('.unit_price').val(response.data.selling_price);--}}
{{--                                //serials.push(response.data.serial);--}}

{{--                                //item.show();--}}
{{--                                calculate();--}}
{{--                                //$('#bar_code').val('');--}}
{{--                            });--}}
{{--                            // else if (response.success == false) {--}}
{{--                            //     $('#bar_code').val('');--}}
{{--                            //     Swal.fire({--}}
{{--                            //         icon: 'error',--}}
{{--                            //         title: 'Oops...',--}}
{{--                            //         text: response.message,--}}
{{--                            //     });--}}
{{--                            //--}}
{{--                            // }--}}
{{--                        });--}}
{{--                    }--}}
{{--                    return false; // prevent the button click from happening--}}

{{--            });--}}

{{--            --}}{{--var selectedSegment = '{{ old('segment') }}';--}}
{{--            --}}{{--var selectedRequisition = '{{ old('requisition') }}';--}}


{{--            --}}{{--$('body').on('change', '#project', function () {--}}
{{--            --}}{{--    var projectId = $(this).val();--}}
{{--            --}}{{--    $('#requisition').html('<option value="">Select Requisition</option>');--}}

{{--            --}}{{--    if (projectId != '') {--}}
{{--            --}}{{--        $.ajax({--}}
{{--            --}}{{--            method: "GET",--}}
{{--            --}}{{--            url: "{{ route('get_requisition') }}",--}}
{{--            --}}{{--            data: { projectId:projectId }--}}
{{--            --}}{{--        }).done(function( response ) {--}}
{{--            //             $.each(response, function( index, item ) {--}}
{{--            //                 if (selectedRequisition == item.id)--}}
{{--            //                     $('#requisition').append('<option value="'+item.id+'" selected>'+item.requisition_no+'</option>');--}}
{{--            //                 else--}}
{{--            //                     $('#requisition').append('<option value="'+item.id+'">'+item.requisition_no+'</option>');--}}
{{--            //             });--}}
{{--            --}}{{--        });--}}
{{--            --}}{{--    }--}}
{{--            --}}{{--});--}}
{{--            --}}{{--$('#project').trigger('change');--}}

{{--            $('body').on('change', '.product', function() {--}}
{{--                var productId = $(this).val();--}}
{{--                var projectId = $('#project').val();--}}
{{--                var requisitionId = $('#requisition').val();--}}
{{--                var segmentId = $('#segment').val();--}}
{{--                var itemProduct = $(this);--}}

{{--                if (productId != '') {--}}
{{--                    $.ajax({--}}
{{--                        method: "GET",--}}
{{--                        url: "{{ route('get_unit') }}",--}}
{{--                        data: {--}}
{{--                            productId: productId--}}
{{--                        }--}}
{{--                    }).done(function(response) {--}}
{{--                        itemProduct.closest('tr').find('.unit').val(response.name);--}}
{{--                    });--}}
{{--                    //--}}
{{--                    // if(requisitionId == ''){--}}
{{--                    //     Swal.fire({--}}
{{--                    //         icon: 'error',--}}
{{--                    //         title: 'Oops...',--}}
{{--                    //         text: 'Please Select Requisition..',--}}
{{--                    //     });--}}
{{--                    // }--}}
{{--                    if (requisitionId !='') {--}}
{{--                        $.ajax({--}}
{{--                            method: "GET",--}}
{{--                            url: "{{ route('get_requisition_approved') }}",--}}
{{--                            data: {--}}
{{--                                requisitionId: requisitionId,--}}
{{--                                productId: productId,--}}
{{--                                projectId: projectId,--}}
{{--                                segmentId: segmentId,--}}
{{--                            }--}}
{{--                        }).done(function(response) {--}}

{{--                            console.log(response);--}}

{{--                            itemProduct.closest('tr').find('.approved_requisition').val(response);--}}
{{--                            itemProduct.closest('tr').find('.quantity')--}}
{{--                                .attr({--}}
{{--                                    "max" : response,--}}
{{--                                    "min" : 1--}}
{{--                                });--}}
{{--                        });--}}

{{--                    }--}}
{{--                }--}}
{{--            });--}}

{{--            $('.product').trigger('change');--}}

{{--            $('#btn-add-product').click(function () {--}}
{{--                var html = $('#template-product').html();--}}
{{--                var item = $(html);--}}

{{--                $('#product-container').append(item);--}}

{{--                initProduct();--}}

{{--                if ($('.product-item').length >= 1 ) {--}}
{{--                    $('.btn-remove').show();--}}
{{--                }--}}
{{--            });--}}

{{--            $('body').on('click', '.btn-remove', function () {--}}
{{--                $(this).closest('.product-item').remove();--}}
{{--                calculate();--}}

{{--                if ($('.product-item').length <= 1 ) {--}}
{{--                    $('.btn-remove').hide();--}}
{{--                }--}}
{{--            });--}}

{{--            $('body').on('keyup', '.quantity, .unit_price', function () {--}}
{{--                calculate();--}}
{{--            });--}}

{{--            if ($('.product-item').length <= 1 ) {--}}
{{--                $('.btn-remove').hide();--}}
{{--            } else {--}}
{{--                $('.btn-remove').show();--}}
{{--            }--}}
{{--            initProduct();--}}
{{--            calculate();--}}
{{--        });--}}

{{--        function calculate() {--}}
{{--            var total = 0;--}}

{{--            $('.product-item').each(function(i, obj) {--}}
{{--                var quantity = $('.quantity:eq('+i+')').val();--}}
{{--                var unit_price = $('.unit_price:eq('+i+')').val();--}}

{{--                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))--}}
{{--                    quantity = 0;--}}

{{--                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))--}}
{{--                    unit_price = 0;--}}

{{--                $('.total-cost:eq('+i+')').html(' ' + (quantity * unit_price).toFixed(2) );--}}
{{--                total += quantity * unit_price;--}}
{{--            });--}}

{{--            $('#total-amount').html(' ' + total.toFixed(2));--}}
{{--        }--}}

{{--        function initProduct() {--}}
{{--            $('.product').select2({--}}
{{--                ajax: {--}}
{{--                    url: "{{ route('purchase_product.json') }}",--}}
{{--                    type: "get",--}}
{{--                    dataType: 'json',--}}
{{--                    delay: 250,--}}
{{--                    data: function (params) {--}}
{{--                        return {--}}
{{--                            searchTerm: params.term // search term--}}
{{--                        };--}}
{{--                    },--}}
{{--                    processResults: function (response) {--}}
{{--                        return {--}}
{{--                            results: response--}}
{{--                        };--}}
{{--                    },--}}
{{--                    cache: true--}}
{{--                }--}}
{{--            });--}}

{{--            $('.product').on('select2:select', function (e) {--}}
{{--                var data = e.params.data;--}}

{{--                var index = $(".product").index(this);--}}
{{--                $('.product-name:eq('+index+')').val(data.text);--}}
{{--            });--}}
{{--        }--}}
{{--    </script>--}}
{{--@endsection--}}


@extends('layouts.app')


@section('title')
    Trading Purchase Order
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('trading_purchase_order.create') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('supplier') ? 'has-error' :'' }}">
                                    <label>Supplier</label>

                                    <select class="form-control select2" style="width: 100%;" name="supplier" data-placeholder="Select Supplier">
                                        <option value="">Select Supplier</option>

                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }} - {{ $supplier->company_name }}</option>
                                        @endforeach
                                    </select>

                                    @error('supplier')
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
                                <div class="form-group {{ $errors->has('supplier_invoice') ? 'has-error' :'' }}">
                                    <label>Supplier's invoice</label>

                                    <div class="form-group">
                                        <input type="file" class="form-control" id="supplier_invoice" name="supplier_invoice" value="{{ old('supplier_invoice') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('supplier_invoice')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Note</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                                    <label>Project Name</label>

                                    <select class="form-control select2" style="width: 100%;" name="project" data-placeholder="Select project">
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
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="40%">Product Name</th>
                                    <th width="15%">Unit</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Unit Price</th>
                                    <th>Total Amount</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" style="width: 100%;" name="product[]" data-placeholder="Select Product" required>
                                                        <option value="">Select Product</option>

                                                        @if (old('product.'.$loop->index) != '')
                                                            <option value="{{ old('product.'.$loop->index) }}" selected>{{ old('product-name.'.$loop->index) }}</option>
                                                        @endif
                                                    </select>
                                                    <input type="hidden" name="product-name[]" class="product-name" value="{{ old('product-name.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control unit" name="unit[]"
                                                           value="{{ old('unit.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="total-cost"> 0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 product" style="width: 100%;" name="product[]" data-placeholder="Select Product" required>
                                                    <option value="">Select Product</option>

                                                </select>

                                                <input type="hidden" name="product-name[]" class="product-name">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any" class="form-control unit" name="unit[]" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control quantity" name="quantity[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control unit_price" name="unit_price[]">
                                            </div>
                                        </td>

                                        <td class="total-cost"> 0.00</td>
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
                                    <th colspan="4" class="text-right">Total Amount</th>
                                    <th id="total-amount"> 0.00 </th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
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
                    <select class="form-control select2 product" style="width: 100%;" name="product[]" data-placeholder="Select Product" required>
                        <option value="">Select Product</option>

                    </select>

                    <input type="hidden" name="product-name[]" class="product-name">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" step="any" class="form-control unit" name="unit[]" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control quantity" name="quantity[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control unit_price" name="unit_price[]">
                </div>
            </td>

            <td class="total-cost"> 0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')

    <script>
        $(function () {



            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('body').on('change', '.product', function() {
                var productId = $(this).val();
                var segmentId = $('#segment').val();
                var itemProduct = $(this);

                if (productId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_unit') }}",
                        data: {
                            productId: productId
                        }
                    }).done(function(response) {
                        itemProduct.closest('tr').find('.unit').val(response.name);
                    });
                }
            });

            $('.product').trigger('change');

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

            $('body').on('keyup', '.quantity, .unit_price', function () {
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
            var total = 0;

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq('+i+')').html(' ' + (quantity * unit_price).toFixed(2) );
                total += quantity * unit_price;
            });

            $('#total-amount').html(' ' + total.toFixed(2));
        }

        function initProduct() {
            $('.product').select2({
                ajax: {
                    url: "{{ route('purchase_product.json') }}",
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

            $('.product').on('select2:select', function (e) {
                var data = e.params.data;

                var index = $(".product").index(this);
                $('.product-name:eq('+index+')').val(data.text);
            });
        }
    </script>
@endsection
