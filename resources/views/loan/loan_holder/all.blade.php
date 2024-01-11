@extends('layouts.app')
@section('title')
    Loan Holder
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
                    <a class="btn btn-primary" href="{{ route('loan_holder.add') }}"> Add Loan Holder </a>

                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile </th>
                            <th>Email</th>
                            <th> Address </th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($loanHolders as $loanHolder)
                            <tr>
                                <td>{{ $loanHolder->name }}</td>
                                <td>{{ $loanHolder->mobile }}</td>
                                <td>{{ $loanHolder->email }}</td>
                                <td>{{ $loanHolder->address }}</td>
                                <td>
                                    @if ($loanHolder->status == 1)
                                        <span class="label label-success"> Active</span>
                                    @else
                                        <span class="label label-danger">Inactive </span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('loan_holder.edit', ['loanHolder' => $loanHolder->id]) }}"> Edit </a>
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
