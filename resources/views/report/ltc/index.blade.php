@extends('layouts.app')
@section('page-title', 'LTC Report')
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
    <div class="col-4 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}" placeholder="Year">
    </div>

    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Travel Concession (LTC) Report</h3>
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
                                                        LOCATION
                                                    </th>
                                                    <th>
                                                        D.O.A
                                                    </th>
                                                    <th>
                                                        GRADE
                                                    </th>
                                                    <th>
                                                        BASIC PAY
                                                    </th>
                                                    <th>
                                                        DUE DATE
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($ltcs as $ltc)
                                                @if ($ltc->ltcDetails->isNotEmpty()) <!-- Check if there are ltcDetails -->
                                                @foreach ($ltc->ltcDetails as $detail)
                                                <tr>
                                                    <td>{{ $loop->parent->iteration }}</td> <!-- Use parent loop for main iteration -->
                                                    <td>{{ $detail->employee->username ?? 'N/A' }}</td>
                                                    <td>{{ $detail->employee->name ?? 'N/A' }}</td>
                                                    <td>{{ $detail->employee->empJob->designation->name ?? 'N/A' }}</td>
                                                    <td>{{ $detail->employee->empJob->office->name ?? 'N/A' }}</td>
                                                    <td>{{ $detail->employee->date_of_appointment ?? 'N/A' }}</td>
                                                    <td>{{ $detail->employee->empJob->gradeStep->name ?? 'N/A' }}</td>
                                                    <td>{{ $detail->employee->empJob->basic_pay ?? 'N/A' }}</td>
                                                    <td>{{ $ltc->for_month }}</td> <!-- This is constant across details -->
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td colspan="8" class="text-center text-muted">No Details Available</td>
                                                </tr>
                                                @endif
                                                @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-danger">No LTC Reports Found</td>
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
                @if ($ltcs->hasPages())
                <div class="card-footer">
                    {{ $ltcs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>





@endsection