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

<div class="col-sm-6">
    <h5>Leave Travel Concession (LTC) Report</h5>
</div>
<br>

<div class="block-header block-header-default">
   
    <div class="form-group">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Select Year</label>
                    <select class="form-control" name="year">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Select Month</label>
                    <select class="form-control" name="month">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="jan">January</option>
                        <option value="feb">February</option>
                        <option value="mar">March</option>
                        <option value="apr">April</option>
                        <option value="may">May</option>
                        <option value="jun">June</option>
                        <option value="jul">July</option>
                        <option value="aug">August</option>
                        <option value="sep">September</option>
                        <option value="oct">October</option>
                        <option value="nov">November</option>
                        <option value="dec">December</option>

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label"></label>
                    <button type="button" name="report" class="form-control btn btn-primary">
                        {{ request()->get('report') ?? 'Search' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
           
            
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
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
                                                        <tr>
                                                            <td colspan="8" class="text-center text-danger">No Holiday found</td>
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