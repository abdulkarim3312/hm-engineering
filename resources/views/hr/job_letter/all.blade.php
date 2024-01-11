@extends('layouts.app')

@section('title')
    Job Letter
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <a class="btn btn-primary" href="{{ route('job_letter_add') }}"> Job Letter </a>

                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th width="12%">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($jobLetter as $key => $item)
                                <tr>
                                    <td>{!! $item->letter_description ?? '' !!}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('job_letter_edit', $item->id) }}">Edit</a>
                                        <a class="btn btn-success btn-sm" href="{{ route('job_letter_view', $item->id) }}">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(function () {
            $('#table').DataTable();
        })
    </script>
@endsection
