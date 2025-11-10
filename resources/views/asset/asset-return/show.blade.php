@extends('layouts.app')

@section('page-title', 'Asset Return Details')

@section('buttons')
<a href="{{ url('asset/asset-return/') }}" class="btn btn-primary">
    <i class="fa fa-reply"></i> Back to List
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- Asset Return Info -->
        <div class="col-sm-12 card" style="padding-top: 16px; padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Asset Return Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Asset Return No <span class="pull-right d-none d-sm-block">:</span>&nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">{{ $return->transaction_no }}</td>
                            </tr>
                            <tr>
                                <th>Return Date <span class="pull-right d-none d-sm-block">:</span>&nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ \Carbon\Carbon::parse($return->created_at)->format('d-M-Y') }}
                                    at
                                    {{ \Carbon\Carbon::parse($return->created_at)->format('h:i A') }}
                                </td>
                            </tr>
                            <tr>
                            <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span></th>
                            <td style="padding-left:25px;">
                                @if ($return->attachment)
                                    @php
                                        $files = json_decode($return->attachment, true); // Fixed field name from 'file' to 'attachment'
                                        $file = $files[0] ?? null;
                                    @endphp

                                    @if ($file)
                                        <a href="{{ asset($file) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-file-alt"></i> View Attachment
                                        </a>
                                    @else
                                        <p>No attachment available.</p>
                                    @endif
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

        <!-- Asset Return Table -->
        <div class="tab-pane" id="asset-return">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="asset-return-detail" class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                            <thead class="text-center">
                                <tr role="row">
                                    <th>#</th>
                                    <th>Asset No</th>
                                    <th>UOM</th>
                                    <th>Description</th>
                                    <th>QTY</th>
                                    <th>Dzongkhag</th>
                                    <th>Store</th>
                                    <th>Condition Code</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($return->details as $detail)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $detail->asset->asset_no }}</td>
                                    <td class="text-center">
                                        {{ $detail->asset->receivedSerial?->requisitionDetail->grnItemDetail->item->uom ?? $detail->asset->sapAssets?->uom }}
                                    </td>
                                    <td class="text-center">
                                            {{  $detail->asset->receivedSerial?->asset_description ?? $detail->asset->receivedSerial?->requisitionDetail->grnItemDetail->item->item_description ?? $detail->asset->sapAssets?->item_description }}
                                        </td>
                                     <td class="text-right">{{ $detail->asset->receivedSerial?->quantity ?? $detail->sapAssets?->quantity ?? 1 }}</td>
                                    <td class="text-center">
                                        {{ $detail->store->dzongkhag ?? config('global.null_value') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->store->name ?? config('global.null_value') }}
                                    </td>
                                    <td class="text-center">
                                        {{ config('global.asset_condition_codes')[$detail->condition_code] ?? config('global.null_value') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->remark ?? config('global.null_value') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-right text-muted">No Data Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval History -->
        <div class="col-sm-12 card" style="padding-top: 16px; padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Document History</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.includes.approval-details', [
                    'approvalDetail' => $approvalDetail,
                    'applicationStatus' => $return->status,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('page_scripts')
@endpush
