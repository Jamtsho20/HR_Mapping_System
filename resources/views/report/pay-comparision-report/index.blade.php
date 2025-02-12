@extends('layouts.app')
@section('page-title', 'Pay Comparision Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">

            <a href="{{ route('pay-comparision-report-pdf.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('pay-comparision-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
        </div>
    </div>

    <br>

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
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
                        <h3 class="card-title">Pay Comparision Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="col-sm-12">

                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table class="table table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th colspan="6" class="custom-border">for the month
                                                            of {{ \Carbon\Carbon::parse($currentMonth)->format('F Y') }}
                                                        </th>
                                                        <th colspan="3" class="custom-border">Month :
                                                            {{ \Carbon\Carbon::parse($previousMonth)->format('F Y') }}
                                                        </th>
                                                        <th colspan="3" class="custom-border">Differences</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="custom-border">Sl no</th>
                                                        <th class="custom-border">employee name</th>
                                                        <th class="custom-border">employee code</th>
                                                        <th class="custom-border">basic</th>
                                                        <th class="custom-border">allowances</th>
                                                        <th class="custom-border">gross</th>
                                                        <th class="custom-border">basic</th>
                                                        <th class="custom-border">Allowances</th>
                                                        <th class="custom-border">Gross</th>
                                                        <th class="custom-border">basic</th>
                                                        <th class="custom-border">Allowance</th>
                                                        <th class="custom-border">Gross</th>
                                                    </tr>

                                                </thead>
                                                <tbody>

                                                    @foreach ($current as $data)
                                                        @php

                                                            $previousData = $previous
                                                                ->where('mas_employee_id', $data->mas_employee_id)
                                                                ->first();

                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $data->employee->name ?? config('global_null') }}</td>
                                                            <td>{{ $data->employee->username }}</td>

                                                            {{-- Current Salary Details --}}
                                                            <td>{{ $data['basic_pay'] }}</td>
                                                            <td>

                                                                {{ $data['total_allowance'] }}</td>
                                                            <td>{{ $data['gross_pay'] }}</td>

                                                            {{-- Previous Salary Details --}}
                                                            <td>{{ $previousData['details']['basic_pay'] ?? config('global_null') }}
                                                            </td>
                                                            <td>{{ $previousData['total_allowances'] }}</td>
                                                            <td>{{ $previousData['details']['gross_pay'] ?? config('global_null') }}
                                                            </td>

                                                            {{-- Differences --}}
                                                            <td>{{ ($data['basic_pay'] ?: 0) - ($previousData['details']['basic_pay'] ?: 0) }}
                                                            </td>
                                                            <td>{{ $data['total_allowance'] - $previousData['total_allowances'] }}
                                                            </td>
                                                            <td>{{ $data['gross_pay'] - $previousData['details']['gross_pay'] }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    @if ($current->isEmpty())
                                                        <tr>
                                                            <td colspan="12">No Pay Comparison Reports Found</td>
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

                </div>
            </div>
        </div>
    </div>








@endsection
