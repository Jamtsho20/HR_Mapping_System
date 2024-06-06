@extends('layouts.app')
@section('page-title', 'Fuel Approval')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="employee" class="form-control" value="{{ request()->get('employee') }}"
                placeholder="Search">
        </div>
        @endcomponent
   
    </div>
    <div class="block-content">
        <div class="block-options">
            <div class="col-sm-8">
                <h5>Expense Fuel Approval</h5>
            </div>
            <div class="col-sm-6">
                <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                    value="Approve">
                <input class="btn-sm  btn-danger buttonsubmit " data-value="reject" type="button" value="Reject"
                    id="btn_reject">
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
                                                                DATE
                                                            </th>
                                                            <th>
                                                                location
                                                            </th>
                                                            <th>
                                                                VEHICLE TYPE
                                                            </th>
                                                            <th>
                                                                MILEAGE
                                                            </th>
                                                            <th>
                                                                AMOUNT
                                                            </th>
                                                            <th>
                                                                STATUS
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
                                                            <td>1000</td>
                                                            <td><span class="badge bg-success">Approved</span>
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






@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush