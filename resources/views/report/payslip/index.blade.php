@extends('layouts.app')
@section('page-title', 'Salary Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">

            <a href="{{ route('payslip-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>

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
                        <h3 class="card-title">Payslip Report</h3>
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
                                                            Department
                                                        </th>
                                                        <th>
                                                            job Title
                                                        </th>
                                                        <th>
                                                            Grade </th>
                                                        <th>
                                                            Bank Name
                                                        </th>
                                                        <th>
                                                            Bank A/C
                                                        </th>
                                                        <th>
                                                            Basic Pay
                                                        </th>
                                                        <th>
                                                            House Allowance
                                                        </th>
                                                        <th>
                                                            Medical Allowance
                                                        </th>
                                                        <th>
                                                            Add. Work Allowance
                                                        </th>
                                                        <th>
                                                            Cash Allowance
                                                        </th>
                                                        <th>
                                                            Corporate Allowance
                                                        </th>
                                                        <th>
                                                            Difficulty Allowance
                                                        </th>
                                                        <th>
                                                            Critical Allowance
                                                        </th>
                                                        <th>
                                                            Gross Pay
                                                        </th>

                                                        <th>
                                                            Adv. Salary
                                                        </th>
                                                        <th>
                                                            Adv. Staff
                                                        </th>
                                                        <th>
                                                            PF Contr.
                                                        </th>
                                                        <th>
                                                            SSS
                                                        </th>
                                                        <th>
                                                            SIFA
                                                        </th>
                                                        <th>
                                                            H/Tax
                                                        </th>
                                                        <th>
                                                            Salary Tax
                                                        </th>
                                                        <th>
                                                            GSLI
                                                        </th>
                                                        <th>
                                                            Samsung Ded
                                                        </th>
                                                        <th>Loan BNB</th>
                                                        <th>Loan NPPF</th>
                                                        <th>Loan BDFC</th>
                                                        <th>Loan RICB</th>
                                                        <th>Loan DPNB</th>
                                                        <th>Loan BOB </th>
                                                        <th>Loan Tbank </th>
                                                        <th>Loan SIFA</th>
                                                        <th> Net Pay </th>

                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($payslips as $salary)
                                                        <tr>
                                                            <td>{{ ($payslips->currentPage() - 1) * $payslips->perPage() + $loop->iteration }}
                                                            </td>
                                                            <td>{{ $salary->employee->name }}</td>
                                                            <td>{{ $salary->employee->empJob->department->name }}</td>
                                                            <td>{{ $salary->employee->empJob->designation->name }}</td>
                                                            <td>{{ $salary->employee->empJob->gradeStep->name }}</td>
                                                            <td>{{ $salary->employee->empJob->bank ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $salary->employee->empJob->account_number ?? config('global.null_value') }}
                                                            </td>
                                                            <td>{{ $salary->employee->empJob->basic_pay }}</td>
                                                            <td>{{ $salary->details['allowances']['House Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Medical Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Add. Work Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Cash Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Corporate Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Difficulty Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['allowances']['Critical Allowance'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['gross_pay'] ?? 0 }}</td>
                                                            <td>{{ $salary->details['deductions']['Adv. Salary'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['Adv. Staff'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['PF Contr'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['SSSS'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['SIFA'] ?? '0' }}</td>
                                                            <td>{{ $salary->details['deductions']['H/Tax'] ?? '0' }}</td>

                                                            <td>{{ $salary->details['deductions']['Salary Tax'] ?? '0' }}
                                                            </td>
                                                            <td>{{ $salary->details['deductions']['GSLI'] ?? '0' }}</td>
                                                            <td>{{ $salary->details['deductions']['Samsung Ded'] ?? '0' }}
                                                            </td>


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

                                                            <td>{{ $salary->details['net_pay'] }}</td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="28" class="text-center text-danger">No Payslip
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
                    @if ($payslips->hasPages())
                        <div class="card-footer">
                            {{ $payslips->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>








@endsection
