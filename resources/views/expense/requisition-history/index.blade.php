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
    <h5>Requisition History</h5>
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
                                                            REQUISITION NO
                                                        </th>
                                                        <th>
                                                            REQUISITION TYPE
                                                        </th>
                                                        <th>
                                                            CREATION DATE
                                                        </th>
                                                        <th>
                                                            ITEM DESCRIPTION
                                                        </th>
                                                        <th>
                                                            APPROVAL STATUS
                                                        </th>
                                                        <th>
                                                            ACTION
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


@endsection