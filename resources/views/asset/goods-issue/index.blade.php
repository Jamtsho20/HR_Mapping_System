@extends('layouts.app')
@section('page-title', 'Goods Issue')
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
<div class="block-header block-header-default">
   
    <div class="form-group">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Issue No</label>
                    <input type="text" placeholder="" class="form-control" id="issueno">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Issue Date</label>
                    <input type="date" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Requisition No</label>
                    <select class="form-control" name="reqno">
                        <option value="" disabled selected hidden>Select</option>
                    </select>                   
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Employee Name</label>
                    <select class="form-control" name="empname">
                        <option value="" disabled selected hidden>Select</option>
                    </select>                   
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Department</label>
                    <select class="form-control" name="issueno">
                        <option value="" disabled selected hidden>Select</option>
                    </select>    
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Requisition Date</label>
                    <input type="date" class="form-control">
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
                                                                PO
                                                            </th>
                                                            <th>
                                                                Item Description
                                                            </th>
                                                            <th>
                                                                UOM
                                                            </th>
                                                            <th>
                                                                Store
                                                            </th>
                                                            <th>
                                                                Stock Staus
                                                            </th>
                                                            <th>
                                                                LEAVE TYPE
                                                            </th>
                                                            <th>
                                                                Quantity Issued
                                                            </th>
                                                            <th>
                                                                Dzongkhag
                                                            </th>
                                                            <th>
                                                                Site Name
                                                            </th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="12" class="text-center text-danger">No goods issue found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-primary btn-lg text-center" id="btn_create" type="button">Issue</button>
                                        <input type="hidden" id="hdn_count" value="0">
                                        <button class="btn btn-primary btn-lg text-center" id="btn_create" type="button">Reset</button>
                                        <input type="hidden" id="hdn_count" value="0">
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