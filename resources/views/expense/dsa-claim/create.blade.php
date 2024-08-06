@extends('layouts.app')
@section('page-title', 'DSA Claim and Settlement')
@section('content')

<form action="{{ route('dsa-claim-settlement.store') }}" method="POST">
    @csrf
    <div class="block-header block-header-default">
        <div class="form-group">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group form-focus select-focus">
                        <label class="control-label">E.name/With code</label>
                        <input type="text" class="form-control  myclass input_search" id="ename">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-focus select-focus">
                        <label class="control-label">Adv. No</label>
                        <input type="text" class="form-control  myclass input_search" id="advanceno">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-focus select-focus">
                        <label class="control-label">Adv. Amt</label>
                        <input type="text" class="form-control  myclass input_search" id="advanceamount">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-focus select-focus">
                        <label class="control-label">Total Amt Adj</label>
                        <input type="text" class="form-control  myclass input_search" id="totalamountadjusted">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-focus select-focus">
                        <label class="control-label">Net Payable Amt</label>
                        <input type="text" class="form-control  myclass input_search" id="netpayableamount">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-focus select-focus">
                        <label class="control-label">Balance Amt</label>
                        <input type="text" class="form-control  myclass input_search" id="balanceamounts">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-focus select-focus">
                        <label class="control-label">Attachment</label>
                        <input type="file" class="form-control  myclass input_search" id="attachment">
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
                                                                            From Date
                                                                        </th>
                                                                        <th>
                                                                            From Location
                                                                        </th>
                                                                        <th>
                                                                            To Date
                                                                        </th>
                                                                        <th>
                                                                            To Location
                                                                        </th>
                                                                        <th>
                                                                            Total Days
                                                                        </th>
                                                                        <th>
                                                                            DA
                                                                        </th>
                                                                        <th>
                                                                            PA
                                                                        </th>
                                                                        <th>
                                                                            Total Amount
                                                                        </th>
                                                                        <th>
                                                                            Remarks
                                                                        </th>
                                                                    </tr>
                                                                </thead>
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
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush