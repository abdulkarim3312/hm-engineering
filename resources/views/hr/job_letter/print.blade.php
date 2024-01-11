@extends('layouts.app')
@section('title')
Job letter print
@endsection
@section('style')
    <style>

        table,.table,table td,

        .table-bordered{
            border: 1px solid #000000;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000000 !important;
        }
        .table.body-table td,.table.body-table th {
            padding: 2px 7px;
        }
        ul.document-list {margin: 0;padding: 0;list-style: auto;}

        ul.document-list li {display: inline-block;}
        .row {
            margin-right: 0;
            margin-left: 0;
        }
    </style>
@endsection
@section('content')
   <div class="row">
       <div class="col-md-12">
           <div class="box">
               <div class="box-header">
                   <a target="_blank" class="btn btn-default btn-lg" href="{{ route('job_letter_print', $letter->id) }}"><i class="fa fa-print"></i></a>
               </div>
               <div class="box-body">
                  
                   <div class="row" style="margin-top: 10px;">
                        @if($letter)
                        <td>{!! $letter->letter_description ?? '' !!}</td>
                        @endif
                   </div>
                  
                   {{-- <div class="row signature-area" style="margin-top: 30px">
                       <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Prepared By</span></div>
                       <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Checked By</span></div>
                       <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Approved By</span></div>
                       <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Received By</span></div>
                   </div> --}}
               </div>
           </div>
       </div>
   </div>

@endsection



