@extends('layouts.app')
@section('page-title', 'Showing Leave Encashment Details')
@section('buttons')
    <a href="{{ url('report/leave-encashment-report/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Leave
        Encashment Report</a>
@endsection
@section('content')
    <div class="row">
        @include('components.employee-details', ['empDetails' => $empDetails])

        <div class="col-lg-12">

            <div class="accordion" id="accordionExample" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#leaveEncashment" aria-expanded="true" aria-controls="leaveEncashment">
                            <strong> Leave Encashment Details</strong>
                        </button>
                    </h2>
                    <div id="leaveEncashment" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table style="width:100%;" class="simple-table">
                                        <tbody>
                                            <tr>
                                                <th style="width:35%;">Leave Encashed <span
                                                        class="pull-right d-none d-sm-block">:</span>
                                                    &nbsp;&nbsp;</th>
                                                <td style="padding-left:25px;"> {{ $leave->leave_applied_for_encashment }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width:35%;">EL Closing Balance<span
                                                        class="pull-right d-none d-sm-block">:</span>
                                                    &nbsp;&nbsp;</th>
                                                <td style="padding-left:25px;">
                                                    {{ $leave->employeeLeave->closing_balance }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width:35%;">Basic Pay <span
                                                        class="pull-right d-none d-sm-block">:</span>
                                                    &nbsp;&nbsp;</th>
                                                <td style="padding-left:25px;"> {{ $leave->amount }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                                        {{ $leave->status == 3 && $leave->updatedBy ? $leave->updatedBy->name : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $leave->status == -1 && $leave->updatedBy ? $leave->updatedBy->name : 'N/A' }}
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
