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
        </div>



    </div>
</div>
<div class="block-content">


</div>


@endsection