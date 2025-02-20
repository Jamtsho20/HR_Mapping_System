@extends('layouts.app')
@section('page-title', 'View Leave Application')
@section('buttons')
<a href="{{ url('leave/leave-apply') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Leave List</a>
@endsection
@section('content')

<div class="row">
    @include('components.employee-details', ['empDetails' => $empDetails])
    <div class="col-lg-12">
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
                                <th style="width:35%;">Leave Type <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->leaveType->name }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">From Date <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ \Carbon\Carbon::parse($leave->from_date)->format('d-M-Y') }}
                                    ({{ config('global.leave_days')[$leave->from_day] ?? 'N/A' }})
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">To Date <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ \Carbon\Carbon::parse($leave->to_date)->format('d-M-Y') }}
                                    ({{ config('global.leave_days')[$leave->to_day] ?? 'N/A' }})
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">No. of Days <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->no_of_days }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Remarks <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->remarks ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    @if ($leave->attachment)
                                    <a href="{{ asset($leave->attachment) }}"
                                        class="btn btn-sm btn-primary pull-right" target="_blank">
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
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Document History</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.includes.approval-details', [
                    'approvalDetail' => $approvalDetail,
                    'applicationStatus' => $leave->status,
                    ])

                </div>
            </div>
        </div>
    </div>

</div>

@endsection
@push('page_scripts')
@endpush