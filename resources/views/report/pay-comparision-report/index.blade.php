@extends('layouts.app')
@section('page-title', 'Pay Comparision Report')
@section('content')

<div class="col-md-12 d-flex justify-content-end gap-2">
    <div class="d-flex gap-2">

        <a href="{{ route('pay-comparision-report-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
            title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
        <a href="{{ route('cash-report-print', Request::query()) }}" target="_blank"
            onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
    </div>
</div>

<br>

<div class="block-header block-header-default">
    @component('layouts.includes.filter')

    <div class="col-3 form-group">
        <select name="employee_id" class="form-control ">
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
                                        <table
                                            class="table table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th colspan="6" class="custom-border">for the month of {{$currentMonthName}}</th>
                                                    <th colspan="3" class="custom-border">Month : {{$previousMonthName}}</th>
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
                                                @if($payslips)
                                                @foreach ($payslipData as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration}}</td>
                                                    <td>{{ $data['employee_name'] }}</td>
                                                    <td>{{ $data['employee_id'] }}</td>
                                                    <td>{{ number_format($data['current_basic'], 2) }}</td>
                                                    <td>{{ number_format($data['current_allowances'], 2) }}</td>
                                                    <td>{{ number_format($data['current_gross'], 2) }}</td>

                                                    <td>{{ number_format($data['previous_basic'], 2) }}</td>
                                                    <td>{{ number_format($data['previous_allowances'], 2) }}</td>
                                                    <td>{{ number_format($data['previous_gross'], 2) }}</td>

                                                    <td>{{ number_format($data['basic_diff'], 2) }}</td>
                                                    <td>{{ number_format($data['allowances_diff'], 2) }}</td>
                                                    <td>{{ number_format($data['gross_diff'], 2) }}</td>
                                                </tr>
                                                @endforeach


                                                @else
                                                <tr colspan="12">No PAy Comparision Reports Found</tr>

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