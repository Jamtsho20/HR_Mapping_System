@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <div class="col-sm-4">
            <h5>Geography</h5>
        </div>
    </div>
    <div class="block-content">
        <div class="block">
            <ul class="nav nav-tabs nav-tabs-block font-weight-bold" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" href="#country">Country</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="#time_zone">Time Zone</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="#region">Region</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="#dzongkhag">Dzongkhag</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="#store_location">Store Location</a>
                </li>
                <li class="nav-item ml-auto">
                    <a class="nav-link" href="#btabs-static-settings">
                        <i class="si si-settings"></i>
                    </a>
                </li>
            </ul>
            <div class="block-content tab-content">
                <!-- country -->
                <div class="tab-pane active" id="country" role="tabpanel">
                    <div class="row">
                        <div class="col-3">
                            <label style="float:left">Show &nbsp;</label>
                            <div class="dataTables_length" id="tbl_attendancesheet_length" style="float:left">
                                <select name="tbl_attendancesheet_length" aria-controls="tbl_attendancesheet"
                                    class="form-control">
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

                    <div class="block-content">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Country Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>BT</td>
                                    <td>Bhutan</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-center text-danger">No Data found</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- time zone -->
                <div class="tab-pane" id="time_zone" role="tabpanel">
                    <div class="row">
                        <div class="col-3">
                            <label style="float:left">Show &nbsp;</label>
                            <div class="dataTables_length" id="tbl_attendancesheet_length" style="float:left">
                                <select name="tbl_attendancesheet_length" aria-controls="tbl_attendancesheet"
                                    class="form-control">
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

                    <div class="block-content">
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
                                    <td><span class="badge badge-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-center text-danger">No Data found</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Region -->
                <div class="tab-pane" id="region" role="tabpanel">
                    <div class="row">
                        <div class="col-3">
                            <label style="float:left">Show &nbsp;</label>
                            <div class="dataTables_length" id="tbl_attendancesheet_length" style="float:left">
                                <select name="tbl_attendancesheet_length" aria-controls="tbl_attendancesheet"
                                    class="form-control">
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

                    <div class="block-content">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Region Name</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($regions as $region)
                                <tr>
                                    <td>{{ $regions->firstItem() + ($loop->iteration - 1) }}</td>
                                    <td>BT</td>
                                    <td>{{ $region->region_name }}</td>
                                    <td>Bhutan</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-danger">No Regions found</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Dzongkhag -->
                <div class="tab-pane" id="dzongkhag" role="tabpanel">
                    <h4 class="font-w400">dzongkhag</h4>
                    <p>...</p>
                </div>
                <!-- store location -->
                <div class="tab-pane" id="store_location" role="tabpanel">
                    <h4 class="font-w400">store location</h4>
                    <p>...</p>
                </div>
                <div class="tab-pane" id="btabs-static-settings" role="tabpanel">
                    <h4 class="font-w400">Settings Content</h4>
                    <p>...</p>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection