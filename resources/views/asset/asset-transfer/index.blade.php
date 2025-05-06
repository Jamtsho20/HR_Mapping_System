@extends('layouts.app')
@section('page-title', 'Asset Transfer')
@section('content')

    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('asset-transfer.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Apply Asset Transfer
            </a>
        @endsection
    @endif

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
                                                                <input type="checkbox" style="accent-color: primary; pointer-events: none;"
                                                                    {{ $transfer->received_acknowledged ? 'checked' : '' }}>
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
                                                @endforelse
                                                </tbody>
                                            </table>

                                            @if ($assetTransfer->hasPages())
                                                <div class="card-footer">
                                                    {{ $assetTransfer->links() }}
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
@endpush
