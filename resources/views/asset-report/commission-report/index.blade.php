@extends('layouts.app')
@section('page-title', 'FA Commission Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            {{-- <a href="{{ route('employee-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('employee-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('employee-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)">
                <span><i class="fa fa-print fa-lg"></i></span>
            </a> --}}

        </div>
    </div>

    <br>
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-md-3 form-group">
                <label for="">From Date:</label>
                <input type="date" name="from_date" class="form-control" value="{{ request()->get('from_date') }}" placeholder="From Date">
            </div>
            <div class="col-md-3 form-group">
                <label for="">To Date:</label>
                <input type="date" name="to_date" class="form-control" value="{{ request()->get('to_date') }}" placeholder="To Date">
            </div>
            <div class="col-md-3 form-group">
                <label for="">Commission No:</label>
                <input type="text" class="form-control" name="comm_no" value="{{ old('comm_no', request()->get('comm_no')) }}" placeholder="Comm No" />
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
                                                    Status
                                                </th>
                                                <th>
                                                    Approved By
                                                </th> 
                                                <th>
                                                    Action
                                                </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($commissions as $comm)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $comm->employee->emp_id_name }}</td>
                                                    <td>{{ $comm->transaction_no }}</td>
                                                    <td>{{ $comm->transaction_date }}</td>
                                                    @php
                                                        $statusClasses = [
                                                            -1 => 'Rejected',
                                                            0 => 'Cancelled',
                                                            1 => 'Submitted',
                                                            2 => 'Verified',
                                                            3 => 'Approved',
                                                        ];
                                                        $statusText = config(
                                                            "global.application_status.{$comm->status}",
                                                            'Unknown Status',
                                                        );
                                                        $statusClass =
                                                            $statusClasses[$comm->status] ??
                                                            'badge bg-secondary';
                                                    @endphp
                                                    <td>
                                                        {{ $statusText }}
                                                    </td>
                            
                                                    <td>
                                                        {{ $comm->approvedBy->emp_id_name ?? '-' }}
                                                    </td>
                                                    <td>
                                                        @if ($privileges->view)
                                                            <a href="{{ url('asset-report/commission-report/' . $comm->id) }}"
                                                                class="btn btn-sm btn-outline-secondary"><i
                                                                    class="fa fa-list"></i> Detail</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-danger text-center">No Commissions Data Found</td>
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
