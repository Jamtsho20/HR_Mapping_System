@extends('layouts.app')
@section('page-title', 'Travel Authorization Details')
@section('buttons')
    @if ($context === 'application')
        <a href="{{ route('apply-travel-authorization.index') }}" class="btn btn-primary">
            <i class="fa fa-reply"></i> Back to Travel Authorization List
        </a>
    @endif
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Travel Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Travel Authorization Number<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->transaction_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Date<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                       {{ \Carbon\Carbon::parse($travelAuthorization->date)->format('d-M-Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Travel Type <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->travelType->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Estimated Expense Amount <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->estimated_travel_expenses }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Total No of Day(s) <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->total_days ?? '-' }} day(s)</td>
                                </tr>
                                <tr>
                                <th style="width:35%;">Applied On <span class="pull-right d-none d-sm-block">:</span>&nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                {{ \Carbon\Carbon::parse($travelAuthorization->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($travelAuthorization->created_at)->format('h:i A') }}
                                </td>
                            </tr>

                                {{-- Display parent travel ID if it exists --}}
                                @if($travelAuthorization->old_travel_id)
                                    <tr>
                                        <th style="width:35%;">Parent Travel No (Extension)<span class="pull-right d-none d-sm-block">:</span>
                                            &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;">{{ $travelAuthorization->old_travel_id }}</td>
                                    </tr>
                                @endif
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
                        <h6>Travel Details </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if($travelAuthorization->parent_id)
                            <p class="info-green "><strong>Note:</strong> This travel authorization is an extension of the travel with Travel Authorization Number: <strong>{{ $parentNo}}</strong>.</p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style="margin-top: 20px; ">
                            <table id="travel_details" class="table table-condensed table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Number of Days</th>
                                        <th>From Location</th>
                                        <th>To Location</th>
                                        <th>Mode of Travel</th>
                                        <th colspan="2">Purpose</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($travelAuthorization->details as $index => $detail)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($detail->from_date)->format('d-M-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($detail->to_date)->format('d-M-Y') }}</td>
                                            <td>{{ $detail->number_of_days ?? '-' }}</td>
                                            <td>{{ $detail->from_location }}</td>
                                            <td>{{ $detail->to_location }}</td>
                                            <td>
                                                <p class="form-control-static">
                                                    {{ config('global.travel_modes')[$detail->mode_of_travel] ?? 'Unknown' }}
                                                </p>
                                            </td>
                                            <td colspan="2">{{ $detail->purpose }}</td>
                                        </tr>
                                    @endforeach
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
                        @include('layouts.includes.approval-details', [
                            'approvalDetail' => $approvalDetail,
                            'applicationStatus' => $travelAuthorization->status,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
