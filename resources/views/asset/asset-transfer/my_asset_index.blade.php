@extends('layouts.app')
@section('page-title', 'Asset Transfer')
@section('content')
@include('layouts.includes.loader')

<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="block-header block-header-default">
    @component('layouts.includes.filter')
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
    @endcomponent

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
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
                                                        <th>TRANSFER NUMBER</th>
                                                        <th>TRANSFER TYPE</th>
                                                        <th>TRANSFER DATE</th>
                                                        <th>STATUS</th>
                                                        <th>ACKNOWLEDGED</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!$toBeTransferedToUserAsset->isEmpty())
                                                    <tr>
                                                        <td colspan="7" class="text-center text-info">Transfers Awaiting Acknowledgement</td>
                                                    </tr>
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

                                                @endforelse
                                                @endif
                                                @if(!$transferedToUser->isEmpty())
                                                <tr>
                                                    <td colspan="7" class="text-center text-info">Transfered To User</td>
                                                </tr>
                                                @forelse ($transferedToUser as $transfer)
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
                                                            <input type="checkbox" style="accent-color: primary; pointer-events: none;"
                                                                {{ $transfer->received_acknowledged ? 'checked' : '' }}>
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

                                                @endforelse
                                                @endif
                                                    {{-- <tr>
                                                        <td colspan="6" class="text-center text-info">Transfer Applications</td>
                                                    </tr>
                                                 @forelse ($assetTransfer as $transfer)

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
                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                    <a href="{{ url('asset/asset-transfer/' . $transfer->id) }}"
                                                                        class="btn btn-sm btn-outline-secondary"><i
                                                                            class="fa fa-list"></i> Detail</a>
                                                                @endif
                                                            </td>
                                                    <tr>

                                                    @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-danger">No Asset Transfer Found</td>
                                                </tr>
                                                @endforelse --}}
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
