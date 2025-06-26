@extends('layouts.app')
@section('page-title', 'Attendance Update')
@section('content')
@if ($privileges->create)

@endif
<div class="block-header block-header-default">
    <!-- @component('layouts.includes.filter')
    <div class="col-6 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
    </div>
    @endcomponent -->

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="dataTables_scroll">
                                <div class="dataTables_scrollHead"
                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                    <div class="dataTables_scrollHeadInner"
                                        style="box-sizing: content-box; padding-right: 0px;">
                                        <table
                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                            id="basic-datatable table-responsive">
                                            <thead>
                                                <tr role="row" class="thead-light" style="text-align: center;">
                                                    <th>
                                                        Sl. No
                                                    </th>
                                                    <th>
                                                        Attendance ID
                                                    </th>
                                                    <th>
                                                        Employee
                                                    </th>
                                                    <th>
                                                        Attendance Status
                                                    </th>
                                                    <th>
                                                        Remarks
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($attendanceRecords as $record)
                                                <tr>
                                                    <td>{{ $record->employee_id }}</td>
                                                    <td>
                                                        @php
                                                        $status = \App\Models\AttendanceStatus::find($record->attendance_status_id)->name ?? 'Unknown';
                                                        @endphp
                                                        {{ $status }}
                                                    </td>
                                                </tr>
                                                @endforeach
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

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush