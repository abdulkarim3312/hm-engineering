@extends('layouts.app')

@section('title')
    Utilize
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Utilize Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('purchase_product.utilize.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label" for="financial_year">Financial Year <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="financial_year" id="financial_year">
                                    <option value="">Select Year</option>
                                    @for($i=2022; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ old('financial_year') == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>
                                    @endfor
                                </select>
                                @error('financial_year')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project *</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" id="project" name="project">
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
{{--                        <div class="form-group {{ $errors->has('segment') ? 'has-error' :'' }}">--}}
{{--                            <label class="col-sm-2 control-label">Segment *</label>--}}
{{--                            <div class="col-sm-10">--}}
{{--                                <select class="form-control select2" id="segment" name="segment">--}}
{{--                                    <option value="">Select Segment</option>--}}
{{--                                </select>--}}
{{--                                @error('segment')--}}
{{--                                <span class="help-block">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group {{ $errors->has('warehouse') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Warehouse *</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="warehouse">
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option {{ old('warehouse') == $warehouse->id ? 'selected' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('product') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Product *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="product">
                                    <option value="">Select Product</option>

                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product') == $product->id ? 'selected' : '' }}>{{ $product->name.' ('.$product->unit->name.')' }}</option>
                                    @endforeach
                                </select>

                                @error('product')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('quantity') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Quantity *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Quantity"
                                       name="quantity" value="{{ old('quantity') }}">

                                @error('quantity')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Date *</label>

                            <div class="col-sm-10">
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

                        <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Note</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Note"
                                       name="note" value="{{ old('note') }}">

                                @error('note')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
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
@endsection

@section('script')

    <script>
        $(function () {
            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            var segmentSelected = '{{ old('segment') }}';

            $('#project').change(function () {
                var projectId = $(this).val();

                $('#segment').html('<option value="">Select Segment</option>');

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_segment') }}",
                        data: {projectId: projectId}
                    }).done(function (data) {
                        $.each(data, function (index, item) {
                            if (segmentSelected == item.id)
                                $('#segment').append('<option value="' + item.id + '" selected>' + item.name + '</option>');
                            else
                                $('#segment').append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                    });
                }
            });
            $('#project').trigger("change");
        });
    </script>
@endsection
