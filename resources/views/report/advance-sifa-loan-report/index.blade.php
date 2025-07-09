@extends('layouts.app')
@section('page-title', 'Advance SIFA Loan')
@section('content')


    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
              <a href="{{ route('advance-sifa-loan-report-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('advance-sifa-loan-report.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            </a>
        </div>
    </div>
    <br>
    
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <input type="month" name="year" class="form-control"
                    value="{{ request()->get('year', \Carbon\Carbon::now()->format('Y-m')) }}">
            </div>
            <div class="col-3 form-group">
                <select name="employee" class="form-control select2 select2-hidden-accessible"
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
                        <h3 class="card-title">Advance SIFA Loan Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="dataTables_scroll">
                                            <div class="dataTables_scrollHead"
                                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                <div class="dataTables_scrollHeadInner"
                                                    style="box-sizing: content-box; padding-right: 0px;">

                                                    <table
                                                        class="table table-bordered text-nowrap border-bottom dataTable no-footer">

                                                        <thead class="thead-light">
                                                            <tr role="row">
                                                                <th>
                                                                    #
                                                                </th>
                                                                <th>
                                                                    EMPLOYEE 
                                                                </th>
                                                                <th>
                                                                    NAME
                                                                </th>
                                                                <th>
                                                                    DESIGNATION
                                                                </th>
                                                                <th>
                                                                    DEPARTMENT
                                                                </th>
                                                                <th>
                                                                    LOCATION
                                                                </th>
                                                                <th>
                                                                    Advance Type
                                                                </th>
                                                                <th>
                                                                    DATE OF CLAIM
                                                                </th>
                                                                <th>
                                                                    AMOUNT
                                                                </th>
                                                                <th>
                                                                    Deduction Period From
                                                                </th>
                                                                <th>
                                                                    NO OF EMI
                                                                </th>
                                                                <th>
                                                                    EMI End Date
                                                                </th>
                                                                <th>
                                                                    Monthly EMI 
                                                                </th>
                                                                <th>
                                                                    Status
                                                                </th>
                                                                <th>
                                                                    DISBURSED BY
                                                                </th>
                                                                <th>
                                                                    DISBURSAL DATE
                                                                </th>
                                                                <th>
                                                                    VIEW 
                                                                </th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($advancesifaReports as $reports)
                                                                <tr>
                                                                    <td>{{ ($advancesifaReports->currentPage() - 1) * $advancesifaReports->perPage() + $loop->iteration }}
                                                                    </td>
                                                                    <td>{{ $reports->employee->username }}</td>
                                                                    <td>{{ $reports->employee->name }}</td>
                                                                    <td>{{ $reports->employee->empJob->designation->name }}
                                                                    </td>
                                                                    <td>{{ $reports->employee->empJob->department->name }}
                                                                    </td>
                                                                    <td>{{ $reports->employee->empJob->office->name }}</td>
                                                                    <td>{{ $reports->type->name }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->transaction_date)->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $reports->amount }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->deduction_from_period)->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $reports->no_of_emi }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->deduction_from_period)->addMonths($reports->no_of_emi - 1)->format('d-F-Y') }}
                                                                    </td>
                                                                    <td>{{ $reports->monthly_emi_amount }}</td>
                                                                    </td>
                                                                    @php
                                                                        $statusClasses = [
                                                                            -1 => 'Rejected',
                                                                            0 => 'Cancelled',
                                                                            1 => 'Submitted',
                                                                            2 => 'Verified',
                                                                            3 => 'Approved',
                                                                            4 => 'Disbursed',
                                                                        ];
                                                                        $statusText = config(
                                                                            "global.application_status.{$reports->status}",
                                                                            'Unknown Status',
                                                                        );
                                                                        $statusClass =
                                                                            $statusClasses[$reports->status] ??
                                                                            'badge bg-secondary';
                                                                    @endphp
                                                                    <td>

                                                                        {{ $statusText }}
                                                                    </td>
                                                                    <td>{{ $reports->advance_approved_by->name ?? '-' }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->updated_at)->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($privileges->view)
                                                                            <a href="{{ url('report/advance-sifa-loan-report/' . $reports->id) }}"
                                                                                class="btn btn-sm btn-outline-secondary"><i
                                                                                    class="fa fa-list"></i> Detail</a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="14" class="text-center text-danger">No
                                                                        Advance Loan report found</td>
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
                        </div>
                    </div>
                    @if ($advancesifaReports->hasPages())
                        <div class="card-footer">
                            {{ $advanceReports->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>







@endsection
