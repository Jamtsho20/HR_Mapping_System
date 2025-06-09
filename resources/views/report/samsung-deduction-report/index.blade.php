@extends('layouts.app')
@section('page-title', 'Samsung Deduction Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('samsung-deduction-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('samsung-deduction-report-pdf.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('samsung-deduction-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
        </div>
    </div>

    <br>

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-md-3 form-group">
                <input type="text" class="form-control" name="date" id="date-range-picker"
                    value="{{ request()->get('date') }}" placeholder=" Date (From - To)">
            </div>

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
                        <h3 class="card-title">Samsung Deduction Report</h3>
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
                                                            Applied On
                                                        </th>
                                                        <th>
                                                            Employee Name
                                                        </th>
                                                        <th>
                                                            Employee ID
                                                        </th>
                                                        <th>
                                                            Designation
                                                        </th>
                                                        <th>
                                                            Department
                                                        </th>
                                                        <th>
                                                            Region 
                                                        </th>
                                                        <th>
                                                            Office Location
                                                        </th>
                                                        <th>
                                                            loan type
                                                        </th>
                                                        <th>
                                                            Loan number
                                                        </th>
                                                        <th>
                                                            Item Type/Device Code
                                                        </th>
                                                        <th>
                                                            Start Date
                                                        </th>
                                                        <th>
                                                            End Date
                                                        </th>
                                                        <th>
                                                            No of Installments (Months)
                                                        </th>
                                                        <th>
                                                            For Month
                                                        </th>
                                                        <th>
                                                            Monthly Installment (Nu.)
                                                        </th>
                                                        <th>
                                                            Amount Paid (Nu.)
                                                        </th>
                                                        <th>
                                                            Approved By
                                                        </th>
                                                        <th>
                                                            Approved On
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php $iteration = 1; @endphp
                                                    @forelse($paySlips as $paySlip)
                                                        @foreach($paySlip->emiDeductions as $deduction)
                                                            <tr>
                                                                <td style="text-align: right;">{{ $iteration++ }}</td>
                                                                <td style="text-align: right;">{{ getDisplayDateFormat(optional($deduction->advanceApplication)->created_at) ?? config('global.null_value') }}</td>
                                                                <td>{{ $deduction->employee->emp_name }}</td>
                                                                <td>{{ $deduction->employee->username }}</td>
                                                                <td>{{ $deduction->employee->empJob->designation->name }}</td>
                                                                <td>{{ $deduction->employee->empJob->department->name }}</td>
                                                                <td>{{ $deduction->employee->empJob->office->region->name ?? config('global.null_value') }}</td>
                                                                <td>{{ $deduction->employee->empJob->office->name }}</td>
                                                                <td>{{ $deduction->loanType->name }}</td>
                                                                <td>{{ $deduction->loan_number }}</td>
                                                                <td>{{ $deduction->advanceApplication->item_type ?? config('global.null_value') }}</td>
                                                                <td style="text-align: right;">{{ getDisplayDateFormat($deduction->start_date) }}</td>
                                                                <td style="text-align: right;">{{ getDisplayDateFormat($deduction->end_date) }}</td>
                                                                <td style="text-align: right;">{{ $deduction->recurring_months}}</td>
                                                                <td>{{ \Carbon\Carbon::parse($paySlip->for_month)->format('F Y') }}</td>
                                                                <td style="text-align: right;">{{ formatAmount($deduction->amount, false) }}</td>
                                                                <td style="text-align: right;">
                                                                    @php
                                                                        $details = is_string($paySlip->details) ? json_decode($paySlip->details, true) : $paySlip->details;
                                                                        $samsungDeduction = $details['deductions']['Samsung Ded'] ?? 0;
                                                                    @endphp
                                                                    {{ formatAmount($samsungDeduction, false) }}
                                                                </td>
                                                                <td>{{ $deduction->advanceApplication->advance_approved_by->emp_name ?? config('global.null_value') }}</td>
                                                                <td style="text-align: right;">{{ getDisplayDateFormat(optional($deduction->advanceApplication)->updated_at) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @empty
                                                        <tr>
                                                            <td colspan="18" class="text-center text-danger">No Data Found.</td>
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
                    @if ($paySlips->hasPages())
                        <div class="card-footer">
                            {{ $paySlips->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>








@endsection
