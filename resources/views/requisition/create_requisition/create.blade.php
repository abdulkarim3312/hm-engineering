@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Requisition
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Requisition Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('requisition.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                                    <label>Project</label>

                                    <select class="form-control project select2" style="width: 100%;" name="project" id="project">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Product Name</th>
{{--                                    <th width="15%">Available</th>--}}
                                    <th width="15%">Product Unit</th>
                                    <th width="15%">Quantity</th>
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
{{--                                            <td>--}}
{{--                                                <div class="form-group {{ $errors->has('available.'.$loop->index) ? 'has-error' :'' }}">--}}
{{--                                                    <input type="text"  name="available[]" class="form-control available" value="{{ old('available.'.$loop->index) }}" readonly>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
                                            <td>
                                                <div class="form-group {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="unit[]" class="form-control unit" value="{{ old('unit.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control product" style="width: 100%;" name="product[]" required>
                                                    <option value="">Select Product</option>

                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

{{--                                        <td>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <input type="text" name="available[]" class="form-control available" readonly>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="unit[]" class="form-control unit" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control quantity" name="quantity[]">
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add More</a>
                                    </td>
{{--                                    <th colspan="1">Total Quantity</th>--}}
{{--                                    <th id="total-quantity"> ৳ 0.00 </th>--}}
{{--                                    <td></td>--}}
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
                    <select class="form-control product" style="width: 100%;" name="product[]" required>
                        <option value="">Select Product</option>

                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
{{--            <td>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="text" name="available[]" class="form-control available" readonly>--}}
{{--                </div>--}}
{{--            </td>--}}
            <td>
                <div class="form-group">
                    <input type="text" name="unit[]" class="form-control unit" readonly>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" class="form-control quantity" name="quantity[]">
                </div>
            </td>

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
            var selectedSegment = '{{ old('segment') }}';

            $('body').on('change', '#project', function () {
                var projectId = $(this).val();
                $('#segment').html('<option value="">Select Segment</option>');

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_segment') }}",
                        data: { projectId: projectId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
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

            $('body').on('keyup', '.quantity', function () {
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
                var unit_price = $('.unit:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                //$('.total-cost:eq('+i+')').html('৳ ' + parseFloat(quantity ).toFixed(2));
                total += parseFloat(quantity);
            });

            $('#total-quantity').html('৳ ' + total.toFixed(2));
        }

        $('body').on('change', '.product', function () {
            var productId = $(this).val();
            //var projectId = $('#project').val();
            //var segmentId = $('#segment').val();
            var itemProduct = $(this);

            itemProduct.closest('tr').find('.available').val(0);
            // var selectedProduct = itemCategory.closest('tr').find('.product').attr("data-selected-product");
            if (productId != '') {
                $.ajax({
                    method: "GET",
                    url: "{{ route('requisition_product.json') }}",
                    data: {
                        productId: productId,
                        //projectId: projectId,
                        //segmentId: segmentId
                    }
                }).done(function (response) {

                    itemProduct.closest('tr').find('.unit').val(response.unit);
                    //itemProduct.closest('tr').find('.available').val(response.available);
                    //itemProduct.closest('tr').find('.quantity').attr('max',response.available);
                });
            }
        });

        $('.product').trigger('change');

        function initProduct() {
            $('.product').select2();
        }

        {{--function initProduct() {--}}
        {{--    $('.product').select2({--}}
        {{--        ajax: {--}}
        {{--            url: "{{ route('purchase_product.json') }}",--}}
        {{--            type: "get",--}}
        {{--            dataType: 'json',--}}
        {{--            delay: 250,--}}
        {{--            data: function (params) {--}}
        {{--                return {--}}
        {{--                    searchTerm: params.term // search term--}}
        {{--                };--}}
        {{--            },--}}
        {{--            processResults: function (response) {--}}
        {{--                return {--}}
        {{--                    results: response--}}
        {{--                };--}}
        {{--            },--}}
        {{--            cache: true--}}
        {{--        }--}}
        {{--    });--}}

        {{--    // $('.product').on('select2:select', function (e) {--}}
        {{--    //     var data = e.params.data;--}}
        {{--    //--}}
        {{--    //     var index = $(".product").index(this);--}}
        {{--    //     $('.product-name:eq('+index+')').val(data.text);--}}
        {{--    //     $('.unit:eq('+index+')').val(data.unit_price);--}}
        {{--    // });--}}
        {{--}--}}
    </script>
@endsection
