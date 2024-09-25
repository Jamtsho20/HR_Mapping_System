@extends('layouts.app')
@section('page-title', 'Showing Payslip Details')
@section('buttons')
    <a href="{{ route('pay-slips.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Payslip
        List</a>
@endsection
@section('content')
    <form action="{{ route('pay-slips.update', $paySlip->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body d-flex justify-content-between">
                <div class="form-group col-md-6">
                    <label for="for_month">For Month <span class="text-danger">*</span></label>
                    <input type="month" class="form-control" name="for_month"
                        value="{{ substr($paySlip->for_month, 0, 7) }}" required="required">
                </div>
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fa fa-upload"></i> UPDATE
                    </button>
                    &nbsp;
                    <a href="{{ url('payroll/pay-slips') }}" class="btn btn-danger">
                        <i class="fa fa-undo"></i> CANCEL
                    </a>
                </div>
            </div>
        </div>
    </form>


    @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="search" class="form-control"
                value="{{ request()->get('search') }}"placeholder="Search by employee name or id. eg. tashi or 1050">
        </div>
    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Payslip Overview</h3>
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
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Name (ID)</th>
                                                            <th>Basic Pay</th>
                                                            @foreach ($payHeads as $payHead)
                                                                <th>{{ $payHead->code }}</th>
                                                            @endforeach
                                                            <th>Gross Pay</th>
                                                            <th>Net Pay</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($records as $record)
                                                            <tr>
                                                                <td>{{ $record->employee->name }}
                                                                    ({{ $record->employee->employee_id }})
                                                                </td>
                                                                <td>{{ $record->basic_pay }}</td>
                                                                @foreach ($payHeads as $payHead)
                                                                    <td>{{ $record->{str_replace(' ', '_', $payHead->name)} }}
                                                                    </td>
                                                                @endforeach
                                                                <td>{{ $record->gross_pay }}</td>
                                                                <td>{{ $record->net_pay }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="100" class="text-center text-danger">No
                                                                    records found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div>{{ $records->links() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Payslip Detail</h3>
                    @if ($paySlip->status['key'] == 2)
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add-pay-slip-detail-modal">
                            <i class="fa fa-plus"></i> New
                            Detail
                        </button>
                    @endif
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
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Name (ID)</th>
                                                            <th>Pay Head</th>
                                                            <th>Amount</th>
                                                            <th>Created At</th>
                                                            <th>Updated At</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($details as $detail)
                                                            <tr>
                                                                <td>{{ $detail->employee->name }}
                                                                    ({{ $detail->employee->employee_id }})
                                                                </td>
                                                                <td>{{ $detail->payHead->name }}</td>
                                                                <td>{{ $detail->amount }}</td>
                                                                <td>{{ $detail->created_at ? $detail->created_at->format('Y-m-d H:i:s') : '' }}
                                                                </td>
                                                                <td>{{ $detail->updated_at ? $detail->updated_at->format('Y-m-d H:i:s') : '' }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center text-danger">No
                                                                    records found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div>{{ $details->links() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Detail -->
    <div class="modal fade" id="add-pay-slip-detail-modal" tabindex="-1" aria-labelledby="add-pay-slip-detail-modal"
        aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pay-slip-detail.add', $paySlip->id) }}" method="post"
                        id="pay-slip-detail-form">
                        @csrf
                        <input type="hidden" class="form-control" name="pay_slip_id" value="{{ $paySlip->id }}"
                            required="required">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                                <select class="form-control" name="mas_employee_id" required>
                                    <option value="">Select</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                            ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="mas_pay_head_id">Pay Head <span class="text-danger">*</span></label>
                                <select class="form-control" name="mas_pay_head_id" required>
                                    <option value="">Select</option>
                                    @foreach ($payHeads as $payHead)
                                        <option value="{{ $payHead->id }}">{{ $payHead->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="amount" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('page_scripts')
@endpush
