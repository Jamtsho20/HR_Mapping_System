@extends('layouts.app')
@section('page-title', 'Cheque Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('cheque-report-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('cheque-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('cheque-report-print', Request::query()) }}" target="_blank"
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
                <select name="bank_location" class="form-control select2 select2-hidden-accessible"
                    data-placeholder="Select Bank Type">
                    <option value="" disabled="" selected="" hidden="">Select Bank</option>
                    @foreach (config('global.bank') as $key => $label)
                        <option value="{{ $key }}" {{ request()->get('bank_location') == $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cheque Report</h3>
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
                                                            Bank account number
                                                        </th>
                                                        <th>
                                                            Bank Location
                                                        </th>
                                                        <th> Net Payment
                                                        </th>
                                                        <th>
                                                            Date
                                                        </th>

                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($cheques as $cheque)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $cheque->employee->name }}</td>
                                                            <td>{{ $cheque->employee->empJob->account_number }}</td>
                                                            <td>{{ $cheque->employee->empJob->bank }}</td>
                                                            <td>{{ $cheque->details['net_pay'] }}</td>
                                                            <td>{{ $cheque->for_month }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="21" class="text-center text-danger">No Cheque
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
                    @if ($cheques->hasPages())
                        <div class="card-footer">
                            {{ $cheques->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>








@endsection
