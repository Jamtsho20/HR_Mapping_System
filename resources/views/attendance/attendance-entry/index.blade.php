@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')
<style>
    .col-md-2 {
        -ms-flex: 0 0 16.666667%;
        flex: 0 0 16.666667%;
        max-width: 12.5%;
    }

    .col-md-1 {
        padding-top: 25px;
    }
</style>

<div class="col-sm-4">
    <h5>Attendance Entry</h5>
</div>

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">E.Code</label>
                    <input type="text" placeholder="Eg. XX12" class="form-control  myclass input_search" id="txt_empcode">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">E.Name</label>
                    <input type="text" placeholder="Eg. John" class="form-control  myclass input_search" id="txt_empname">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group form-focus focused">
                    <label class="control-label">Region </label>
                    <select class="form-control" name="region">
                        <option value="" disabled selected hidden>Select</option>
                        @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->region_name  }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-focus focused">
                    <label class="control-label">Department </label>
                    <select class="form-control" name="department">
                        <option value="" disabled selected hidden>Select</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name  }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-focus focused">
                    <label class="control-label">Location</label>
                    <select class="form-control  myclass input_search" id="ddl_location" name="ddl_location">
                        <option value="0">Select</option>
                        <option value="1">TICL_Thimphu Head Office</option>
                        <option value="2">TICL_Autsho Extension</option>
                        <option value="3">TICL_Babesa Extension</option>
                        <option value="4">TICL_Bajo Extension</option>
                        <option value="5">TICL_Bangtar Extension</option>
                        <option value="6">TICL_Bumthang customer Care Center</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-focus focused">
                    <label class="control-label">Year</label>
                    <select class="form-control  myclass input_search" id="ddl_year">
                        <option value="0">Select</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-focus focused">
                    <label class="control-label">Select Month</label>
                    <select class="form-control  myclass input_search" id="ddl_month">
                        <option value="0">Select </option>
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">Jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>
                </div>

            </div>
            @endcomponent
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Attendance Entry</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="dataTables_length" id="responsive-datatable_length"
                                                data-select2-id="responsive-datatable_length">
                                                <label data-select2-id="26">
                                                    Show
                                                    <select class="select2">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                    entries
                                                </label>
                                            </div>
                                            <div class="dataTables_scroll">
                                                <div class="dataTables_scrollHead"
                                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                    <div class="dataTables_scrollHeadInner"
                                                        style="box-sizing: content-box; padding-right: 0px;">
                                                        <table
                                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                            id="basic-datatable table-responsive">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th>
                                                                        Employee
                                                                    </th>
                                                                    <th>
                                                                        Present
                                                                    </th>
                                                                    <th>
                                                                        Leave
                                                                    </th>
                                                                    <th>
                                                                        Holiday
                                                                    </th>
                                                                    <th>
                                                                        Leave WP
                                                                    </th>
                                                                    <th>
                                                                        Weekly off
                                                                    </th>
                                                                    <th>
                                                                        Pay Day
                                                                    </th>
                                                                    <th>
                                                                        Status
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Adrian</td>
                                                                    <td>Terry</td>
                                                                    <td>Casual</td>
                                                                    <td>2013/04/21</td>
                                                                    <td>$543,769</td>
                                                                    <td>0.5</td>
                                                                    <td>0.5</td>
                                                                    <td><span class="badge bg-success">Approved</span>
                                                                    </td>
                                                                </tr>
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
</div>
<div class="block-content">


</div>


@endsection