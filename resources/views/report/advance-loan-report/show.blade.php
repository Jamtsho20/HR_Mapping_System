@extends('layouts.app')
@section('page-title', 'Showing Advance Loan Details')
@section('buttons')
    <a href="{{ url('report/advance-loan-report/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to
        Advance Loan Report</a>
@endsection
@section('content')
    <div class="row">

        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Advance Details</h6>
                    </div>
                </div>

                <div class="row">
                    @include('components.employee-details', ['empDetails' => $empDetails])

                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Advance No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $advance->advance_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Applied On<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ \Carbon\Carbon::parse($advance->date)->format('d-M-Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Advance Type <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $advance->advanceType->name }}</td>
                                </tr>

                                @if ($advance->type_id == 2)
                                    @include('advance-loan.approval.details.dsa-tour')
                                @endif

                                @if ($advance->type_id == 4)
                                    @include('advance-loan.approval.details.gadget')
                                @endif
                                @if ($advance->type_id == 6)
                                    @include('advance-loan.approval.details.salary')
                                @endif
                                @if ($advance->type_id == 7)
                                    @include('advance-loan.approval.details.sifa')
                                @endif



                                <tr>
                                    <th style="width:35%;">Amount<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $advance->amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Total Amount<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $advance->total_amount ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th style="width:35%;">Remarks<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $advance->remarks ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        @if ($advance->attachment)
                                            <a href="{{ asset($advance->attachment) }}"
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
                        @if ($advance->advance_type_id == 1)
                            @include('advance-loan.approval.details.advance-to-staff')
                        @endif

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
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Approved By <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $advance->status == 3 ? $advance->advance_approved_by->name : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $advance->status == -1 ? $advance->advance_approved_by->name : 'N/A' }} </td>
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
