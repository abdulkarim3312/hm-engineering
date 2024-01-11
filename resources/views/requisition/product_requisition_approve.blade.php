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
        .card-sub-title{
            font-size: 18px;
        }
    </style>
@endsection
@section('title')
    Product Requisition Approve
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="card-sub-title">Requisition Product Approved Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{route('product_requisition_approve',[$requisition->id])}}">
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
                                        <th>Status</th>
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
                                    @if($requisition->requisition_note)
                                        <tr>
                                            <th>Requisition Note</th>
                                            <td>{{ $requisition->requisition_note }}</td>
                                        </tr>
                                    @endif
                                    @if($requisition->approved_note)
                                        <tr>
                                            <th>Requisition Approved Note</th>
                                            <td>{{ $requisition->approved_note }}</td>
                                        </tr>
                                    @endif
                                    @if($requisition->delivered_note)
                                        <tr>
                                            <th>Requisition Delivered Note</th>
                                            <td>{{ $requisition->delivered_note }}</td>
                                        </tr>
                                    @endif
                                    @if($requisition->received_note)
                                        <tr>
                                            <th>Requisition Received Note</th>
                                            <td>{{ $requisition->received_note }}</td>
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th width="20%">Quantity</th>
                                    <th width="20%">Unit Price</th>
                                    <th>Total Cost</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('category') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group">
                                                    <input type="hidden" name="product_requisition_id[]"  value="{{ old('product_requisition_id.'.$loop->index) }}">
                                                    <input readonly type="text" value="{{ old('product.'.$loop->index) }}" name="product[]" class="form-control">
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
                                                    <input readonly type="text" name="product[]" value="{{ $projectSegmentDetails->name }}" class="form-control">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ $projectSegmentDetails->quantity ?? ''}}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ $projectSegmentDetails->unit_price ?? ''}}">
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
                                    <td>

                                    </td>
                                    <th colspan="2" class="text-right">Total Amount</th>
                                    <th id="total-amount">৳0.00</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th class="text-right">Approved Note</th>
                                    <th colspan="4">
                                        <div class="form-group">
                                            <input type="text" name="note" value="{{ old('note',$requisition->approved_note) }}" class="form-control" placeholder="Approved Note...">
                                        </div>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer box-footer">
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

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

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

                $('.total-cost:eq('+i+')').html('৳ ' + (quantity * unit_price).toFixed(2) );
                total += quantity * unit_price;
            });

            $('#total-amount').html('৳ ' + total.toFixed(2));
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
