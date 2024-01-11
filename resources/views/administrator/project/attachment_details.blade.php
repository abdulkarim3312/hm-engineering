@extends('layouts.app')

@section('title')
    Attachment View
@endsection

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- jquery validation -->
            @if($project->attachment == NULL)
                <div class="card card-outline card-primary">
                    <h1 style="color:red;text-align:center" >There is no file</h1>
                </div>
            @else
                <div class="card card-outline card-primary">
                    <iframe src="{{asset($project->attachment)}}" height="1000" width="1500" title="Document View">
                    </iframe>
                </div>
        @endif

        <!-- /.card -->
        </div>
        <!--/.col (left) -->
    </div>
@endsection
