@extends('layouts.app')
@section('page-title', 'View SIFA Loan Disbursement Application')
@section('buttons')
<a href="{{ url('advance-loan/sifa-disburse') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')

<div class="row">
    @include('components.employee-details', ['empDetails' => $empDetails])
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Advance Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Advance No <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $advance->transaction_no }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Advance Type <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $advance->advanceType->name }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Net Payable<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ formatAmount($advance->net_payable )}}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Amount claimed<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ formatAmount($advance->amount )}}
                                </td>
                            </tr>
                            <th style="width:35%;">Total Amount with Interest<span class="pull-right d-none d-sm-block">:</span>&nbsp;&nbsp;</th>
                            <td style="padding-left:25px;">
                                @if (is_numeric($advance->total_amount))
                                {{ formatAmount(floatval($advance->total_amount), 2) }} {{-- Format as float with 2 decimal places --}}
                                @else
                                - {{-- Display "-" if it's not a valid number --}}
                                @endif
                            </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Previous Month Net Pay<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ formatAmount($advance->netPay) }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Interest Rate<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$advance->interest_rate }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">No. Of EMI<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$advance->no_of_emi }} months</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Monthly EMI Amount<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{$advance->monthly_emi_amount }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Deduction Period From<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ \Carbon\Carbon::parse($advance->deduction_from_period)->format('d-M-Y') }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Applied On <span class="pull-right d-none d-sm-block">:</span>&nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ \Carbon\Carbon::parse($advance->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($advance->created_at)->format('h:i A') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Remarks<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $advance->remarks ?? '-' }}</td>
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
                    'applicationStatus' => $advance->status,
                    ])

                </div>
            </div>
            <div class="text-center mt-4">
                @if ($advance->status == 3) {{-- Only show if current status is 3 --}}
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmDisbursementModal">
                    Proceed with Disbursement
                </button>
                @endif
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="confirmDisbursementModal" tabindex="-1" aria-labelledby="confirmDisbursementLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('sifa-disburse.disburse', $advance->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDisbursementLabel">Confirm Disbursement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to proceed with disbursement?<br><br>
                        <strong>Net Payable: </strong> {{ formatAmount($advance->net_payable) }}
                    </div>
                    <div class="modal-body">
                        <strong>Remarks: </strong><br> <textarea name="remarks" class="form-control" row="2" placeholder="Remark"></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Yes, Disburse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('page_scripts')
@endpush