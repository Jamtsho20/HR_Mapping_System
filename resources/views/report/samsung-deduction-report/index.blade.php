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
                                                            Employee Name
                                                        </th>
                                                        <th>
                                                            EMP ID
                                                        </th>
                                                        <th>
                                                            loan type
                                                        </th>
                                                        <th>
                                                            Loan number
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

                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($samsungDeductions as $loan)
                                                        <tr>
                                                            <td style="text-align: right;">{{ $loop->iteration }}</td>
                                                            <td>{{ $loan->employee->emp_name }}</td>
                                                            <td>{{ $loan->employee->username }}</td>
                                                            <td>{{ $loan->pay_head_name }}</td>
                                                            <td>{{ $loan->loan_number }}</td>
                                                            <td style="text-align: right;">{{ getDisplayDateFormat($loan->start_date) }}</td>
                                                            <td style="text-align: right;">{{ getDisplayDateFormat($loan->end_date) }}</td>
                                                            <td style="text-align: right;">{{ $loan->recurring_months}}</td>
                                                            <td>{{ \Carbon\Carbon::parse($loan->for_month)->format('F Y') }}</td>
                                                            <td style="text-align: right;">{{ formatAmount($loan->amount, false) }}</td>


                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="10" class="text-center text-danger">No Data Found.</td>
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
                    @if ($samsungDeductions->hasPages())
                        <div class="card-footer">
                            {{ $samsungDeductions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>








@endsection
