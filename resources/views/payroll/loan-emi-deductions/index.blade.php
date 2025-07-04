@extends('layouts.app')
@section('page-title', 'Loan / Device Emis')
@section('content')
    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('loan-emi-deductions.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New
            </a>
        @endsection
    @endif

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <select name="payhead" class="form-control select2">
                    <option value="">-- Select Payhead --</option>
                    @foreach ($payHeads as $id => $name)
                        <option value="{{ $id }}"
                            {{ old('payhead', request()->get('payhead')) == $id ? 'selected' : '' }}>{{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-3 form-group">
                <select name="employee" class="form-control select2">
                    <option value="">-- Select Employee --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ old('employee', request()->get('employee')) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->emp_id_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3 form-group">
                <select name="loantype" class="form-control select2">
                    <option value="">-- Select Loan Type --</option>
                    @foreach ($loanTypes as $id => $name)
                        <option value="{{ $id }}"
                            {{ old('loantype', request()->get('loantype')) == $id ? 'selected' : '' }}>{{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
             <div class="col-md-3 form-group">
                <input type="text" name="cid_no" class="form-control" value="{{ request()->get('cid_no') }}"
                    placeholder="CID ID">
            </div>
    @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th> # </th>
                                                            <th> Employee </th>
                                                            <th> Employee ID </th>
                                                            <th> Employee CID </th>
                                                            <th> Pay Head </th>
                                                            <th> Loan Type </th>
                                                            <th> Branch Code </th>
                                                            <th> Amount </th>
                                                            <th> Loan No. </th>
                                                            <th> Start Date </th>
                                                            <th> End Date </th>
                                                            <th> Recurring </th>
                                                            <th> Recurring Months </th>
                                                            <th> Paid off early </th>
                                                            <th> Created At </th>
                                                            <th> Updated At </th>
                                                            <th> Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($loanEMIDeductions as $record)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $record->employee->emp_id_name }} </td>
                                                                <td>{{ $record->employee->employee_id }} </td>
                                                                <td>{{ $record->employee->cid_no }} </td>
                                                                <td>{{ $record->payHead->name }}</td>
                                                                <td>{{ $record->loanType?->name }} </td>
                                                                <td>{{ $record->branch_code ?? config('global.null_value') }}
                                                                </td>
                                                                <td>{{ $record->amount }} </td>
                                                                <td>{{ $record->loan_number }} </td>
                                                                <td> {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('M d, Y') : '-' }}
                                                                </td>
                                                                <td> {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('M d, Y') : '-' }}
                                                                </td>
                                                                <td>
                                                                    @if ($record->recurring)
                                                                        <i class="fa fa-check-circle-o text-success"></i>
                                                                    @else
                                                                        <i class="fa fa-times-circle text-danger"></i>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $record->recurring_months }} </td>
                                                                <td>
                                                                    @if ($record->is_paid_off)
                                                                        <i class="fa fa-check-circle-o text-success"></i>
                                                                    @else
                                                                        <i class="fa fa-times-circle text-danger"></i>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $record->created_at ? $record->created_at->format('Y-m-d H:i:s') : '-' }}
                                                                </td>
                                                                <td>{{ $record->updated_at ? $record->updated_at->format('Y-m-d H:i:s') : '-' }}
                                                                </td>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($privileges->edit)
                                                                        <a href="{{ route('loan-emi-deductions.edit', $record->id) }}"
                                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                                            <i class="fa fa-edit"></i>
                                                                            EDIT
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="13" class="text-center text-danger"> No
                                                                    Matching Records Found </td>
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
                    @if ($loanEMIDeductions->hasPages())
                        <div class="card-footer">
                            {{ $loanEMIDeductions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
