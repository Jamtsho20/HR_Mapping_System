@extends('layouts.app')
@section('page-title', 'Tax Schedule')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('tax-schedule-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('tax-schedule-report-pdf.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('tax-schedule-report-print', Request::query()) }}" target="_blank"
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
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tax Schedule Report</h3>
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
                                                            Employee Name
                                                        </th>
                                                        <th>
                                                            TPN
                                                        </th>

                                                        <th>
                                                            CID
                                                        </th>
                                                        <th>
                                                            Basic Pay
                                                        </th>
                                                        <th>
                                                            Critical ALL
                                                        </th>
                                                        <th>
                                                            House ALL
                                                        </th>
                                                        <th>
                                                            Medical ALL
                                                        </th>
                                                        <th>
                                                            Corporate ALL
                                                        </th>
                                                        <th>
                                                            Cash ALL
                                                        </th>
                                                        <th>
                                                            Additional Work ALL
                                                        </th>

                                                        <th>
                                                            Health Tax
                                                        </th>
                                                        <th>
                                                            Salary Tax
                                                        </th>
                                                        <th>
                                                            Total Tax
                                                        </th>
                                                        <th>
                                                            Date
                                                        </th>


                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($taxSchedules as $pf)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $pf->employee->name }}</td>
                                                            <td>{{ $pf->employee->empJob->tpn_number }}</td>
                                                            <td>{{ $pf->employee->cid_no }}</td>
                                                            <td>{{ $pf->details['basic_pay'] ?? '0' }}</td>
                                                            <td>{{ $pf->details['allowances']['Critical Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $pf->details['allowances']['House Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $pf->details['allowances']['Medical Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $pf->details['allowances']['Corporate Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $pf->details['allowances']['Cash Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $pf->details['allowances']['Add. Work Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $pf->details['deductions']['H/Tax'] ?? '0' }}</td>
                                                            <td>{{ $pf->details['deductions']['Salary Tax'] ?? '0' }}</td>
                                                            <td>{{ ($pf->details['deductions']['Salary Tax'] ?: 0) + ($pf->details['deductions']['H/Tax'] ?: 0) }}
                                                            </td>
                                                            <td>{{ $pf->for_month }}</td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="13" class="text-center text-danger">No Tax
                                                                Schedule Reports found</td>
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
                    @if ($taxSchedules->hasPages())
                        <div class="card-footer">
                            {{ $taxSchedules->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection
