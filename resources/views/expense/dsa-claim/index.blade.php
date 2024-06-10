@extends('layouts.app')
@section('page-title', 'DSA Claim')
@section('content')


<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="expense" class="form-control" value="{{ request()->get('expense') }}"
                placeholder="Search">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">

                <button type="button" data-bs-toggle="modal" data-bs-target="#dsa" class="btn btn-sm btn-primary"><i
                        class="fa fa-plus"></i> DSA Settlement</button>
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
                                                                EMPLOYEE
                                                            </th>
                                                            <th>
                                                                Creation DATE
                                                            </th>
                                                            <th>
                                                                total payable amount
                                                            </th>
                                                            <th>
                                                                adv. blance amount
                                                            </th>
                                                            <th>
                                                                total amount
                                                            </th>
                                                            <th>
                                                                STATUS
                                                            </th>
                                                            <th>
                                                                action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Kinga</td>
                                                            <td>02/08/2022</td>
                                                            <td>Amount</td>
                                                            <td>5000</td>
                                                            <td>Money</td>
                                                            <td><span class="badge bg-success">Approved</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="" data-short_name="" data-name=""
                                                                    class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i
                                                                        class="fa fa-edit"></i>
                                                                    EDIT</a>
                                                                <a href="#"
                                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                    data-url=""><i class="fa fa-trash"></i>
                                                                    DELETE</a>
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

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="dsa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">DSA Settlement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf

                    <div class="form-group">
                        <br>
                        <div class="row">
                            <div class="col-3">
                                <label for="employee">Employee Name/code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="employee" required="required"
                                    readonly="readonly">
                            </div>

                            <div class="col-2">
                                <label for="advance_no">Advance No </label>
                                <input type="text" class="form-control" name="advance_no" required="required">
                            </div>

                            <div class="col-2">
                                <label for="advance_amount">Advance Amount </label>
                                <input type="text" class="form-control" name="advance_amount" required="required"
                                    readonly="readonly" value="0">
                            </div>

                            <div class="col-2">
                                <label for="total">Total Amt Adjusted </label>
                                <input type="text" class="form-control" name="total" required="required"
                                    readonly="readonly" value="0">
                            </div>

                            <div class="col-3">
                                <label for="net_amount">Net Payable Amount <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="net_amount" required="required"
                                    readonly="readonly" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <label for="attachment">File</label>
                                <input type="file" class="form-control" name="attachment" required="required">
                            </div>

                            <div class="col-2">
                                <br><br>
                                <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                            </div>
                            <div class="col-2">
                                <label for="balance">Balace Amount<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="balance" required="required"
                                    readonly="readonly" value="0">
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-3">
                            <table class="table table-bordered table-sm table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>File</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="col-sm-12">
                        <table id="tbladditem" class="table table-hover table-white">
                            <thead>
                                <tr>
                                    <th>From Date</th>
                                    <th>From Location</th>
                                    <th>To Date</th>
                                    <th>To Location</th>
                                    <th>Total Days</th>
                                    <th>DA</th>
                                    <th>TA</th>
                                    <th>Total Amount</th>
                                    <th>Remarks</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tablerheader">
                                    <td style="width:200px;font-size:smaller">
                                        <input class="js-datepicker form-control" type="text" id="tbltxtfromdate"
                                            readonly="readonly" style="background-color: rgb(255, 255, 255);">
                                    </td>
                                    <td style="width:150px">
                                        <input type="text" tabindex="1" class="form-control" id="tbltxtfromlocation"
                                            autocomplete="off">
                                    </td>

                                    <td style="width:200px;font-size:smaller">
                                        <input tabindex="2" class="js-datepicker form-control" type="text"
                                            id="tbltxttodate" readonly="readonly"
                                            style="background-color: rgb(255, 255, 255);">
                                    </td>

                                    <td style="width:150px"><input tabindex="3" type="text" class="form-control"
                                            id="tbltxttolocation" autocomplete="off"></td>
                                    <td style="width:90px"><input tabindex="4" type="text" id="total_days"
                                            class="form-control myDecimal"></td>
                                    <td style="width:120px"><input tabindex="4" type="text" id="tbltxtda"
                                            class="form-control" readonly=""></td>
                                    <td style="width:90px"><input type="text" tabindex="5" id="tbltxtta"
                                            class="form-control myDecimal" autocomplete="off"></td>
                                    <td style="width:120px"><input tabindex="6" class="form-control" type="text"
                                            id="tbltxtamount" readonly="" autocomplete="off"></td>
                                    <td style="width:220px"><textarea tabindex="7" maxlength="200" class="form-control"
                                            id="tbltxtRemarks" autocom=""></textarea></td>
                                    <td><a href="" class="text-success font-18" title="Add" tabindex="2"><i
                                                class="fa fa-plus myadddsasettlement"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                  
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<!-- CREATE EXPENSE -->




<div class="modal show " id="dsa" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width: 1000px;">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="modal-header">
                        <h3 class="modal-title">DSA Settlement</h3>
                        <div class="block-options">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>

                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <br>
                            <div class="row">
                                <div class="col-3">
                                    <label for="employee">Employee Name/code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="employee" required="required"
                                        readonly="readonly">
                                </div>

                                <div class="col-2">
                                    <label for="advance_no">Advance No </label>
                                    <input type="text" class="form-control" name="advance_no" required="required">
                                </div>

                                <div class="col-2">
                                    <label for="advance_amount">Advance Amount </label>
                                    <input type="text" class="form-control" name="advance_amount" required="required"
                                        readonly="readonly" value="0">
                                </div>

                                <div class="col-2">
                                    <label for="total">Total Amt Adjusted </label>
                                    <input type="text" class="form-control" name="total" required="required"
                                        readonly="readonly" value="0">
                                </div>

                                <div class="col-3">
                                    <label for="net_amount">Net Payable Amount <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="net_amount" required="required"
                                        readonly="readonly" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-3">
                                    <label for="attachment">File</label>
                                    <input type="file" class="form-control" name="attachment" required="required">
                                </div>

                                <div class="col-2">
                                    <br><br>
                                    <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                                </div>
                                <div class="col-2">
                                    <label for="balance">Balace Amount<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="balance" required="required"
                                        readonly="readonly" value="0">
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-3">
                                <table class="table table-bordered table-sm table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <table id="tbladditem" class="table table-hover table-white">
                        <thead>
                            <tr>
                                <th>From Date</th>
                                <th>From Location</th>
                                <th>To Date</th>
                                <th>To Location</th>
                                <th>Total Days</th>
                                <th>DA</th>
                                <th>TA</th>
                                <th>Total Amount</th>
                                <th>Remarks</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tablerheader">
                                <td style="width:200px;font-size:smaller">
                                    <input class="js-datepicker form-control" type="text" id="tbltxtfromdate"
                                        readonly="readonly" style="background-color: rgb(255, 255, 255);">
                                </td>
                                <td style="width:150px">
                                    <input type="text" tabindex="1" class="form-control" id="tbltxtfromlocation"
                                        autocomplete="off">
                                </td>

                                <td style="width:200px;font-size:smaller">
                                    <input tabindex="2" class="js-datepicker form-control" type="text" id="tbltxttodate"
                                        readonly="readonly" style="background-color: rgb(255, 255, 255);">
                                </td>

                                <td style="width:150px"><input tabindex="3" type="text" class="form-control"
                                        id="tbltxttolocation" autocomplete="off"></td>
                                <td style="width:90px"><input tabindex="4" type="text" id="total_days"
                                        class="form-control myDecimal"></td>
                                <td style="width:120px"><input tabindex="4" type="text" id="tbltxtda"
                                        class="form-control" readonly=""></td>
                                <td style="width:90px"><input type="text" tabindex="5" id="tbltxtta"
                                        class="form-control myDecimal" autocomplete="off"></td>
                                <td style="width:120px"><input tabindex="6" class="form-control" type="text"
                                        id="tbltxtamount" readonly="" autocomplete="off"></td>
                                <td style="width:220px"><textarea tabindex="7" maxlength="200" class="form-control"
                                        id="tbltxtRemarks" autocom=""></textarea></td>
                                <td><a href="" class="text-success font-18" title="Add" tabindex="2"><i
                                            class="fa fa-plus myadddsasettlement"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check"></i>Submit
                    </button>
                    <button type="button" class="btn btn-alt-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush