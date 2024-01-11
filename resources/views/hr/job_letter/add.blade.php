@extends('layouts.app')

@section('title')
    Job Letter Add
@endsection
@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Letter Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('job_letter_add') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="letter_type" value="1">
                    <div class="box-body">
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Letter Title</label>
                            <input type="file" name="job_letter_title" class="form-control">
                            @error('job_letter_title')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Job Letter Writing</label>
                            <textarea class="form-control" id="editor" name="letter_description"></textarea>
                            @error('letter_description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
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
<script src="https://cdn.ckeditor.com/4.19.1/standard-all/ckeditor.js"></script>
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            CKEDITOR.replace('body');
            CKEDITOR.replace('letter_description');
        });
    </script>
@endsection