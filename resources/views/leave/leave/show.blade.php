@extends('layouts.app')
@section('page-title', 'View Leave Application')
@section('buttons')
<a href="{{ url('leave/leave-apply') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Leave List</a>
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Employee</b> <a class="pull-right">{{ $leave->employee->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Leave Type</b> <a class="pull-right">{{ $leave->leaveType->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Leave Balance</b> <a class="pull-right">{{ $leave->leave_balance }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>From Date</b> <a class="pull-right">{{ $leave->from_date }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>To Date</b> <a class="pull-right">{{ $leave->to_date }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>No of Days</b> <a class="pull-right">{{ $leave->no_of_days }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Remarks</b> <a class="pull-right">{{ $leave->remarks }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Attachment</b>
                                @if($leave->attachment)
                                    <a href="{{ asset($leave->attachment) }}" class="btn btn-sm btn-primary pull-right" target="_blank">
                                        <i class="fas fa-file-alt"></i> View Attachment
                                    </a>
                                @else
                                    <span class="pull-right">No attachment available.</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <ul class="list-group list-group-unbordered">

                    <li class="list-group-item">
                        <b>Approved By</b>
                        <a class="pull-right"></a>
                    </li>
                    <li class="list-group-item">
                        <b>Rejected By</b> <a class="pull-right"></a>

                    </li>


                </ul>
            </div>

        </div>
    </div>
</div>

@endsection
@push('page_scripts')

@endpush
