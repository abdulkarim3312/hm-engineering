@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Purchase Order Edit
@endsection

@section('content')
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
                    <h3 class="box-title">Order Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('purchase_order_edit', ['order' => $order->id]) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('supplier') ? 'has-error' :'' }}">
                                    <label>Supplier</label>

                                    <select class="form-control select2" style="width: 100%;" name="supplier" data-placeholder="Select Supplier">
                                        <option value="">Select Supplier</option>

                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
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
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{   date('Y-m-d',strtotime($order->date)) }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('supplier_invoice') ? 'has-error' :'' }}">
                                    <label>Supplier's Invoice</label>

                                    <div class="form-group">
                                        <input type="file" class="form-control" id="supplier_invoice" value="{{ $order->supplier_invoice }}" name="supplier_invoice">
                                        @if ($order->supplier_invoice)
                                            <img src="{{ asset($order->supplier_invoice) }}" height="100px" width="100px" alt="">
                                        @endif
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
                                        <input type="text" class="form-control" id="note" name="note" value="{{ $order->note }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                                    <label>Project</label>

                                    <select class="form-control select2" style="width: 100%;" name="project" id="project" data-placeholder="Select Project">
                                        <option value="">Select Project</option>

                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ $order->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

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

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('requisition') ? 'has-error' :'' }}">
                                    <label>Requisition No</label>

                                    <select class="form-control requisition select2" style="width: 100%;" name="requisition" id="requisition" data-placeholder="Select Requisition">
                                        <option value="">Select Requisition</option>
                                    </select>

                                    @error('requisition')
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
                                        <th>Requisition Available</th>
                                        <th width="15%">Unit</th>
                                        <th width="15%">Quantity</th>
                                        <th width="15%">Unit Price</th>
                                        <th width="25%">Total Cost</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product" style="width: 100%;" name="product[]" required>
                                                        <option value="">Select Product</option>

                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ old('product.'.$loop->parent->index) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('approved_requisition.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control approved_requisition" name="approved_requisition[]"
                                                           value="{{ old('approved_requisition.'.$loop->index) }}" readonly>
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
                                    @foreach($order->products as $product)
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 product " style="width: 100%;" name="product[]" required>
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $item)
                                                        <option value="{{$item->id}}" {{$product->pivot->purchase_product_id == $item->id ? "selected" : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control approved_requisition" name="approved_requisition[]" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any" class="form-control unit" name="unit[]" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control quantity" value="{{$product->pivot->quantity}}" name="quantity[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control unit_price" value="{{$product->pivot->unit_price}}" name="unit_price[]">
                                            </div>
                                        </td>

                                        <td class="total-cost"> 0.00</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td>
                                            <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add Product</a>
                                        </td>
                                        <th class="text-right" colspan="4">Total</th>
                                        <th id="total-amount">  0.00 </th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="5">Discount:</th>
                                        <th id="discount">  {{ number_format($order->discount,2) }}</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="5">Paid:</th>
                                        <th id="paid" > {{ number_format($order->paid,2) }}</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="5">Due:</th>
                                        <th id="due"> 0.00</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="5">Refund:</th>
                                        <th id="refund_view"> 0.00</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
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

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control select2 product" style="width: 100%;" name="product[]" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control approved_requisition" name="approved_requisition[]" readonly>
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
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>

        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            //var selectedSegment = '{{ old('segment', $order->segment_id) }}';
            var selectedRequisition = '{{ old('requisition',$order->requisition_id) }}';

            {{--$('body').on('change', '#project', function () {--}}
            {{--    var projectId = $(this).val();--}}
            {{--    $('#segment').html('<option value="">Select Segment</option>');--}}
            {{--    $('#requisition').html('<option value="">Select Requisition</option>');--}}

            {{--    if (projectId != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('get_segment') }}",--}}
            {{--            data: { projectId: projectId }--}}
            {{--        }).done(function( response ) {--}}
            {{--            $.each(response, function( index, item ) {--}}
            {{--                if (selectedSegment == item.id)--}}
            {{--                    $('#segment').append('<option value="'+item.id+'" selected>'+item.name+'</option>');--}}
            {{--                else--}}
            {{--                    $('#segment').append('<option value="'+item.id+'">'+item.name+'</option>');--}}
            {{--            });--}}
            {{--            $('#segment').trigger('change');--}}
            {{--        });--}}
            {{--    }--}}
            {{--    $('#segment').trigger('change');--}}
            {{--});--}}
            {{--$('#project').trigger('change');--}}

            $('body').on('change', '#project', function () {
                var projectId = $(this).val();
                $('#requisition').html('<option value="">Select Requisition</option>');

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_requisition') }}",
                        data: { projectId:projectId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedRequisition == item.id)
                                $('#requisition').append('<option value="'+item.id+'" selected>'+item.requisition_no+'</option>');
                            else
                                $('#requisition').append('<option value="'+item.id+'">'+item.requisition_no+'</option>');
                        });
                        //$('#project').trigger('change');
                    });
                }
            });
            $('#project').trigger('change');

            $('body').on('change', '.product', function() {
                var productId = $(this).val();
                var projectId = $('#project').val();
                var requisitionId = $('#requisition').val();
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

                    // if(requisitionId == ''){
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Oops...',
                    //         text: 'Please Select Requisition..',
                    //     });
                    // }
                    if (requisitionId !='') {
                        $.ajax({
                            method: "GET",
                            url: "{{ route('get_requisition_approved') }}",
                            data: {
                                requisitionId: requisitionId,
                                productId: productId,
                                projectId: projectId,
                                segmentId: segmentId,
                            }
                        }).done(function(response) {
                            console.log(response);
                            itemProduct.closest('tr').find('.approved_requisition').val(response);
                            itemProduct.closest('tr').find('.quantity')
                                // .attr({
                                //     "max" : response,
                                //     "min" : 1
                                // });
                        });
                    }
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
                //
                // if ($('.product-item').length <= 1 ) {
                //     $('.btn-remove').hide();
                // }
            });

            $('body').on('keyup', '.quantity, .unit_price', function () {
                calculate();
            });


                $('.btn-remove').show();


            initProduct();
            calculate();
        });

        function calculate() {
            var total = 0;
            var paid = parseFloat('{{$order->paid}}');
            var discount = parseFloat('{{$order->discount}}');

            var refund = 0;
            if (paid == '' || paid < 0 || !$.isNumeric(paid))
                paid = 0;
            if (due == '' || due < 0 || !$.isNumeric(due))
                due = 0;
            if (refund == '' || refund < 0 || !$.isNumeric(refund))
                refund = 0;

            if (discount == '' || discount < 0 || !$.isNumeric(discount))
                discount = 0;

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


            due = parseFloat(total) - parseFloat(discount) - parseFloat(paid);
            if (due < 0) {
                var previousDue = due;
                due -= previousDue;
                refund = paid - total - due;
            }
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));
            $('#refund').val(refund.toFixed(2));

            $('#due').html(' ' + due.toFixed(2));
            $('#discount').html(' ' + discount.toFixed(2));
            $('#refund_view').html('' + refund.toFixed(2));
            $('#total-amount').html(' ' + total.toFixed(2));
        }

        function initProduct() {
            $('.product').select2();


        }
    </script>
@endsection
