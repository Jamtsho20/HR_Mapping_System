@extends('layouts.app')
@section('page-title', 'Attendance Entry')
@section('content')
@push('page_styles')
    <style>
        .tooltip-inner {
            text-align: left !important;
            /* white-space: pre-line; */
        }
    </style>
@endpush
<div class="block">
    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
                @php
                    $selectedYear = request()->get('year') ?? now()->year;
                    $currentYear = now()->year;
                @endphp
                <div class="col-md-4 form-group">
                    <select class="form-control" name="year">
                        <option value="" disabled selected hidden>Select Year</option>
                        @foreach (config('global.years') as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }} {{ $year > $currentYear ? 'disabled' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            @endcomponent
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
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
                                                            <tr role="row" class="thead-light">
                                                                <th>
                                                                    #
                                                                </th>
                                                                <th>
                                                                    For Month
                                                                </th>
                                                                @foreach($days as $day)
                                                                    <th>
                                                                        {{ $day }}
                                                                    </th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($monthlyAttendances as $index => $attendance)
                                                                @php
                                                                    $attendanceMap = $attendance['attendanceMap'];
                                                                    $monthName = $attendance['month'];
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $monthName }}</td>

                                                                    @foreach ($days as $day)
                                                                        @php
                                                                            $data = $attendanceMap[$day] ?? null;
                                                                        @endphp
                                                                        <td class="text-center"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-html="true"
                                                                            data-bs-placement="top"
                                                                            title="Check-in: {{ $data['check_in_at'] ?? config('global.null_value') }}<br>
                                                                            Check-out: {{ $data['check_out_at'] ?? config('global.null_value') }}<br>
                                                                            Status: {{ $data['attendance_status_code'] ?? config('global.null_value') }} - {{ $data['attendance_status_description'] ?? config('global.null_value') }}<br>
                                                                            Worked Hours: {{ $data['worked_hours'] ?? config('global.null_value') }}<br>
                                                                            Date: {{ $data['attendance_date'] ?? config('global.null_value') }}">
                                                                            {{ $data['attendance_status_code'] ?? config('global.null_value') }}
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="{{ 2 + count($days) }}" class="text-center text-danger">
                                                                        No Data Found
                                                                    </td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.includes.delete-modal')
@endsection