@extends('layouts.app')
@section('page-title', 'PF')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('pf-report-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('pf-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('pf-report-print', Request::query()) }}" target="_blank"
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
                        <h3 class="card-title">Provident Fund</h3>
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
                                                            PF Number
                                                        </th>
                                                        <th>
                                                            CID
                                                        </th>
                                                        <th>
                                                            Basic Pay
                                                        </th>
                                                        <th>
                                                            Member Contribution
                                                        </th>
                                                        <th>
                                                            Employer Contribution
                                                        </th>
                                                        <th>
                                                            Total Contribution
                                                        </th>


                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse ($pfDeductionsWithPF as $pf)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $pf['employee_name'] }}</td>
                                                            <td>{{ $pf['pf_number'] }}</td>
                                                            <td>{{ $pf['CID'] ?? '-' }}</td>
                                                            <td>{{ $pf['basic_pay'] ?? '-' }}</td>
                                                            <td>{{ $pf['details']['deductions']['PF Contr'] ?? 0 }}</td>
                                                            <td>{{ $pf['employer_pf_amount'] ?? 0 }}</td>
                                                            <td>{{ $pf['total'] ?? 0 }}</td>
                                                        </tr>
                                                    @empty

                                                        <tr>
                                                            <td colspan="6" class="text-center text-danger">No PF Reports
                                                                found</td>
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
                    @if ($pfDeductions->hasPages())
                        <div class="card-footer">
                            {{ $pfDeductions->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection
