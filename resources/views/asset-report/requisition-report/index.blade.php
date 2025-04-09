@extends('layouts.app')
@section('page-title', 'Requisition Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('requisition-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('requisition-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('requisition-report-print', Request::query()) }}" target="_blank"
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
                <label for="">Req Type:</label>
                <select class="form-control select" data-placeholder="Select Stores" name="req_type">
                    <option value="" disabled="" selected="" hidden="">Select Requisition Type</option>
                    @foreach ($reqTypes as $type)
                        <option value="{{ $type->id }}" {{ request()->get('req_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="">Req No:</label>
                <input type="text" class="form-control" name="req_no" value="{{ old('req_no', request()->get('req_no')) }}"
                    placeholder="Req No" />
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
                        <h3 class="card-title">Requisition Report</h3>
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
                                                    Req Type
                                                </th>
                                                <th>
                                                    Req No
                                                </th>
                                                <th>
                                                    Req Date
                                                </th>
                                                <th>
                                                    GRN
                                                </th>
                                                <th>
                                                    Item Description
                                                </th>
                                                <th>
                                                    UOM
                                                </th>
                                                <th>
                                                    Store
                                                </th>
                                                <th>
                                                    Stock Status
                                                </th>
                                                <th>
                                                    Quantity Requested
                                                </th>
                                                <th>
                                                    Quantity Received
                                                </th>
                                                <th>
                                                    Dzongkhag
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
                                            @forelse($requisitions as $req)
                                                @foreach ($req->details as $detail)
                                                    <tr>
                                                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                                                        <td>{{ $req->employee->emp_id_name }}</td>
                                                        <td>{{ $req->type->name }}</td>
                                                        <td>{{ $req->transaction_no }}</td>
                                                        <td>{{ $req->transaction_date }}</td>

                                                        {{-- Detail-specific data --}}
                                                        <td>{{ $detail->grnItem->grn_no ?? config('global.null_value') }}
                                                        </td>
                                                        <td title="{{ $detail->grnItemDetail->item->item_description }}">
                                                            {{ \Illuminate\Support\Str::limit($detail->grnItemDetail->item->item_description, 25, '...') }}
                                                        </td>
                                                        <td>{{ $detail->grnItemDetail->item->uom ?? config('global.null_value') }}
                                                        </td>
                                                        <td>{{ $detail->grnItemDetail->store->name }}</td>
                                                        <td class="text-right">{{ $detail->grnItemDetail->quantity }}</td>
                                                        <td class="text-right">{{ $detail->requested_quantity }}</td>
                                                        <td class="text-right">{{ $detail->received_quantity }}</td>
                                                        <td>{{ $detail->dzongkhag->dzongkhag ?? config('global.null_value') }}
                                                        </td>
                                                        <td>{{ $detail->site->name ?? config('global.null_value') }}</td>
                                                        <td>{{ $detail->remark ?? config('global.null_value') }}</td>

                                                        {{-- Parent-level status & approver repeated per row --}}

                                                        <td>{{ config("global.application_status.{$req->status}", 'Unknown') }}
                                                        </td>
                                                        <td>{{ $req->approvedBy->emp_id_name ?? '-' }}</td>
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
                                                    <td colspan="17" class="text-danger text-center">No Data Found</td>
                                                </tr>
                                            @endforelse
                                            {{-- @forelse($requisitions as $req)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $req->employee->emp_id_name }}</td>
                                                    <td>{{ $req->type->name }}</td>
                                                    <td>{{ $req->transaction_no }}</td>
                                                    <td>{{ $req->transaction_date }}</td>
                                                    @php
                                                        $statusClasses = [
                                                            -1 => 'Rejected',
                                                            0 => 'Cancelled',
                                                            1 => 'Submitted',
                                                            2 => 'Verified',
                                                            3 => 'Approved',
                                                        ];
                                                        $statusText = config(
                                                            "global.application_status.{$req->status}",
                                                            'Unknown Status',
                                                        );
                                                        $statusClass =
                                                            $statusClasses[$req->status] ??
                                                            'badge bg-secondary';
                                                    @endphp
                                                    <td>
                                                        {{ $statusText }}
                                                    </td>

                                                    <td>
                                                        {{ $req->approvedBy->emp_id_name ?? '-' }}
                                                    </td>
                                                    {{-- <td>
                                                        @if ($privileges->view)
                                                            <a href="{{ url('asset-report/requisition-report/' . $req->id) }}"
                                                                class="btn btn-sm btn-outline-secondary"><i
                                                                    class="fa fa-list"></i> Detail</a>
                                                        @endif
                                                    </td> --}}
                                            {{-- </tr> --}}
                                            {{-- @empty
                                                <tr>
                                                    <td colspan="17" class="text-danger text-center">No Data Found</td>
                                                </tr>
                                            @endforelse --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($requisitions->hasPages())
                        <div class="card-footer">
                            {{ $requisitions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>







@endsection
