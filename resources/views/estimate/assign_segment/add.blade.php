@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Assign Segment
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Assign Segment Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('assign_segment.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Estimate Project</label>

                                    <select class="form-control select2" style="width: 100%;" name="estimate_project" data-placeholder="Select Estimating Project">
                                        <option value="">Select Estimate Project</option>

                                        @foreach($estimateProjects as $estimateProject)
                                            <option value="{{ $estimateProject->id }}" {{ old('estimate_project') == $estimateProject->id ? 'selected' : '' }}>{{ $estimateProject->name??'' }}</option>
                                        @endforeach
                                    </select>

                                    @error('estimate_project')
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
                            <div class="col-md-6">
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
                                    <th>Configure Segment</th>
                                    <th width="15%">Height</th>
                                    <th width="15%">Width</th>
                                    <th width="15%">Length</th>
                                    <th width="15%">Segment Quantity</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="segment_configure-container">
                                @if (old('segment_configure') != null && sizeof(old('segment_configure')) > 0)
                                    @foreach(old('segment_configure') as $item)
                                        <tr class="segment_configure-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('segment_configure.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 segment_configure" style="width: 100%;" name="segment_configure[]" data-placeholder="Select Configure Segment" required>
                                                        <option value="">Select Configure Segment</option>
                                                        @foreach($segmentConfigures as $segmentConfigure)
                                                            <option value="{{ $segmentConfigure->id }}" {{ old('segment_configure.'.$loop->parent->index) == $segmentConfigure->id ? 'selected' : '' }}>{{ $segmentConfigure->costingSegment->name??'' }}-{{$segmentConfigure->segment_configure_no??''}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('height.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="height[]" class="form-control height" value="{{ old('height.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('width.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control width" name="width[]" value="{{ old('width.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control length" name="length[]" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('segment_quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control segment_quantity" name="segment_quantity[]" value="{{ old('segment_quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="segment_configure-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control segment_configure" style="width: 100%;" name="segment_configure[]" required>
                                                    <option value="">Select Configure Segment</option>

                                                    @foreach($segmentConfigures as $segmentConfigure)
                                                        <option value="{{ $segmentConfigure->id }}">{{ $segmentConfigure->costingSegment->name??'' }}-{{$segmentConfigure->segment_configure_no??''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="height[]" class="form-control height" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control width" name="width[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control length" name="length[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control segment_quantity" name="segment_quantity[]" value="1">
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
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-segment_configure">Add More</a>
                                    </td>
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

    <template id="template-segment_configure">
        <tr class="segment_configure-item">
            <td>
                <div class="form-group">
                    <select class="form-control segment_configure" style="width: 100%;" name="segment_configure[]" required>
                        <option value="">Select Configure Segment</option>

                        @foreach($segmentConfigures as $segmentConfigure)
                            <option value="{{ $segmentConfigure->id }}">{{ $segmentConfigure->costingSegment->name??'' }}-{{$segmentConfigure->segment_configure_no??''}}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="height[]" class="form-control height" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control width" name="width[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control length" name="length[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control segment_quantity" name="segment_quantity[]" value="1">
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

            $('#btn-add-segment_configure').click(function () {
                var html = $('#template-segment_configure').html();
                var item = $(html);

                $('#segment_configure-container').append(item);

                initProduct();

                if ($('.segment_configure-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.segment_configure-item').remove();
                calculate();

                if ($('.segment_configure-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('keyup', '.quantity', function () {
                calculate();
            });

            if ($('.segment_configure-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initProduct();
            calculate();
        });

        function calculate() {
            var total = 0;

            $('.segment_configure-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var height_price = $('.height-price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;
                if (height_price == '' || height_price < 0 || !$.isNumeric(height_price))
                    height_price = 0;

                $('.total-cost:eq('+i+')').html('৳ ' + parseFloat(quantity * height_price).toFixed(2));
                total += parseFloat(quantity * height_price);
            });

            $('#total-amount').html('৳ ' + total.toFixed(2));
        }

        function initProduct() {
            $('.segment_configure').select2();
            {{--$('.segment_configure').select2({--}}
            {{--    ajax: {--}}
            {{--        url: "{{ route('estimate_segment_configure.json') }}",--}}
            {{--        type: "get",--}}
            {{--        dataType: 'json',--}}
            {{--        delay: 250,--}}
            {{--        data: function (params) {--}}
            {{--            return {--}}
            {{--                searchTerm: params.term // search term--}}
            {{--            };--}}
            {{--        },--}}
            {{--        processResults: function (response) {--}}
            {{--            return {--}}
            {{--                results: response--}}
            {{--            };--}}
            {{--        },--}}
            {{--        cache: true--}}
            {{--    }--}}
            {{--});--}}

            {{--$('.segment_configure').on('select2:select', function (e) {--}}
            {{--    var data = e.params.data;--}}

            {{--    var index = $(".segment_configure").index(this);--}}
            {{--    $('.segment_configure-name:eq('+index+')').val(data.text);--}}
            {{--    $('.height-price:eq('+index+')').val(data.height_price);--}}
            {{--});--}}
        }
    </script>
@endsection
