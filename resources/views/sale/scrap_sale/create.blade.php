@extends('layouts.app')
@section('title')
    Scrap Sale
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Scrap Sale Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('scrap_sale.create') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            {{-- <div class="col-md-4">
                                <div class="form-group client_class {{ $errors->has('client') ? 'has-error' :'' }}">
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
                                <input type="hidden" name="client" value="0">
                            </div> --}}

                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div style="width: 100%" class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Note</label>
                                    <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">

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
                                    <th width="25%">Project</th>
                                    <th width="20%">Product</th>
                                    <th width="15%">Unit</th>
                                    <th width="15%">Stock</th>
                                    <th width="10%">Quantity</th>
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
                                                            <option value="{{ $project->id }}" {{ old('project.'.$loop->parent->index) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                                        @endforeach
                                                        <input type="hidden" name="project_id_hidden" value="{{ $project->id ?? '' }}">
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product select2" style="width: 100%;"  name="product[]" required
                                                            data-selected-product="{{ old('product.'.$loop->index) }}">
                                                        <option value="">Select Product</option>
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
                                                <div class="form-group {{ $errors->has('stock.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control stock" name="stock[]"
                                                           value="{{ old('stock.'.$loop->index) }}" readonly>
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
                                                <select class="form-control project select2" style="width: 100%;" name="project[]" required>
                                                    <option value="">Select Project</option>
                                                    @foreach($projects as $project)
                                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 product" style="width: 100%;" name="product[]">
                                                    <option value="">Select Product</option>
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
                                                <input type="text" step="any" class="form-control stock" name="stock[]" readonly>
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
                                            <option value="">Select Bank/Cash Account</option>
                                            @if (old('account_head') != '')
                                                <option value="{{ old('account_head') }}" selected>{{ old('account_name') }}</option>
                                            @endif
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
                                            <th colspan="4" class="text-right"> Sub Total</th>
                                            <th id="product-sub-total">0.00</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right"> Discount</th>
                                            <td>
                                                <div class="form-group {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control" name="discount" id="discount" value="{{ empty(old('discount')) ? ($errors->has('discount') ? '' : '0') : old('discount') }}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right">Total</th>
                                            <th id="final-amount">0.00</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right"> Paid</th>
                                            <td>
                                                <div class="form-group {{ $errors->has('paid') ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control" name="paid" id="paid" value="{{ old('paid',0) }}">
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
                    <select class="form-control project select2" style="width: 100%;" name="project[]" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select class="form-control select2 product" style="width: 100%;" name="product[]">
                        <option value="">Select Product</option>
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
                    <input type="text" step="any" class="form-control stock" name="stock[]" readonly>
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
            intSelect2();

            $('body').on('change','.project', function () {
                var projectId = $(this).val();
                var itemProject = $(this);
                itemProject.closest('tr').find('.product').html('<option value="">Select Product</option>');
                itemProject.closest('tr').find('.unit').val('');
                itemProject.closest('tr').find('.unit_price').val('');
                itemProject.closest('tr').find('.stock').val('');
                var productSelected = itemProject.closest('tr').find('.product').attr("data-selected-product");

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_scrap_product') }}",
                        data: { projectId: projectId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (productSelected == item.id)
                                itemProject.closest('tr').find('.product').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                itemProject.closest('tr').find('.product').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                        //itemProject.closest('tr').find('.product').trigger('change');
                    });
                }

            });
            $('.project').trigger('change');

            $('body').on('change', '.product', function() {
                var productId = $(this).val();
                var itemProduct = $(this);
                itemProduct.closest('tr').find('.unit').val('');
                itemProduct.closest('tr').find('.unit_price').val('');
                itemProduct.closest('tr').find('.stock').val('');
                var projectId = itemProduct.closest('tr').find('.project').val();

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
                            }
                        }).done(function(response) {
                            itemProduct.closest('tr').find('.unit_price').val(response.last_unit_price);
                            itemProduct.closest('tr').find('.stock').val(response.quantity);
                            itemProduct.closest('tr').find('.quantity')
                                .attr({
                                    "max" : response.quantity,
                                    "min" : 1
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
            $('body').on('keyup', '#discount, #paid', function () {
                calculate();
            });

            if ($('.project-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            calculate();

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
            var totalAmount = 0;
            var productSubTotal = 0;
            var discount = $('#discount').val();
            var paid = $('#paid').val() || 0;

            $('.project-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                if (discount == '' || discount < 0 || !$.isNumeric(discount))
                    discount = 0;

                if (paid == '' || paid < 0 || !$.isNumeric(paid))
                    paid = 0;

                $('.total-cost:eq('+i+')').html(' ' + (quantity * unit_price).toFixed(2) );
                totalAmount += quantity * unit_price;
                productSubTotal += quantity * unit_price;

                $('#total-amount').html(' ' + totalAmount.toFixed(2));
                $('#product-sub-total').html(' ' + productSubTotal.toFixed(2));
            });

            var total = parseFloat(productSubTotal) - parseFloat(discount);

            $('#paid').attr({
                "max" : total,
                "min" : 0
            });

            var due = parseFloat(total) - parseFloat(paid);

            $('#final-amount').html('৳' + total.toFixed(2));
            $('#total-amount').html('৳' + total.toFixed(2));
            $('#total').val(total);
            $('#due').html('৳' + due.toFixed(2));
        }

        function initProduct() {
            $('.project').select2();
            $('.product').select2();
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
            $('#account_head').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#account_head").index(this);
                $('#account_name:eq('+index+')').val(data.text);
            });

        }
    </script>
@endsection
