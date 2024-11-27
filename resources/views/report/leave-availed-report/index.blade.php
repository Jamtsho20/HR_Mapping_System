@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')

<div class="col-md-12 d-flex justify-content-end gap-2">
    <div class="d-flex gap-2">
        <a href="{{route('leave-availed-excel.export',Request::query())}}" data-toggle="tooltip" data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
        <a href="{{route('leave-availed-pdf.export', Request::query())}}" data-toggle="tooltip" data-placement="top" title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
        <a href="{{ route('leave-availed-report-print',Request::query()) }}" target="_blank" onclick="openPrintPreview(event)">
            <span><i class="fa fa-print fa-lg"></i></span>
        </a>

    </div>
</div>

<br>
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-3 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
    </div>

    <div class="col-2 form-group">
        <select name="leave_type" class="form-control ">
            <option value="" disabled="" selected="" hidden="">Select Leave Type</option>
            @foreach($leaveTypes as $type)
            <option value="{{  $type->id }}" {{ request()->get('leave_type') ==  $type->id ? 'selected' : '' }}>
                {{$type->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control" name="department">
            <option value="" disabled="" selected="" hidden="">Select Department</option>
            @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ request()->get('department') == $department->id ? 'selected' : '' }}>
                {{ $department->name }}
            </option>
            @endforeach
        </select>

    </div>

    <div class="col-md-4">
        <select class="form-control" name="section">
            <option value="" disabled selected hidden>Select Sections</option>
            @foreach($sections as $section)
            <option value="{{ $section->id }}" {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                {{ $section->name }}
            </option>
            @endforeach
        </select>
    </div>
    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Availed Report</h3>
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
                                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                                                    <thead class="thead-light">
                                                        <tr role="row">
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                CODE
                                                            </th>
                                                            <th>
                                                                NAME
                                                            </th>
                                                            <th>
                                                                DESIGNATION
                                                            </th>
                                                            <th>
                                                                DEPARTMENT
                                                            </th>
                                                            <th>
                                                                Leave Type
                                                            </th>
                                                            <th>
                                                                LOCATION
                                                            </th>
                                                            <th>
                                                                FROM DATE
                                                            </th>
                                                            <th>
                                                                TO DATE
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($leaveReports as $report)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$report->employee->username}}</td>
                                                            <td>{{$report->employee->name}}</td>
                                                            <td>{{$report->employee->empJob->designation->name}}</td>
                                                            <td>{{$report->employee->empJob->department->name}}</td>
                                                            <td>{{$report->leaveType->name}}</td>
                                                            <td>{{$report->employee->empJob->office->name}}</td>
                                                            <td>{{$report->from_date}}</td>
                                                            <td>{{$report->to_date}}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center text-danger">No leave availed report found</td>
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
                @if ($leaveReports->hasPages())
                <div class="card-footer">
                    {{ $leaveReports->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>







@endsection