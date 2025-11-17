@extends('layouts.app')
@section('page-title', 'sifa')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('sifa-report-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('sifa-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('sifa-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
        </div>
    </div>

    <br>

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-md-2 form-group">
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
            <div class="col-3 form-group">
                <input type="text" name="cid_no" class="form-control" value="{{ request()->get('cid_no') }}"
                    placeholder="CID ID">
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">SIFA Contribution</h3>
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
                                                            EMployee ID
                                                        </th>
                                                        <th>
                                                            EMployee Name
                                                        </th>
                                                        <th>
                                                            Designtion
                                                        </th>
                                                        <th>
                                                            DOA
                                                        </th>
                                                        <th>
                                                            Employment Type
                                                        </th>
                                                        <th>
                                                            amount
                                                        </th>
                                                        <th>
                                                            For Month
                                                        </th>

                                                    </tr>
                                                </thead>
                                                @php $hasRecords = false; @endphp

                                                <tbody>
                                                    @forelse($sifaContributions as $sifa)
                                                        @php
                                                            $sifaAmount =
                                                                $sifa->details['deductions']['SIFA'] ??
                                                                $sifa->sifa_contr;
                                                        @endphp

                                                        @if ($sifaAmount > 0)
                                                            @php $hasRecords = true; @endphp
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $sifa->employee->username }}</td>
                                                                <td>{{ $sifa->employee->emp_name }}</td>
                                                                <td>{{ $sifa->employee->empJob->designation->name }}</td>
                                                                <td>{{ getDisplayDateFormat($sifa->employee->date_of_appointment) }}</td>
                                                                <td>{{ $sifa->employee->empJob->empType->name }}</td>
                                                                <td>{{ formatAmount($sifaAmount) }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($sifa->for_month)->format('F Y') }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @empty
                                                    @endforelse

                                                    @if (!$hasRecords)
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No SIFA
                                                                contribution Reports found</td>
                                                        </tr>
                                                    @endif
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($sifaContributions->hasPages())
                        <div class="card-footer">
                            {{ $sifaContributions->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection
