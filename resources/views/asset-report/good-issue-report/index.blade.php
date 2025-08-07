@extends('layouts.app')
@section('page-title', 'Good Issue Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('good-issue-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('good-issue-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('good-issue-report-print', Request::query()) }}" target="_blank"
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
            {{-- <div class="col-md-3 form-group">
                <label for="">Is Received:</label>
                <select class="form-control select" name="is_received">
                    <option value="" disabled {{ request()->get('is_received') === null ? 'selected' : '' }} hidden>Select Status</option>
                    <option value="1" {{ request()->get('is_received') === '1' ? 'selected' : '' }}>Received</option>
                    <option value="0" {{ request()->get('is_received') === '0' ? 'selected' : '' }}>Not Received</option>
                </select>
            </div> --}}

             <div class="col-md-3 form-group">
                <label for="">GIN</label>
                <input type="number" class="form-control" name="gin" value="{{ old('gin', request()->get('gin')) }}"
                    placeholder="GIN" />
            </div>

            <div class="col-md-3 form-group">
                <label for="">Req No:</label>
                <input type="number" class="form-control" name="req_no" value="{{ old('req_no', request()->get('req_no')) }}"
                    placeholder="Req No" />
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Good Issue Report</h3>
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
                                                   Goods Issued To
                                                </th>
                                                <th>
                                                    Department
                                                </th>
                                                <th>
                                                    Req No
                                                </th>
                                                <th>
                                                    GIN
                                                </th>
                                                <th>
                                                    Goods Issued From (Store)
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
                                                    Site
                                                </th>
                                                {{-- <th>
                                                    Capitalization Date
                                                </th>
                                                <th>
                                                    Is Received
                                                </th> --}}
                                                <th>
                                                    Remark
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1; @endphp
                                            @forelse($receivedSerials as $serials  )
                                                    <tr>
                                                        <td>{{ $count++ }}</td>
                                                        <td>{{ $serials->requisitionDetail->requisition->employee->emp_id_name ?? config('global.null_value')   }}</td>
                                                        <td>{{ $serials->requisitionDetail->requisition->employee->empJob->department->name ?? config('global.null_value')  }}</td>                                                {{-- Detail-specific data --}}

                                                        <td>{{ $serials?->requisitionDetail?->requisition?->transaction_no ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serials?->requisitionDetail?->requisition?->good_issue_doc_no ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serials?->requisitionDetail?->grnItemDetail?->store?->name ?? config('global.null_value')  }}</td>
                                                        <td>{{ $serials?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $serials?->asset_serial_no  ?? config('global.null_value')  }}</td>
                                                        <td title="{{ $serials?->asset_description }}">
                                                            {{ \Illuminate\Support\Str::limit($serials?->asset_description, 75, '...') }}
                                                        </td>

                                                        <td>{{ $serials?->requisitionDetail?->grnItemDetail?->item?->uom ?? config('global.null_value')  }}
                                                        </td>
                                                        <td class="text-right">{{$serials?->quantity ?? 1}}</td>
                                                        <td class="text-right">{{ $serials?->amount ?? config('global.null_value')  }}</td>
                                                        <td class="text-right">{{ $serials->requisitionDetail?->dzongkhag->dzongkhag ?? config('global.null_value')  }}</td>
                                                        <td class="text-right">{{ $serials->requisitionDetail?->site->name ?? config('global.null_value')  }}</td>
                                                        {{-- <td class="text-right">{{ $serials->commissionDetail?->date_placed_in_service ?? config('global.null_value')  }}</td>
                                                        <td class="text-right">{{ $serials->is_received ? 'Received' : 'Not Received' ?? config('global.null_value')  }}</td> --}}
                                                        <td class="text-right">{{ $serials->remark ?? config('global.null_value')  }}</td>

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
