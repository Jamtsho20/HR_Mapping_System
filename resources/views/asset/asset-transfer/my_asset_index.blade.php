@extends('layouts.app')
@section('page-title', 'Site Asset')
@section('content')
@include('layouts.includes.loader')

<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="block-header block-header-default">

    @component('layouts.includes.filter')
                <div class="col-6 form-group">
                    <select class="form-control" id="current_site_id" name="current_site_id">
                        <option value="" disabled selected hidden>Select Site</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}" {{ request()->get('current_site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 form-group">
                    <input placeholder="Serial Number" type="text" name="serial_number" class="form-control" value="{{ request()->get('serial_number') }}">
                </div>
            @endcomponent

    {{-- @component('layouts.includes.filter')
        <div class="col-6 form-group">
            <select class="form-control" id="req_type" name="req_type">
                <option value="" disabled selected hidden>Select transfer Type</option>
                @foreach ($transferTypes as $type)
                    <option value="{{ $type->id }}" {{ request()->get('req_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 form-group">
            <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
        </div>
    @endcomponent --}}


        @if(!$toBeTransferedToUserAsset->isEmpty())
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="dataTables_scroll">
                                    <div class="card-header">
                                        <h3 class="card-title">Transfers Awaiting Acknowledgement</h3>
                                    </div>
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                id="basic-datatable">
                                                <thead class="thead-light">
                                                    <tr role="row">
                                                        <th>#</th>
                                                        <th>TRANSFER NUMBER</th>
                                                        <th>TRANSFER TYPE</th>
                                                        <th>TRANSFER DATE</th>
                                                        <th>STATUS</th>
                                                        <th>ACKNOWLEDGE</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                @forelse ($toBeTransferedToUserAsset as $transfer )
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $transfer->transaction_no }}</td>
                                                        <td>{{ $transfer->transferType->name }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d-M-Y') }}</td>
                                                        <td class ="text-center">
                                                            @php
                                                            $statusClasses = [
                                                                -1 => 'badge bg-danger',
                                                                0 => 'badge bg-warning',
                                                                1 => 'badge bg-primary',
                                                                2 => 'badge bg-success',
                                                                3 => 'badge bg-info',
                                                            ];

                                                            $statusText = config(
                                                                "global.application_status.{$transfer->status}",
                                                                'Unknown Status',
                                                            );
                                                            $statusClass =
                                                                $statusClasses[$transfer->status] ??
                                                                'badge bg-secondary';
                                                            @endphp

                                                            <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                        </td>
                                                        <td>
                                                            <input
                                                            type="checkbox"
                                                            class="ack-checkbox"
                                                            data-id="{{ $transfer->id }}"
                                                            data-type="assettransfer"
                                                            {{ $transfer->received_acknowledged ? 'checked' : '' }}
                                                        >
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($privileges->view)
                                                                <a href="{{ url('asset/assets/' . $transfer->id) }}"
                                                                    class="btn btn-sm btn-outline-secondary"><i
                                                                        class="fa fa-list"></i> Detail</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                      <tr>
                                                        <td colspan="7" class="text-center text-info">No transfers awaiting acknowledgement.</td>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>

                                            @if ($transferedToUser->hasPages())
                                                <div class="card-footer">
                                                    {{ $transferedToUser->links() }}
                                                </div>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Site Asset List</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style=" overflow-y: auto;">
                            <table class="table table-condensed table-striped table-bordered table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Serial Number</th>
                                        <th>Item Description</th>
                                        <th>Cost</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siteAsset as $index => $asset)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{
                                                ($asset->receivedSerial?->requisitionDetail->grnItemDetail->item->item_no ?? '')
                                                .
                                                (($asset->receivedSerial?->requisitionDetail->grnItemDetail->item->item_no ?? null) && ($asset->receivedSerial?->asset_serial_no ?? $asset->serial_number) ? '-' : '')
                                                .
                                                ($asset->receivedSerial?->asset_serial_no ?? $asset->serial_number ?? config('global.null_value'))
                                            }}
                                        </td>
                                        <td>{{ $asset->item->item_description ?? $asset->sapAssets->item_description ?? config('global.null_value')}}</td>
                                        <td>{{ $asset->receivedSerial?->amount ?? $asset->sapAssets->amount ?? config('global.null_value') }}</td>
                                        <td>{{ $asset->receivedSerial?->quantity ?? $asset->sapAssets->quantity ?? config('global.null_value') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">No assets found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if ($siteAsset->hasPages())
                                <div class="card-footer">
                                    {{ $siteAsset->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    @include('layouts.includes.delete-modal')

@endsection

@push('page_scripts')

<script>
     document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ack-checkbox').forEach(checkbox => {
            checkbox.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior
                const transferId = this.dataset.id; // Get the transfer ID from the checkbox data attribute
                handleAcknowledgment(this, transferId); // Call the handler
            });
        });
    });
</script>
@endpush
