@extends('layouts.app')
@section('page-title', 'FA Commission Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('commission-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('commission-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('commission-report-print', Request::query()) }}" target="_blank"
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
                <label for="">Commission No:</label>
                <input type="text" class="form-control" name="comm_no"
                    value="{{ old('comm_no', request()->get('comm_no')) }}" placeholder="Comm No" />
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
                        <h3 class="card-title">FA Commission Report</h3>
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
                                                    Employee Name
                                                </th>
                                                <th>
                                                    Comm No
                                                </th>
                                                <th>
                                                    Comm Date
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
                                                    Dzongkhag
                                                </th>
                                                <th>
                                                    Date Placed In Service
                                                </th>
                                                <th>
                                                    Site
                                                </th>
                                                <th>
                                                    Remark
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Approved By
                                                </th>
                                                {{-- <th>
                                                    Action
                                                </th> --}}


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1; @endphp
                                            @forelse($commissions as $comm)
                                                @foreach ($comm->details as $detail)
                                                    <tr>
                                                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                                                        <td>{{ $comm->employee->emp_id_name }}</td>
                                                        <td>{{ $comm->transaction_no }}</td>
                                                        <td>{{ $comm->transaction_date }}</td>

                                                        {{-- Detail-specific data --}}
                                                        <td>{{ $detail->receivedSerial->asset_serial_no }}</td>
                                                        {{-- <td>{{ $detail->receivedSerial->asset_description }}</td> --}}
                                                        <td title="{{ $detail->receivedSerial->asset_description }}">
                                                            {{ \Illuminate\Support\Str::limit($detail->receivedSerial->asset_description, 25, '...') }}
                                                        </td>

                                                        <td>{{ $detail->receivedSerial->requisitionDetail->grnItemDetail->item->uom ?? '-' }}
                                                        </td>
                                                        <td class="text-right">1</td>
                                                        <td class="text-right">{{ $detail->receivedSerial->amount }}</td>
                                                        <td>{{ $detail->dzongkhag->dzongkhag }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($detail->date_placed_in_service)->format('d-M-Y') }}
                                                        </td>
                                                        <td>{{ $detail->site->name ?? '-' }}</td>
                                                        <td>{{ $detail->remark ?? '-' }}</td>

                                                        {{-- Parent-level status & approver repeated per row --}}
                                                        <td>{{ config("global.application_status.{$comm->status}", 'Unknown') }}
                                                        </td>
                                                        <td>{{ $comm->approvedBy->emp_id_name ?? '-' }}</td>
                                                        {{-- <td>
                                                            @if ($privileges->view)
                                                                <a href="{{ url('asset-report/commission-report/' . $comm->id) }}"
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
                    @if ($commissions->hasPages())
                        <div class="card-footer">
                            {{ $commissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>







@endsection
