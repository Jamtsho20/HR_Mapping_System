@extends('layouts.app')
@section('page-title', 'Leave Encashment Report')
@section('content')

<div class="col-md-12 d-flex justify-content-end gap-2">
    <div class="d-flex gap-2">
        <a href="{{route('ltc.export',Request::query())}}" data-toggle="tooltip" data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
        <a href="{{route('ltc-pdf.export', Request::query())}}" data-toggle="tooltip" data-placement="top" title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
        <a href="{{route('ltc-print',Request::query())}}" target="_blank" onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
    </div>
</div>

<br>
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-3 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
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
                    <h3 class="card-title">Leave Encashment Report</h3>
                </div>
                <div class="card-body">
                    <div class="dataTables_scroll">
                        <div class="dataTables_scrollHead"
                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                            <div class="dataTables_scrollHeadInner"
                                style="box-sizing: content-box; padding-right: 0px;">
                                <div class="table-responsive">
                                    <div class="col-sm-12">
                                        <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">

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
                                                        LOCATION
                                                    </th>
                                                    <th>
                                                        ENCASHMENT
                                                    </th>
                                                    <th>
                                                        EL CLOSING BAL
                                                    </th>
                                                    <th>
                                                        BASIC PAY
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($leaveEncashments as $leave)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$leave->employee->username}}</td>
                                                    <td>{{$leave->employee->name}}</td>
                                                    <td>{{$leave->employee->empJob->designation->name}}</td>
                                                    <td>{{$leave->employee->empJob->department->name}}</td>
                                                    <td>{{$leave->employee->empJob->office->name}}</td>
                                                    <td>{{$leave->leave_applied_for_encashment}}</td>
                                                    <td>{{$leave->encashment_amount}}</td>
                                                    <td>{{$leave->encashment_amount}}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-danger">No Encashment report found</td>
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
                <div class="card-footer">
                    {{ $leaveEncashments->links() }}
                </div>
            </div>
        </div>
    </div>


</div>





@endsection
