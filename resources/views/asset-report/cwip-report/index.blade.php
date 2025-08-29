@extends('layouts.app')
@section('page-title', 'CWIP Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('cwip-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('cwip-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('cwip-report-print', Request::query()) }}" target="_blank"
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
            <input type="date" class="form-control" name="received_from_date"
                value="{{ old('received_from_date', request()->get('received_from_date')) }}" placeholder="From Date (Received date)"/>
        </div>
        <div class="col-md-3 form-group"><label for="">To Date:</label>
            <input type="date" class="form-control" name="received_to_date"
            value="{{ old('received_to_date', request()->get('received_to_date')) }}" placeholder="To Date">
        </div>
        <div class="col-md-3 form-group">
                <label for="">GRN:</label>
                <input type="text" class="form-control" name="grn"
                    value="{{ old('grn', request()->get('grn')) }}" placeholder="GRN" />
        </div>
        <div class="col-md-3 form-group">
            <label for="">Serial No:</label>
            <input type="text" class="form-control" name="serial_no"
                value="{{ old('serial_no', request()->get('serial_no')) }}" placeholder="Serial No" />
        </div>
        <div class="col-md-3 form-group">
                <label for="">Requistion No:</label>
                <input type="text" class="form-control" name="req_no"
                    value="{{ old('req_no', request()->get('req_no')) }}" placeholder="Comm No" />
            </div>


        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">CWIP Report</h3>
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
                                                   Asset Class Code
                                                </th>
                                                <th>
                                                    Asset Class Name
                                                </th>
                                                <th>
                                                    Requisition No.
                                                </th>
                                                <th>
                                                   GRN
                                                </th>
                                                <th>
                                                    Serial No
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
                                                    Goods Received Date
                                                </th>
                                                <th>
                                                    Cost
                                                </th>
                                                <th>
                                                    Issued From
                                                </th>
                                                <th>
                                                    Employee Code
                                                </th>
                                                <th>
                                                   Employee Name
                                                </th>
                                                <th>
                                                    Dzongkhag
                                                </th>
                                                <th>
                                                    Project Code
                                                </th>
                                                <th>
                                                    Project Name
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1; @endphp
                                            @forelse($receivedSerials as $serial)
                                                    <tr>
                                                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                                                        <td>{{ $serial->requisitionDetail?->grnItemDetail->item->item_group_id ?? config('global.null_value')  }}</td>
                                                        <td>{{ config('global.asset_class')[$serial->requisitionDetail?->grnItemDetail->item->item_group_id]
                                                            ?? $serial->requisitionDetail?->grnItemDetail->item->item_group_id
                                                            ?? config('global.null_value') }}
                                                        </td>
                                                        <td>{{ $serial->requisitionDetail?->requisition->transaction_no ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->grnItemDetail->grn->grn_no ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->grnItemDetail->item->item_no .'-'.$serial->asset_serial_no ?? config('global.null_value')  }}</td>
                                                        <td title="{{ $serial->asset_description }}">
                                                            {{ \Illuminate\Support\Str::limit($serial->asset_description, 50, '...') }}
                                                        </td>
                                                        <td>{{ $serial->requisitionDetail?->grnItemDetail->item->uom ?? config('global.null_value')  }}
                                                        </td>
                                                        <td class="text-right">{{$serial->quantity ?? 1}}</td>
                                                        <td>{{ $serial->requisitionDetail?->received_at ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->amount ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->grnItemDetail->store->code ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->requisition->employee->username ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->requisition->employee->name ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->dzongkhag->dzongkhag ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->site->code ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serial->requisitionDetail?->site->name ?? config('global.null_value')  }}</td>
                                                    </tr>

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
                    @if ($receivedSerials->hasPages())
                        <div class="card-footer">
                            {{ $receivedSerials->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>







@endsection
