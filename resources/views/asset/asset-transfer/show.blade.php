@extends('layouts.app')
@section('page-title', 'Showing Transfer Application Details')
@section('buttons')
@php
    $currentPath = request()->path();

    if (Str::startsWith($currentPath, 'asset/assets/')) {
        $backUrl = url('asset/assets/');
    } elseif (Str::startsWith($currentPath, 'asset/asset-transfer/')) {
        $backUrl = url('asset/asset-transfer/');
    } else {
        $backUrl = url()->previous(); // fallback
    }
@endphp

<a href="{{ $backUrl }}" class="btn btn-primary">
    <i class="fa fa-reply"></i> Back to List
</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>FA Transfer Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Transfer No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->transaction_no }}</td>
                                </tr>

                                <tr>
                                    <th style="width:35%;">Transfer Date<span
                                            class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ \Carbon\Carbon::parse($transfer->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($transfer->created_at)->format('h:i A') }}
                                        {{-- {{ \Carbon\Carbon::parse($transfer->transaction_date)->format('d-M-Y') }} --}}
                                    </td>
                                </tr>
                            @if($transfer->type_id == SITE_SITE)
                                <tr>
                                    <th style="width:35%;">From Location <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->fromSite->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">To Location <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->toSite->name }}</td>
                                </tr>
                            @else
                                <tr>
                                    <th style="width:35%;">From Employee <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->fromEmployee->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">To Employee <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->toEmployee->name }}</td>
                                </tr>
                            @endif

                                <tr>
                                    <th style="width:35%;">Transfer Status <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->received_acknowledged ? 'Transfered' : 'Pending' }}</td>
                                </tr>

                                <tr>
                                    <th style="width:35%;">Attachment (s) <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        @if ($transfer->attachment)
                                            @php
                                                // Decode the JSON string into an array
                                                $attachments = json_decode($transfer->attachment, true);
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

        <div class="tab-pane" id="transfer">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="transfer-detail" class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                            <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Asset No</th>
                                    <th>Description</th>
                                    <th>UOM</th>
                                    <th>QTY</th>
                                    <th>Amount (Nu.)</th>
                                    <th>Date Placed In Service</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($transfer->details as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                          {{
                                                $detail->asset->receivedSerial
                                                    ? (
                                                        ($detail->asset->receivedSerial->requisitionDetail?->grnItemDetail->item->item_no
                                                            ? $detail->asset->receivedSerial->requisitionDetail->grnItemDetail->item->item_no . '-'
                                                            : ''
                                                        ) . $detail->asset->receivedSerial->asset_serial_no
                                                    )
                                                    : (
                                                        ($detail->asset->item_code
                                                            ? $detail->asset->item_code . '-'
                                                            : ''
                                                        ) . $detail->asset->serial_number
                                                    )
                                            }}

                                        </td>
                                        <td class="text-center">
                                            {{  $detail->asset->receivedSerial?->asset_description ?? $detail->asset->receivedSerial?->requisitionDetail->grnItemDetail->item->item_description ?? $detail->asset->sapAssets?->item_description }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->asset->receivedSerial?->requisitionDetail->grnItemDetail->item->uom ?? $detail->asset->sapAssets?->uom }}
                                        </td>
                                        <td class="text-right">{{ $detail->asset->receivedSerial?->quantity ?? $detail->sapAssets?->quantity ?? 1 }}</td>
                                        <td class="text-right">
                                            {{ $detail->asset->receivedSerial?->amount ?? $detail->asset->amount ?? $detail->asset->SapAssets?->amount }}
                                        </td>
                                        <td class="text-center">
                                           @php
                                                $datePlaced = $detail->asset->receivedSerial?->commissionDetail->date_placed_in_service
                                                    ?? $detail->asset->SapAssets?->capitalization_date;
                                            @endphp

                                            {{ $datePlaced ? \Carbon\Carbon::parse($datePlaced)->format('d-M-Y') : '' }}

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
                            'applicationStatus' => $transfer->status,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('page_scripts')
@endpush
