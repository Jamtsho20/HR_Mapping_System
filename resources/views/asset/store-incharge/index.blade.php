@extends('layouts.app')
@section('page-title', 'Store Incharge')
@section('content')
@include('layouts.includes.loader')

@if ($privileges->create)

@endif

<meta name="csrf-token" content="{{ csrf_token() }}">
@component('layouts.includes.filter')
                {{-- <div class="col-6 form-group">
                    <select class="form-control" id="current_site_id" name="current_site_id">
                        <option value="" disabled selected hidden>Select Site</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}" {{ request()->get('current_site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="col-6 form-group">
                    <input placeholder="Serial Number" type="text" name="serial_number" class="form-control" value="{{ request()->get('serial_number') }}">
                </div>
            @endcomponent
<div class="row row-sm">
    @if(!$toBeReturned->isEmpty())
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
               <h3 class="card-title">Returns Awaiting Acknowledgement</h3>
           </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="dataTables_scroll">
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
                                                <th>EMPLOYEE</th>
                                                <th>ASSET RETURN NUMBER</th>
                                                <th>RETURN DATE</th>
                                                <th>ACKNOWLEDGE </th>
                                                <th>STATUS</th>
                                                <th>VIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($toBeReturned as $application)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $application->employee->emp_id_name }}</td>
                                                <td>{{ $application->transaction_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($application->transaction_date)->format('d-M-Y') }}</td>
                                                <td class="text-center">

                                                        <input
                                                        type="checkbox"
                                                        class="ack-checkbox"
                                                        data-id="{{ $application->id }}"
                                                        data-type="return"
                                                        {{ $application->received_acknowledged ? 'checked' : '' }}
                                                    >
                                                    </td>
                                                <td class="text-center">
                                                    @php
                                                    $statusClasses = [
                                                    -1 => 'badge bg-danger',
                                                    0 => 'badge bg-warning',
                                                    1 => 'badge bg-primary',
                                                    2 => 'badge bg-success',
                                                    3 => 'badge bg-info',
                                                    ];
                                                    $statusText = config("global.application_status.{$application->status}", 'Unknown');
                                                    $statusClass = $statusClasses[$application->status] ?? 'badge bg-secondary';
                                                    @endphp
                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('store-incharge.show', $application->id) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-list"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            @endforelse

                                            {{-- @if(!$returned->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-info">Returned Assets</td>
                                            </tr>
                                            @forelse ($returned as $application)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $application->employee->emp_id_name }}</td>
                                                <td>{{ $application->transaction_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($application->transaction_date)->format('d-M-Y') }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" style="accent-color: primary; pointer-events: none;"
                                                        {{ $application->received_acknowledged ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                    $statusClasses = [
                                                    -1 => 'badge bg-danger',
                                                    0 => 'badge bg-warning',
                                                    1 => 'badge bg-primary',
                                                    2 => 'badge bg-success',
                                                    3 => 'badge bg-info',
                                                    ];
                                                    $statusText = config("global.application_status.{$application->status}", 'Unknown');
                                                    $statusClass = $statusClasses[$application->status] ?? 'badge bg-secondary';
                                                    @endphp
                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('store-incharge.show', $application->id) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-list"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty

                                            @endforelse
                                            @endif

                                            @if($toBeReturned->isEmpty() && $returned->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-info">No Returns Found</td>
                                            </tr>
                                            @endif --}}
                                        </tbody>

                                    </table>


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
                        <h3 class="card-title">Store Asset List</h3>
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
                                    @forelse($returnedAssets as $index => $asset)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $asset->receivedSerial?->requisitionDetail->grnItemDetail->item->item_no . '-' . $asset->receivedSerial?->asset_serial_no ?? config('global.null_value') }}
                                        </td>
                                        <td>{{ $asset->item->item_description }}</td>
                                        <td>{{ $asset->receivedSerial?->amount ?? $aset->sapAssets->amount ?? config('global.null_value') }}</td>
                                        <td>{{ $asset->receivedSerial?->quantity ?? $aset->sapAssets->quantity ?? config('global.null_value') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">No assets found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if ($returnedAssets->hasPages())
                                <div class="card-footer">
                                    {{ $returnedAssets->links() }}
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

@include('layouts.includes.delete-modal')

@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
       document.querySelectorAll('.ack-checkbox').forEach(checkbox => {
           checkbox.addEventListener('click', function (e) {
               e.preventDefault(); // Prevent default behavior
               const transferId = this.dataset.id; // Get the transfer ID from the checkbox data attribute
               console.log(transferId, this.dataset.type);
               handleAcknowledgment(this, transferId); // Call the handler
           });
       });
   });
</script>
@endpush
