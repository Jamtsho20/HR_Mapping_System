@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
<a href="{{ url('expense/apply-expense/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Expense
    List</a>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Expense Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Expense No <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$expense->expense_no }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Expense Type <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$expense->type->name ??'-'}}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Date<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{$expense->date}}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Expense Amount <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$expense->amount }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Description<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{$expense->description}}
                                </td>
                            </tr>


                            <tr>
                                <th style="width:35%;">Remarks<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$expense->remarks ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> @if($expense->attachment)
                                    <a href="{{ asset($expense->attachment) }}" class="btn btn-sm btn-primary" target="_blank">
                                        <i class="fas fa-file-alt"></i> View Attachment
                                    </a>
                                    @else
                                    <span class="text-danger">No attachment available.</span>
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if($expense->mas_expense_type_id == 1)

    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">

            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>


                            <tr>
                                <th style="width:35%;">Travel Type <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$expense->travelType->name }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel Mode <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ config('global.travel_modes.' . $expense->travel_mode, 'Unknown') }} </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel From Date<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $expense->travel_from_date }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel To Date<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $expense->travel_to_date }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel From<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $expense->travel_from }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel To<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $expense->travel_to }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel Distance<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $expense->travel_distance }}</td>
                            </tr>




                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @endif
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Status</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Approved By <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{$expense->status == 3 ?$expense->expense_approved_by->name:'N/A'}}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$expense->status == -1 ?$expense->expense_approved_by->name:'N/A'}} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@push('page_scripts')

@endpush