@extends('layouts.app')
@section('page-title', 'GIS')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('gis-report-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('gis-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('gis-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
        </div>
    </div>

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
                        <h3 class="card-title">Group Insurance Scheme</h3>
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
                                                            EMployee Name
                                                        </th>
                                                        <th>
                                                            Policy Number
                                                        </th>
                                                        <th>
                                                            CID
                                                        </th>
                                                        <th>
                                                            DOB
                                                        </th>
                                                        <th>
                                                            Basic
                                                        </th>
                                                        <th>
                                                            GIS AMount
                                                        </th>
                                                        <th>
                                                            Date
                                                        </th>


                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($gisDeductions as $gis)
                                                        @php
                                                            $gsli = $gis->details['deductions']['GSLI'] ?? 0;
                                                        @endphp

                                                        @if ($gsli > 0)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $gis->employee->name ?? '-' }}</td>
                                                                <td>{{ $gis->employee->empJob->gis_policy_number ?? '-' }}
                                                                </td>
                                                                <td>{{ $gis->employee->cid_no ?? '-' }}</td>
                                                                <td>{{ $gis->employee->dob ?? '-' }}</td>
                                                                <td>{{ number_format($gis->employee->empJob->basic_pay ?? 0, 2) }}
                                                                </td>
                                                                <td>{{ $gsli }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($gis->for_month)->format('F Y') }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center text-danger">No GIS
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
                    @if ($gisDeductions->hasPages())
                        <div class="card-footer">
                            {{ $gisDeductions->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection
