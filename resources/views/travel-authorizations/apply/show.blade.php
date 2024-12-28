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
                                    <th style="width:35%;">Travel Authorization Number<span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->travel_authorization_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Date<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $travelAuthorization->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Travel Type <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->travelType->name }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="table-responsive" style="margin-top: 20px; ">
                                            <table id="travel_details"
                                                class="table table-condensed table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>From Location</th>
                                                        <th>To Location</th>
                                                        <th>Mode of Travel</th>
                                                        <th colspan="2">Purpose</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($travelAuthorization->details as $index => $detail)
                                                        <tr>
                                                            <td>{{ $detail->from_date }}</td>
                                                            <td>{{ $detail->to_date }}</td>
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
                        <h6>Application Details</h6>
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
                                        {{ $travelAuthorization->status == 3 ? $travelAuthorization->travel_approved_by->name : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $travelAuthorization->status == -1 ? $travelAuthorization->travel_approved_by->name : 'N/A' }}
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
