@extends('layouts.app')

@section('style')
    <style>
        /*.form-group{*/
        /*    width: 140px;*/
        /*}*/
    </style>
@endsection

@section('title')
    Scrap Product Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Scrap Product Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('scrap_product.add') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div style="width: 100%" class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
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
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="25%">Project</th>
                                    <th width="25%">Product</th>
                                    <th width="20%">Unit</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Unit Price</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="project-container">
                                @if (old('project') != null && sizeof(old('project')) > 0)
                                    @foreach(old('project') as $item)
                                        <tr class="project-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('project.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control project select2" style="width: 100%;" name="project[]" required>
                                                        <option value="">Select Project</option>
                                                        @foreach($projects as $project)
                                                            <option value="{{ $project->project_id }}" {{ old('project.'.$loop->parent->index) == $project->project_id ? 'selected' : '' }}>{{ $project->project->name ?? ''}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product select2" style="width: 100%;"  name="product[]" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ old('product.'.$loop->parent->index) == $product->id ? 'selected' : '' }}>{{ $product->name ?? ''}}</option>
                                                        @endforeach
                                                    </select>
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
                                    <tr class="project-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control project" style="width: 100%;" name="project[]" required>
                                                    <option value="">Select Project</option>

                                                    @foreach($projects as $project)
                                                        <option value="{{ $project->project_id }}">{{ $project->project->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 product" style="width: 100%;" name="product[]">
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any" class="form-control unit" name="unit[]" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control quantity" name="quantity[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control unit_price" name="unit_price[]" value="0">
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
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-project">Add Product</a>
                                    </td>
                                    <th colspan="4" class="text-right">Total Amount</th>
                                    <th id="total-amount">  0.00 </th>
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

    <template id="template-project">
        <tr class="project-item">
            <td>
                <div class="form-group">
                    <select class="form-control project" style="width: 100%;" name="project[]" required>
                        <option value="">Select Project</option>

                        @foreach($projects as $project)
                            <option value="{{ $project->project_id }}">{{ $project->project->name ?? ''}}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control select2 product" style="width: 100%;" name="product[]">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" step="any" class="form-control unit" name="unit[]" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control unit_price" name="unit_price[]" value="0">
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

            $('.project').select2();
            $('.subcategory').select2();
            $('.product').select2();


            {{--$('body').on('change','.project', function () {--}}
            {{--    var projectId = $(this).val();--}}
            {{--    var itemProject = $(this);--}}
            {{--    itemProject.closest('tr').find('.segment').html('<option value="">Select Segment</option>');--}}
            {{--    var segmentSelected = itemProject.closest('tr').find('.segment').attr("data-selected-segment");--}}

            {{--    if (projectId != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('get_segment') }}",--}}
            {{--            data: { projectId: projectId }--}}
            {{--        }).done(function( data ) {--}}
            {{--            $.each(data, function( index, item ) {--}}
            {{--                if (segmentSelected == item.id)--}}
            {{--                    itemProject.closest('tr').find('.segment').append('<option value="'+item.id+'" selected>'+item.name+'</option>');--}}
            {{--                else--}}
            {{--                    itemProject.closest('tr').find('.segment').append('<option value="'+item.id+'">'+item.name+'</option>');--}}
            {{--            });--}}
            {{--            itemProject.closest('tr').find('.product').trigger('change');--}}
            {{--        });--}}
            {{--    }--}}

            {{--});--}}
            {{--$('.project').trigger('change');--}}


            $('body').on('change', '.product', function() {
                var productId = $(this).val();
                var itemProduct = $(this);
                var projectId = itemProduct.closest('tr').find('.project').val();
                var warehouseId = itemProduct.closest('tr').find('.warehouse').val();

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

                    if (productId !='') {
                        $.ajax({
                            method: "GET",
                            url: "{{ route('get_scrap_product_details') }}",
                            data: {
                                projectId: projectId,
                                productId: productId,
                                warehouseId:warehouseId
                            }
                        }).done(function(response) {

                            itemProduct.closest('tr').find('.sale_quantity').val(response.quantity);
                            itemProduct.closest('tr').find('.available_scrap_quantity').val(response.sale_due_scrap_quantity);
                            itemProduct.closest('tr').find('.unit_price').val(response.scrap_unit_price);
                            itemProduct.closest('tr').find('.quantity')
                                .attr({
                                    "max" : '',
                                    "min" : ''
                                });
                        });

                    }
                }
            });
            $('.product').trigger('change');

            $('#btn-add-project').click(function () {
                var html = $('#template-project').html();
                var item = $(html);

                $('#project-container').append(item);

                initProduct();

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

            $('body').on('keyup', '.quantity, .unit_price', function () {
                calculate();
            });

            if ($('.project-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }
            initProduct();
            calculate();
        });

        function calculate() {
            var total = 0;

            $('.project-item').each(function(i, obj) {
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
            $('.project').select2();
            $('.purchase_order').select2();
            $('.product').select2();
            // $('.project').on('select2:select', function (e) {
            //     var data = e.params.data;
            //
            //     var index = $(".project").index(this);
            //     $('.project-name:eq('+index+')').val(data.text);
            // });
        }
    </script>
@endsection
