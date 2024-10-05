@extends('layouts.app')
@section('page-title', 'Delegation')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/approval-rules/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Add new</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header ">
        <div class="col-sm-4">
            <h5>Approval Rules</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="card">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-country-tab" data-bs-toggle="pill" data-bs-target="#pills-country" type="button" role="tab" aria-controls="pills-country" aria-selected="true">Leave</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-time_zone-tab" data-bs-toggle="pill" data-bs-target="#pills-time_zone" type="button" role="tab" aria-controls="pills-time_zone" aria-selected="false">Expense</button>
                </li>           
             
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-country" role="tabpanel" aria-labelledby="pills-country-tab">
                    <div class="row">
                        <div class="col-3">
                            <label style="float:left">Show &nbsp;</label>
                            <div class="dataTables_length" id="tbl_attendancesheet_length" style="float:left">
                                <select name="tbl_attendancesheet_length" aria-controls="tbl_attendancesheet" class="form-control">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            &nbsp;
                            <label>entries</label>
                        </div>

                        <div class="col-3">
                            <input type="text" name="search" class="form-control" value="" placeholder="Search">
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Rule Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>BT</td>
                                    <td>Bhutan</td>
                                    <td>Bhutan</td>
                                    <td>Bhutan</td>
                                    <td>Bhutan</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-center text-danger">No Data found</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Expense-->
                <div class="tab-pane fade" id="pills-time_zone" role="tabpanel" aria-labelledby="pills-time_zone-tab">
                    <div class="row">
                        <div class="col-3">
                            <label style="float:left">Show &nbsp;</label>
                            <div class="dataTables_length" id="tbl_attendancesheet_length" style="float:left">
                                <select name="tbl_attendancesheet_length" aria-controls="tbl_attendancesheet" class="form-control">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            &nbsp;
                            <label>entries</label>
                        </div>

                        <div class="col-3">
                            <input type="text" name="search" class="form-control" value="" placeholder="Search">
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Time Zone</th>
                                    <th>Name and Abbreviation</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>UTC +6</td>
                                    <td>Bhutan Time (BST)</td>
                                    <td>Bhutan</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-center text-danger">No Data found</td>
                                </tr>

                            </tbody>
                        </table>
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