@extends('layouts.app')
@section('page-title', 'Asset Transfer Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('good-transfer-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('good-transfer-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('good-transfer-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)">
                <span><i class="fa fa-print fa-lg"></i></span>
            </a>

        </div>
    </div>

    <br>
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-md-3 form-group">
                <label for="">From Date:</label>
                <input type="date" name="from_date" class="form-control" value="{{ request()->get('from_date') }}"
                    placeholder="From Date">
            </div>
            <div class="col-md-3 form-group">
                <label for="">To Date:</label>
                <input type="date" name="to_date" class="form-control" value="{{ request()->get('to_date') }}"
                    placeholder="To Date">
            </div>
            <div class="col-md-3 form-group">
                <label for="">Transfer Type:</label>
                <select class="form-control select" data-placeholder="Select Stores" name="type_id">
                    <option value="" disabled="" selected="" hidden="">Select Transfer Type</option>
                    <option value="1" {{ request()->get('type_id') == 1 ? 'selected' : '' }}>Employee-Employee</option>
                    <option value="2" {{ request()->get('type_id') == 2 ? 'selected' : '' }}>Site-Site</option>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="">Asset Transfer No:</label>
                <input type="text" class="form-control" name="transfer_no"
                    value="{{ old('transfer_no', request()->get('transfer_no')) }}" placeholder="Transfer No" />
            </div>
            <div class="col-md-3 form-group">
                <label for="">Status:</label>
                <select class="form-control select" name="status">
                    <option value="" disabled="" selected="" hidden="">Select Status</option>
                    <option value="3" {{ request()->get('status') == 3 ? 'selected' : '' }}>Approved</option>
                    <option value="-1" {{ request()->get('status') == -1 ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Asset Transfer Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="dataTables_scroll">
                            <div class="dataTables_scrollHead"
                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                <div class="dataTables_scrollHeadInner"
                                    style="box-sizing: content-box; padding-right: 0px;">
                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                                        <thead class="thead-light">
                                            <tr role="row">
                                                <th>
                                                    SL no
                                                </th>
                                                <th>
                                                    Transfer Type
                                                </th>
                                                <th>
                                                   Applicant
                                                </th>
                                                <th>
                                                    Department
                                                </th>
                                                <th>
                                                    Transfer No
                                                </th>
                                                <th>
                                                    Application Date
                                                </th>
                                                <th>
                                                    Asset No
                                                </th>
                                                <th>
                                                    Item Description
                                                </th>
                                                <th>
                                                    UOM
                                                </th>
                                                <th>
                                                    QTY
                                                </th>
                                                <th>
                                                    Amount (Nu.)
                                                </th>
                                                <th>
                                                    From Employee
                                                </th>
                                                <th>
                                                    To Employee
                                                </th>
                                                <th>
                                                    From Site
                                                </th>
                                                <th>
                                                    To Site
                                                </th>
                                                <th>
                                                    Capitalization Date
                                                </th>
                                                <th>
                                                    Reason of Transfer
                                                </th>
                                                <th>
                                                    Transfer Acknowledgement
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Approved By
                                                </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1; @endphp
                                            @forelse($assetTransferApplication as $transfer)
                                                @foreach ($transfer->details as $detail)
                                                    <tr>
                                                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                                                        <td>{{ $transfer->type_id === 1 ? 'Employee-Employee' : 'Site-Site' }}</td>
                                                        <td>{{ $transfer->employee->emp_id_name }}</td>
                                                        <td>{{ $transfer->employee->empJob->department->name ?? config('global.null_value')  }}</td>
                                                        <td>{{ $transfer->transaction_no }}</td>
                                                        <td>{{ $transfer->transaction_date }}</td>

                                                        {{-- Detail-specific data --}}
                                                        <td>{{ $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $detail->receivedSerial?->asset_serial_no }}</td>
                                                        {{-- <td>{{ $detail->receivedSerial->asset_description }}</td> --}}
                                                        <td title="{{ $detail->receivedSerial?->asset_description }}">
                                                            {{ \Illuminate\Support\Str::limit($detail->receivedSerial?->asset_description, 50, '...') }}
                                                        </td>

                                                        <td>{{ $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->uom ?? '-' }}
                                                        </td>
                                                        <td class="text-right">{{$detail->receivedSerial?->quantity ?? 1}}</td>
                                                        <td class="text-right">{{ $detail->receivedSerial?->amount }}</td>
                                                        <td class="text-right">{{ $transfer->fromEmployee->name ?? config('global.null_value')  }}</td>
                                                        <td class="text-right">{{ $transfer->toEmployee->name ?? config('global.null_value') }}</td>
                                                        <td class="text-right">{{ $transfer->fromSite->name ?? config('global.null_value')  }}</td>
                                                        <td class="text-right">{{ $transfer->toSite->name ?? config('global.null_value')  }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($detail->receivedSerial->commissionDetail->date_placed_in_service)->format('d-M-Y') ?? config('global.null_value')  }}
                                                        </td>
                                                        <td>{{ $transfer->reason_of_transfer ?? '-' }}</td>
                                                        <td>{{ $transfer->received_acknowledged ? 'Acknowledged' : 'Not Acknowledged'}}</td>
                                                        {{-- Parent-level status & approver repeated per row --}}
                                                        <td>{{ config("global.application_status.{$transfer->status}", 'Unknown') }}
                                                        </td>
                                                        <td>{{ $transfer->histories->last()->approvedBy->emp_id_name ?? '-' }}</td>
                                                        {{-- <td>
                                                            @if ($privileges->view)
                                                                <a href="{{ url('asset-report/asset-transfer-report/' . $comm->id) }}"
                                                                    class="btn btn-sm btn-outline-secondary"><i
                                                                        class="fa fa-list"></i> Detail</a>
                                                            @endif
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                            @empty
                                                <tr>
                                                    <td colspan="16" class="text-danger text-center">No Data Found</td>
                                                </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($assetTransferApplication->hasPages())
                        <div class="card-footer">
                            {{ $assetTransferApplication->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>







@endsection
