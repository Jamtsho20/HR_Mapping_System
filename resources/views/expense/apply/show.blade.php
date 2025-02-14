@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
    <a href="{{ url('expense/apply-expense/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to
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
                                    <th style="width:35%;">Expense No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $expense->expense_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Expense Type <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $expense->type->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Date<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d-M-Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Expense Amount <span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $expense->amount }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Description<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $expense->description }}
                                    </td>
                                </tr>


                                <tr>
                                    <th style="width:35%;">Remarks<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $expense->remarks ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        @if ($expense->file)
                                            @php
                                                // Decode the JSON string into an array
                                                $attachments = json_decode($expense->file, true);
                                            @endphp
                                            <div class="flex">
                                                @foreach ($attachments as $attachment)
                                                    <a href="{{ asset($attachment) }}" class="btn btn-sm btn-primary"
                                                        target="_blank">
                                                        <i class="fas fa-file-alt"></i> View file
                                                    </a>
                                                @endforeach
                                            </div>
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

        @if ($expense->type_id == 5)
            <div class="tab-pane" id="vehiclefuelclaimsection">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="vehiclefuelclaimtable"
                                class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                                <thead>
                                    <tr role="row">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Initial (KM) Reading</th>
                                        <th>Final (KM) Reading</th>
                                        <th>Qty.(Ltrs.)</th>
                                        <th>Mileage</th>
                                        <th>Rate</th>
                                        <th>Amount (NU.)</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse (($expense->details) as $detail)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][id]"
                                                    class="form-control form-control-sm resetKeyForNew"
                                                    value="{{ $detail->id }}" />

                                                <input type="date"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][date]"
                                                    class="form-control form-control-sm resetKeyForNew"
                                                    value="{{ $detail->date }}" readonly />
                                            </td>
                                            <td class="text-center">
                                                <input type="number"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][initial_reading]"
                                                    class="form-control form-control-sm resetKeyForNew"
                                                    value="{{ $detail->initial_reading }}" readonly />
                                            </td>

                                            <td class="text-center">
                                                <input type="number"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][final_reading]"
                                                    class="form-control form-control-sm resetKeyForNew"
                                                    value="{{ $detail->final_reading }}" readonly />
                                            </td>
                                            <td class="text-center">
                                                <input type="text"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][quantity]"
                                                    class="form-control form-control-sm resetKeyForNew"
                                                    value="{{ $detail->quantity }}" readonly />
                                            </td>
                                            <td class="text-center">
                                                <input type="number"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][mileage]"
                                                    class="form-control form-control-sm resetKeyForNew"
                                                    value="{{ $detail->mileage }}" readonly />
                                            </td>

                                            <td class="text-center">
                                                <input type="number" min="0"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][rate]"
                                                    class="form-control form-control-sm resetKeyForNew notclearfornew"
                                                    value="{{ $detail->rate }}" readonly />
                                            </td>
                                            <td class="text-center">
                                                <input type="number" min="0"
                                                    name="fuel_claim_details[AAAAA{{ $detail->id }}][amount]"
                                                    value="{{ $detail->amount }}"
                                                    class="form-control form-control-sm resetKeyForNew" readonly />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="notremovefornew">
                                            <td colspan="7"></td>
                                            <td class="text-right">
                                                No Data Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($expense->type_id == 1)
            <div class="col-lg-12">
                <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">

                    <div class="row">
                        <div class="col-md-12">
                            <table style="width:100%;" class="simple-table">
                                <tbody>


                                    <tr>
                                        <th style="width:35%;">Travel Type <span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;"> {{ $expense->travelType->name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:35%;">Travel Mode <span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;">
                                            {{ config('global.travel_modes.' . $expense->travel_mode, 'Unknown') }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width:35%;">Travel From Date<span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;"> {{ $expense->travel_from_date }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:35%;">Travel To Date<span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;"> {{ $expense->travel_to_date }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:35%;">Travel From<span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;"> {{ $expense->travel_from }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:35%;">Travel To<span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;"> {{ $expense->travel_to }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:35%;">Travel Distance<span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
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
                        <h6>Document History</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('layouts.includes.approval-details', [
                            'approvalDetail' => $approvalDetail,
                            'applicationStatus' => $expense->status,
                            
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('page_scripts')
@endpush
