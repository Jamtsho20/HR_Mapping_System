@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
<a href="{{ url('expense/apply-expense/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Expense
    List</a>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>DSA Details</h6>
                </div>
            </div>
            @if ($oldDataFlag)
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Claim No <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $dsa->transaction_no }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel No <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $dsa->travel->transaction_no }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Advance No <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $dsa->dsaadvance->advance_application_id ?? config('global.null_value') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Advance Amount <span
                                        class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $dsa->total_amount ?? config('global.null_value')  }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Net Payable Amount <span
                                        class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $dsa->net_payable_amount ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Balance Amount <span
                                        class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $dsa->balance_amount }}</td>
                            </tr>


                            <tr>
                                <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> @php
                                    $attachments = json_decode($dsa->attachment, true); // Decode JSON to array
                                    @endphp

                                    @if (!empty($attachments) && is_array($attachments))
                                    @foreach ($attachments as $file)
                                    <a href="{{ asset($file) }}" class="btn btn-sm btn-primary mb-1"
                                        target="_blank">
                                        <i class="fas fa-file-alt"></i> View Attachment
                                    </a><br>
                                    @endforeach
                                    @else
                                    <span class="text-danger">No attachment available.</span>
                                    @endif

                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <br>

                    <div class="dataTables_scroll">
                        <div class="dataTables_scrollHead"
                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                            <div class="dataTables_scrollHeadInner"
                                style="box-sizing: content-box; padding-right: 0px;">
                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                    id="basic-datatable table-responsive">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="3%" class="text-center">#</th>

                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>From Location</th>
                                            <th>To Location</th>
                                            <th>Total Days</th>
                                            <th>Daily Allowance</th>
                                            <th>Travel Allowance</th>
                                            <th>Total Amount</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($dsa->dsaClaimDetails as $detail)
                                        <tr>
                                            <td class="text-center">
                                                {{ $detail->id }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($detail->from_date)->format('d-M-Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($detail->to_date)->format('d-M-Y') }}
                                            </td>
                                            <td>
                                                {{ $detail->from_location }}
                                            </td>
                                            <td>
                                                {{ $detail->to_location }}
                                            </td>
                                            <td>
                                                {{ $detail->total_days }}
                                            </td>
                                            <td>
                                                {{ $detail->daily_allowance }}
                                            </td>
                                            <td>
                                                {{ $detail->travel_allowance }}
                                            </td>
                                            <td>
                                                {{ $detail->total_amount }}
                                            </td>
                                            <td>
                                                {{ $detail->remark }}
                                            </td>

                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <table style="width:100%;" class="simple-table">

                <tbody>
                    <tr>
                        <th style="width:35%;">Claim No <span class="pull-right d-none d-sm-block">:</span>
                            &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ $dsa->transaction_no }}</td>
                    </tr>
                    <tr>
                        <th style="width:35%;">Travel No(s) <span class="pull-right d-none d-sm-block">:</span>
                            &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ $travelNosString }}</td>
                    </tr>
                    <tr>
                        <th style="width:35%;">Advance No(s) <span class="pull-right d-none d-sm-block">:</span>
                            &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ !empty($advanceNosString) ? $advanceNosString : config('global.null_value') }}</td>
                    </tr>
                    <tr>
                        <th style="width:35%;">Total Amount <span
                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ $dsa->amount ?? config('global.null_value')  }}</td>
                    </tr>
                    <tr>
                    <tr>
                        <th style="width:35%;">Advance Amount <span
                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ $dsa->advance_amount ?? config('global.null_value')  }}</td>
                    </tr>

                    <tr>
                        <th style="width:35%;">Net Payable Amount <span
                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ $dsa->net_payable_amount ?? config('global.null_value')  }}</td>
                    </tr>
                    <tr>
                        <th style="width:35%;">Balance Amount <span
                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ $dsa->balance_amount ?? config('global.null_value')  }}</td>
                    </tr>
                    <tr>
                        <th style="width:35%;">Total Number of Days <span
                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                        <td style="padding-left:25px;"> {{ $dsa->total_number_of_days ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th style="width:35%;">Applied On <span class="pull-right d-none d-sm-block">:</span>&nbsp;&nbsp;</th>
                        <td style="padding-left:25px;">
                            {{ \Carbon\Carbon::parse($dsa->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($dsa->created_at)->format('h:i A') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>
            <p class="info-green p-3 pt-0" style="text-indent: -.01em; padding-left: 1em;">
                <span style="">*</span>
                The "0.5" in the number of days represents either a half-day duration or a half-day allowance.
            </p>
            <div class="dataTables_scroll">
                <div class="dataTables_scrollHead"
                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                    <div class="dataTables_scrollHeadInner"
                        style="box-sizing: content-box; padding-right: 0px;">
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                            id="basic-datatable table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th width="3%" class="text-center">#</th>

                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>From Location</th>
                                    <th>To Location</th>
                                    <th>Total Days</th>
                                    <th>Daily Allowance</th>
                                    <th>Travel Allowance</th>
                                    <th>Total Amount</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($dsa->dsaClaimMappings as $detail)

                                <tr class="travel-auth-${travelAuthGroupClass} bg-light">

                                    <td colspan="4" class="text-center" style="color: black;  font-weight: bold;">
                                        <span name="dsa_claim_detail[${travel_authorizations.travelAuthorization.id}][travel_authorization_id]" data-value="${travel_authorizations.travelAuthorization.id}: ${travel_authorizations.advance_details ? travel_authorizations.advance_details.id : ''}">
                                            Travel Authorization Number: {{$detail->transaction_no}}
                                        </span>
                                    </td>
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold;">
                                        <span
                                            name="dsa_claim_detail[{{ $detail->travel_authorization_id ?? '' }}][advance_detail_id]"
                                            data-value="{{ $detail->transaction_no ? $detail->transaction_no : '' }}">
                                            {{ $detail->transaction_no
                                                    ? "Advance Number: {$detail->transaction_no}, Advance Amount: " . ($detail->advance_amount ?? 'N/A')
                                                    : 'Advance Number: N/A, Advance Amount: N/A'
                                                }}
                                        </span>
                                    </td>

                                    <td colspan="4" style="padding-left:25px;">
                                        @if ($detail->attachment)
                                        <a href="{{ asset(normalizePathForDisplay($detail->attachment))}}" class="btn btn-sm btn-primary mb-1" target="_blank">
                                            <i class="fas fa-file-alt"></i> View Attachment
                                        </a><br>
                                        @else
                                        <span class="text-danger">No attachment available.</span>
                                        @endif
                                    </td>

                                </tr>

                                @foreach ($detail->dsaDetails as $claimDetail )
                                <tr>
                                    <td class="text-center">
                                        {{ $claimDetail->id }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($claimDetail->from_date)->format('d-M-Y') }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($claimDetail->to_date)->format('d-M-Y') }}
                                    </td>
                                    <td>
                                        {{ $claimDetail->from_location }}
                                    </td>
                                    <td>
                                        {{ $claimDetail->to_location }}
                                    </td>
                                    <td>
                                        {{ $claimDetail->total_days }}
                                    </td>
                                    <td>
                                        {{ $claimDetail->daily_allowance }}
                                    </td>
                                    <td>
                                        {{ $claimDetail->travel_allowance }}
                                    </td>
                                    <td>
                                        {{ $claimDetail->total_amount }}
                                    </td>
                                    <td>
                                        {{ $claimDetail->remark ?? '-' }}
                                    </td>

                                </tr>
                                @endforeach

                                <tr class="travel-auth-${travelAuthGroupClass} last-row">
                                    <td colspan="1" class="text-center" style="color: black;">
                                    </td>
                                    <td colspan="1" class="text-center" style="color: black; font-weight: bold;">
                                        <span>
                                            Total Days:
                                        </span>
                                        <span class="days-span">
                                            {{$detail->number_of_days}}
                                        </span>
                                        <input type="hidden" id="total_days" name="total_days[{{$detail->travel_authorization_id}}]" value="{{$detail->number_of_days}}">
                                    </td>
                                    <td colspan="1" class="text-center" style="color: black;  font-weight: bold;">
                                        <span>
                                            Travel Authorization Amount:
                                        </span>
                                    </td>

                                    <td colspan="1" class="text-center" style="color: black;  font-weight: bold;">
                                        <input type="number" id="ta_amount" style="color: black;  font-weight: bold;" class="form-control" name="ta_amount[{{$detail->travel_authorization_id}}]" value="{{$detail->ta_amount}}" readonly>
                                        <input type="hidden" id="advance_amount" name="advance_amount[{{$detail->travel_authorization_id}}]" value="{{$detail->advance_amount ?? 0}}">
                                    </td>
                                    <td colspan="1" class="text-center" style="color: black;">

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                'applicationStatus' => $dsa->status
                ])

            </div>
        </div>
    </div>
</div>
</div>



@endsection
@push('page_scripts')
@endpush