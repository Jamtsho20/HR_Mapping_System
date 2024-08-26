@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')
<div class="card">
    <div class="card-header ">
        <div class="col-sm-4">
            <h5>Geography</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="card">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-country-tab" data-bs-toggle="pill" data-bs-target="#pills-country" type="button" role="tab" aria-controls="pills-country" aria-selected="true">Country</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-time_zone-tab" data-bs-toggle="pill" data-bs-target="#pills-time_zone" type="button" role="tab" aria-controls="pills-time_zone" aria-selected="false">Time Zone</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-region-tab" data-bs-toggle="pill" data-bs-target="#pills-region" type="button" role="tab" aria-controls="pills-region" aria-selected="false">Region</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-dzongkhag-tab" data-bs-toggle="pill" data-bs-target="#pills-dzongkhag" type="button" role="tab" aria-controls="pills-dzongkhag" aria-selected="false">Dzongkhag</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-store-tab" data-bs-toggle="pill" data-bs-target="#pills-store" type="button" role="tab" aria-controls="pills-store" aria-selected="false">Store Locations</button>
                </li>
            </ul>
            <br>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-country" role="tabpanel" aria-labelledby="pills-country-tab">
                    <div class="card-body">
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
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-center text-danger">No Data found</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- time zone -->
                <div class="tab-pane fade" id="pills-time_zone" role="tabpanel" aria-labelledby="pills-time_zone-tab">
                    <!-- <div class="row">
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
                    </div> -->

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
                <!-- region-->
                <div class="tab-pane fade" id="pills-region" role="tabpanel" aria-labelledby="pills-region-tab">
                    <!-- <div class="row">
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
                    </div> -->

                    <div class="card-body">
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
                                    <td>{{ $region->name }}</td>
                                    <td>Bhutan</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-danger">No Regions found</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div>{{ $regions->links() }}</div>
                    </div>
                </div>
                <!-- dzongkhag-->
                <div class="tab-pane fade" id="pills-dzongkhag" role="tabpanel" aria-labelledby="pills-dzongkhag-tab">
                    <div class="card-body">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Dzongkhag Name</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dzongkhags as $dzongkhag)
                                <tr>
                                    <td>{{ $dzongkhags->firstItem() + ($loop->iteration - 1) }}</td>
                                    <td>BT</td>
                                    <td>{{ $dzongkhag->dzongkhag }}</td>
                                    <td>Bhutan</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-danger">No Dzongkhags found</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div>{{ $dzongkhags->links() }}</div>
                    </div>
                </div>

                <!-- store location-->
                <div class="tab-pane fade" id="pills-store" role="tabpanel" aria-labelledby="pills-store-tab">
                    <h4 class="font-w400">Store Location</h4>
                    <p>...</p>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection