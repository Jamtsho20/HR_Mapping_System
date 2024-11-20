@extends('layouts.app')
@section('page-title', 'View Leave Application')
@section('buttons')
<a href="{{ url('leave/leave-apply') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Leave List</a>
@endsection
@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Employee Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;">
                        <tbody>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Name <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $empDetails->name}}</td>
                            </tr>

                            <tr>
                                <th style="width:35%;">Department <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $empDetails->empJob->department->name}}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Section <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">{{ $empDetails->empJob->section->name}} </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Emp Id <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $empDetails->username}}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Email <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $empDetails->email}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
    <div class="col-lg-6">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Leave Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Leave Type <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->leaveType->name }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">From Date<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{$leave->from_date}}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">To Date <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$leave->to_date}}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">No. of Days<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$leave->no_of_days}}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Remarks<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->remarks }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> @if($leave->attachment)
                                    <a href="{{ asset($leave->attachment) }}" class="btn btn-sm btn-primary pull-right" target="_blank">
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
    <div class="col-lg-6">
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
                                <td style="padding-left:25px;"> </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> </td>
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