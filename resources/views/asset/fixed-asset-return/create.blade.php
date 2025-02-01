@extends('layouts.app')
@section('page-title', 'Asset Transfer')
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
            <div class="col-md-3">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Asset Return No</label>
                    <input type="text" placeholder="" class="form-control" id="tranno">
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="form-group form-focus select-focus" required>
                    <label class="control-label">Asset Return Type</label>
                    <select class="form-control" name="grn">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="employee-to-employee">Capitalized</option>
                        <option value="site-to-site">CIP</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-focus focused" required>
                    <label class="control-label">Return Date </label>
                    <input type="date" class="form-control" name="comdate">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-focus select-focus" required>
                    <label class="control-label">Employee Name</label>
                    <input type="text" placeholder="Miss Pema" class="form-control" id="empname">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Department</label>
                    <select class="form-control" name="newempnam" required>
                        <option value="" disabled selected hidden>Select</option>
                    </select>
                </div>
            </div>

            <div class="col-4">
                <label for="attachment">File</label>
                <input type="file" class="form-control" name="attachment" required="required">
            </div>
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
                                            <div class="dataTables_length" id="responsive-datatable_length"
                                                data-select2-id="responsive-datatable_length">
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
                                                                        Asset No
                                                                    </th>
                                                                    <th>
                                                                        Category
                                                                    </th>
                                                                    <th>
                                                                        Item Description
                                                                    </th>
                                                                    <th>
                                                                        UOM
                                                                    </th>
                                                                    <th>
                                                                        Unit
                                                                    </th>
                                                                    <th>
                                                                        Dzongkhag
                                                                    </th>
                                                                    <th>
                                                                        Store
                                                                    </th>
                                                                    <th>
                                                                        Condition Code
                                                                    </th>
                                                                    <th>
                                                                        Reason
                                                                    </th>
                                                                    <th>
                                                                        Action
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>+</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <br><br>
                                            <div class="col-sm-12 text-center">
                                                <button class="btn btn-primary btn-lg text-center" id="btn_create" type="button">Submit</button>
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

    </div>
</div>


@endsection