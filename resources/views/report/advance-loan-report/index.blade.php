@extends('layouts.app')
@section('page-title', 'Advance Loan')
@section('content')


    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('advance-loan.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('advance-loan-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('advance-loan-print', Request::query()) }}" target="_blank" onclick="openPrintPreview(event)">
                <span><i class="fa fa-print fa-lg"></i></span>
            </a>

        </div>
    </div>
    <br>

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
            </div>

            <div class="col-md-2">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Employee"
                    name="employee">
                    <option value="" disabled="" selected="" hidden="">Select Employee</option>
                    @foreach ($employeeLists as $employee)
                        <option value="{{ $employee->id }}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-3">
                <select name="department" class="form-control select2 select2-hidden-accessible"
                    data-placeholder="Select Department">
                    <option value="" disabled="" selected="" hidden="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ request()->get('department') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-2">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Section" name="section">
                    <option value="" disabled selected hidden>Select Sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Advance Loan Report</h3>
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
                                                                    CODE
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
                                                                    ADVANCE LOAN TYPE
                                                                </th>
                                                                <th>
                                                                    Item type
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
                                                                    EMI Amount
                                                                </th>

                                                                <th>
                                                                    APPROVED BY
                                                                </th>
                                                                <th>
                                                                    APPROVAL DATE
                                                                </th>
                                                                <th>
                                                                    Action </th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($advanceReports as $reports)
                                                                <tr>
                                                                    <td>{{ ($advanceReports->currentPage() - 1) * $advanceReports->perPage() + $loop->iteration }}
                                                                    </td>
                                                                    <td>{{ $reports->employee->username }}</td>
                                                                    <td>{{ $reports->employee->name }}</td>
                                                                    <td>{{ $reports->employee->empJob->designation->name }}
                                                                    </td>
                                                                    <td>{{ $reports->employee->empJob->department->name }}
                                                                    </td>
                                                                    <td>{{ $reports->employee->empJob->office->name }}</td>
                                                                    <td>{{ $reports->type->name }}</td>
                                                                    <td>{{ $reports->item_type }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->date)->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $reports->amount }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->deduction_from_period)->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $reports->no_of_emi }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->deduction_from_period)->addMonths($reports->no_of_emi)->format('d-F-Y') }}
                                                                    </td>
                                                                    <td>{{ $reports->monthly_emi_amount }}</td>
                                                                    <td>{{ $reports->advance_approved_by->name ?? '-' }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($reports->updated_at)->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($privileges->view)
                                                                            <a href="{{ url('report/advance-loan-report/' . $reports->id) }}"
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
                    @if ($advanceReports->hasPages())
                        <div class="card-footer">
                            {{ $advanceReports->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>







@endsection
