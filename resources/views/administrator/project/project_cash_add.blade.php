@extends('layouts.app')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
@endsection
@section('title')
    Cash
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
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
                    <h3 class="box-title">Cash Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('project.cash.edit',['cash'=>$cash->id]) }}">
                    @csrf

                    <div class="card-body">
                        @if (empty($cash))
                            <div class="form-group row {{ $errors->has('opening_balance') ? 'has-error' :'' }}">
                                <label class="col-sm-2 control-label"> Opening Balance <span style="color: red">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Opening Balance"
                                           name="opening_balance" value="{{ old('opening_balance') }}">
                                    @error('opening_balance')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="form-group row {{ $errors->has('amount') ? 'has-error' :'' }}">
                                <label class="col-sm-2 control-label"> Balance <span style="color: red">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Blance"
                                           name="amount" value="{{ old('amount', $cash->amount) }}" disabled>
                                    @error('amount')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('opening_balance') ? 'has-error' :'' }}">
                                <label class="col-sm-2 control-label"> Opening Blance <span style="color: red">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Opening Blance"
                                           name="opening_balance" value="{{ old('opening_balance', $cash->opening_balance) }}">
                                    @error('opening_balance')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                    </div>
                    <!-- /.box-body -->

                    <div class="card-footer" style="padding:15px 0px 15px 15px; background: #EEEEEE;">
                        <button type="submit" class="btn btn-primary"> Save </button>
                        <a href="{{ route('project_cash') }}" class="btn btn-primary"> Back </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
        });
    </script>
@endsection
