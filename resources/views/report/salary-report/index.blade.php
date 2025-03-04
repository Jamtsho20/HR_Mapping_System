@extends('layouts.app')
@section('page-title', 'Salary Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('salary-report-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('salary-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('salary-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
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
                        <h3 class="card-title">Salary Report</h3>
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
                                                            Job title
                                                        </th>
                                                        <th>
                                                            job nature
                                                        </th>
                                                        <th>
                                                            Salary month
                                                        </th>
                                                        <th>
                                                            basic pay
                                                        </th>
                                                        <th>
                                                            house all.
                                                        </th>
                                                        <th>
                                                            medical all.
                                                        </th>
                                                        <th>
                                                            add. work all.
                                                        </th>
                                                        <th>
                                                            corporate all.
                                                        </th>
                                                        <th>
                                                            diff. all.
                                                        </th>
                                                        <th>
                                                            critical all.
                                                        </th>
                                                        <th>
                                                            gross earning
                                                        </th>

                                                        <th>
                                                            samsung
                                                        </th>
                                                        <th>
                                                            gis
                                                        </th>

                                                        <th>BNB</th>
                                                        <th>NPPF</th>
                                                        <th>BDFC</th>
                                                        <th>RICB</th>
                                                        <th>DPNB</th>
                                                        <th> BOB </th>
                                                        <th> Tbank </th>
                                                        <th>Sifa loan</th>
                                                        <th>
                                                            PF
                                                        </th>
                                                        <th>
                                                            sifa
                                                        </th>
                                                        <th>
                                                            SSS
                                                        </th>
                                                        <th>
                                                            TDS
                                                        </th>
                                                        <th>
                                                            H/Tax
                                                        </th>
                                                        <th>
                                                            Net Pay
                                                        </th>

                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($salaries as $salary)
                                                        <tr>
                                                            <td>{{ ($salaries->currentPage() - 1) * $salaries->perPage() + $loop->iteration }}
                                                            </td>
                                                            <td>{{ $salary->employee->name }}</td>
                                                            <td>{{ $salary->employee->empJob->designation->name }}</td>
                                                            <td>{{ $salary->employee->empJob->empType->name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($salary->for_month)->format('F Y') }}
                                                            </td>
                                                            <td>{{ $salary->employee->empJob->basic_pay }}</td>
                                                            <td>{{ $salary->details['allowances']['House Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Medical Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Add. Work Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Corporate Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Difficulty Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Critical Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['gross_pay'] ?? 0 }}</td>
                                                            <td>{{ $salary->details['deductions']['Samsung Ded'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['GSLI'] ?? '0' }}</td>

                                                            <td>{{ $salary->details['deductions']['Loan BNB'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Loan NPPF'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Loan BDFC'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Loan RICB'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Loan DPNB'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Loan BOB'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Loan TBank'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Loan SIFA'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['PF Contr'] ?? '0' }}
                                                            </td>

                                                            <td>{{ $salary->details['deductions']['SIFA'] ?? '0' }}</td>
                                                            <td>{{ $salary->details['deductions']['SSSS'] ?? '0' }}</td>
                                                            <td>{{ $salary->details['deductions']['Salary Tax'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['H/Tax'] ?? '0' }}</td>
                                                            <td>{{ $salary->details['net_pay'] }}</td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="28" class="text-center text-danger">No Salary
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
                    @if ($salaries->hasPages())
                        <div class="card-footer">
                            {{ $salaries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>








@endsection
