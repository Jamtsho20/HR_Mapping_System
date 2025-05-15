@extends('layouts.app')
@section('page-title', 'FA Commission Details')
@section('buttons')
<a href="{{ url('asset-report/commission-report') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Commission List</a>
@endsection
@section('content')
<div class="row">
    @include('components.employee-details', ['empDetails' => $empDetails])
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>FA Commission Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Comm Number<span
                                        class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $commission->transaction_no }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Comm Date<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                   {{ \Carbon\Carbon::parse($commission->transaction_date)->format('d-M-Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Attachment (s) <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    @if ($commission->file)
                                        @php
                                            // Decode the JSON string into an array
                                            $attachments = json_decode($commission->file, true);
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
                            <tr>
                                <td colspan="2">
                                    <div class="table-responsive" style="margin-top: 20px; ">
                                        <table id="commission-detail" class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                                            <thead>
                                                <tr role="row">
                                                    <th>#</th>
                                                    <th>Asset No</th>
                                                    <th>Description</th>
                                                    <th>UOM</th>
                                                    <th>QTY</th>
                                                    <th>Amount (Nu.)</th>
                                                    <th>Dzongkhag</th>
                                                    <th>Date Placed In Service</th>
                                                    <th>Site</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                
                                                @forelse ($commission->details as $detail)
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $detail->receivedSerial->asset_serial_no }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $detail->receivedSerial->asset_description }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $detail->receivedSerial->requisitionDetail->grnItemDetail->item->uom }}
                                                        </td>
                                                        <td class="text-right">1</td>
                                                        <td class="text-right">
                                                            {{ $detail->receivedSerial->amount }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $detail->dzongkhag->dzongkhag }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($detail->date_placed_in_service)->format('d-M-Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $detail->site->name }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $detail->remark ?? config('global.null_value') }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td class="text-right">
                                                            No Data Found
                                                        </td>
                                                    </tr>
                                                @endforelse
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
</div>

@endsection
