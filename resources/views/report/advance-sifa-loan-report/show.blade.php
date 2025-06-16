@extends('layouts.app')
@section('page-title', 'Showing Advance SIFA Loan Details')
@section('buttons')
<a href="{{ url('report/advance-sifa-loan-report/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to
    Advance SIFA Loan Report</a>
@endsection
@section('content')
<div class="row">
    @include('components.employee-details', ['empDetails' => $empDetails])

    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <h6>SIFA Loan Repayment Schedule</h6>
                        <br>
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Repayment #</th>
                                    <th>Month</th>
                                    <th>Opening Balance</th>
                                    <th>EMI Amount</th>
                                    <th>Interest Charged</th>
                                    <th>Principal Repaid</th>
                                    <th>Closing Balance</th>
                                    <th>% Outstanding</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repayments as $repayment)
                                <tr>
                                    <td>{{ $repayment->repayment_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($repayment->month)->format('F Y') }}</td>
                                    <td>{{ number_format($repayment->opening_balance, 2) }}</td>
                                    <td>{{ number_format($repayment->monthly_emi_amount, 2) }}</td>
                                    <td>{{ number_format($repayment->interest_charged, 2) }}</td>
                                    <td>{{ number_format($repayment->principal_repaid, 2) }}</td>
                                    <td>{{ number_format($repayment->closing_balance, 2) }}</td>
                                    <td>{{ number_format($repayment->percentage_outstanding, 2) }}%</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No repayment records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                                <th style="width:35%;">Disbursed By <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ $advance->status == 4 ? $advance->advance_approved_by->name : 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ $advance->status == -1 ? $advance->advance_approved_by->name : 'N/A' }}
                                </td>
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