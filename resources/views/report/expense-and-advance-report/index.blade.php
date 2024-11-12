@extends('layouts.app')
@section('page-title', 'Vehicle Fuel Report')
@section('content')


<div class="col-sm-6">
    <h5>Vehicle Fuel Report</h5>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default">
                @component('layouts.includes.filter')
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Select Expense</label>
                            <select class="form-control" name="year">
                                <option value="" disabled selected hidden>Select</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Employe Name</label>
                            <select class="form-control" name="month">
                                <option value="" disabled selected hidden>Select</option>
                                <option value="jan">January</option>
                                <option value="feb">February</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Department</label>
                            <select class="form-control" name="department">
                                <option value="" disabled selected hidden>Select</option>
                                <option value="1">Strategic Planning and Projects</option>
                                <option value="2">Core Network and Carrier Services</option>
                                <option value="4">Finance</option>
                                <option value="7">Management Information System</option>
                                <option value="8">Commercial</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Section</label>
                            <select class="form-control" name="department">
                                <option value="14">ISP Access</option>
                                <option value="32">Power &amp; Utilities</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Region</label>
                            <select class="form-control" name="department">
                                <option value="14">ISP Access</option>
                                <option value="32">Power &amp; Utilities</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Location</label>
                            <select class="form-control" name="department">
                                <option value="14">ISP Access</option>
                                <option value="32">Power &amp; Utilities</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Managerr</label>
                            <select class="form-control" name="department">
                                <option value="" disabled selected hidden>Select</option>
                                <option value="1">Strategic Planning and Projects</option>
                                <option value="2">Core Network and Carrier Services</option>
                                <option value="4">Finance</option>
                                <option value="7">Management Information System</option>
                                <option value="8">Commercial</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Year</label>
                            <select class="form-control" name="department">
                                <option value="14">ISP Access</option>
                                <option value="32">Power &amp; Utilities</option>
                                <option value="49">Access Network</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Month</label>
                            <select class="form-control" name="department">
                                <option value="14">ISP Access</option>
                                <option value="32">Power &amp; Utilities</option>
                                <option value="49">Access Network</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Paid Status</label>
                            <select class="form-control" name="leavetype">
                                <option value="" disabled selected hidden>Select</option>
                                <option value="1">Fully Paid</option>
                                <option value="2">Not Paid</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="control-label">Invoice Status</label>
                            <select class="form-control" name="leavetype">
                                <option value="" disabled selected hidden>Select</option>
                                <option value="1">Validated</option>
                                <option value="2">Never Validated</option>
                                <option value="3">Cancelled</option>
                                <option value="4">Unalidated</option>
                                <option value="5">Available</option>
                                <option value="6">Available Payment</option>
                                <option value="8">Manual</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endcomponent
            </div>
            <br>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
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
                                                            Region Name
                                                        </th>
                                                        <th>
                                                            date of entry
                                                        </th>
                                                        <th>
                                                            vehicle type
                                                        </th>
                                                        <th>
                                                            vehicle number
                                                        </th>
                                                        <th>
                                                            mileage
                                                        </th>
                                                        <th>
                                                            amount
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="10" class="text-center text-danger">No Vehicle Fuel report found</td>
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



@endsection