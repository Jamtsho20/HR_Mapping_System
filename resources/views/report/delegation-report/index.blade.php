@extends('layouts.app')
@section('page-title', 'Delegation Report')
@section('content')

    {{-- <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('loan-report-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('loan-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('loan-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
        </div>
    </div> --}}

    <br>

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
            </div>
            <div class="col-3 form-group">
                <select name="employee_id" class="form-control select2 select2-hidden-accessible"
                    data-placeholder="Select Employee">
                    <option value="" disabled="" selected="" hidden="">Select Employee ID</option>
                    @foreach ($employee as $name)
                        <option value="{{ $name->id }}" {{ request()->get('employee_id') == $name->id ? 'selected' : '' }}>
                            {{ $name->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-3 form-group">
                <input type="text" name="cid_no" class="form-control" value="{{ request()->get('cid_no') }}"
                    placeholder="CID ID">
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Delegation Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="col-sm-12">

                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                                <thead class="thead-light">
                                                    <tr role="row">
                                                        <th>
                                                            #
                                                        </th>
                                                        <th>
                                                            Delegator
                                                        </th>
                                                        <th>
                                                            Role
                                                        </th>
                                                        <th>
                                                            Delegatee
                                                        </th>
                                                        <th>
                                                            Start Date
                                                        </th>
                                                        <th>
                                                            End Date
                                                        </th>
                                                        <th>
                                                            Remarks
                                                        </th>
                                                        <th>
                                                            Status
                                                        </th>


                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($delegations as $delegation)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $delegation->delegator->name ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $delegation->role->name ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $delegation->delegatee->name ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $delegation->start_date ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $delegation->end_date ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $delegation->remarks ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $delegation->status == 1 ? 'active' : 'inactive' ?? config('global.null_value') }}
                                                            </td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="21" class="text-center text-danger">No Delegation
                                                                Reports found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($delegations->hasPages())
                        <div class="card-footer">
                            {{ $delegations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>








@endsection
