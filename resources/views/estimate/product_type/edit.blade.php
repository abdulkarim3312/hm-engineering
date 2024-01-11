@extends('layouts.app')

@section('title')
    Estimate Product Type Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('estimate_product_type.edit', ['estimateProductType' => $estimateProductType->id]) }}">
                    @csrf

                    <div class="box-body">

                        <div class="form-group {{ $errors->has('estimate_product') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Estimate Product *</label>

                            <div class="col-sm-10">
                                <select name="estimate_product" class="form-control select2">
                                    <option value="">Select Estimate Product</option>
                                    @foreach($estimateProducts as $estimateProduct)
                                        <option value="{{$estimateProduct->id}}" {{ empty(old('estimate_product')) ? ($errors->has('estimate_product') ? '' : ($estimateProductType->estimate_product_id == $estimateProduct->id ? 'selected' : '')) :
                                            (old('estimate_product') == $estimateProduct->id ? 'selected' : '') }}>{{$estimateProduct->name}}-{{$estimateProduct->unit->name}}</option>
                                    @endforeach
                                </select>

                                @error('estimate_product')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $estimateProductType->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('unit_price') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Unit Price *</label>

                            <div class="col-sm-10">
                                <input type="number" class="form-control" placeholder="Enter Unit Price"
                                       name="unit_price" step="any"
                                       value="{{ empty(old('unit_price')) ? ($errors->has('unit_price') ? '' : $estimateProductType->unit_price) : old('unit_price') }}">

                                @error('unit_price')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('description') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Description</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Description"
                                       name="description" value="{{ empty(old('description')) ? ($errors->has('description') ? '' : $estimateProductType->description) : old('description') }}">

                                @error('description')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($estimateProductType->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($estimateProductType->status == '0' ? 'checked' : '')) :
                                            (old('status') == '0' ? 'checked' : '') }}>
                                        Inactive
                                    </label>
                                </div>

                                @error('status')
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
