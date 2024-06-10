@extends('layouts.app')
@section('page-title', 'Expense Approval')
@section('content')


<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="expense" class="form-control" value="{{ request()->get('expense') }}" placeholder="Search">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="row" style="float:right">
                <div class="col-6 ">
                    <div class="btn-group mt-2 mb-2">
                        <button type="button" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown">
                            Approval Status
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:void(0);">Pending</a></li>
                            <li><a href="javascript:void(0);">Approved</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block-content">
        <div class="block-options">
            <div class="col-sm-8">
                <h5>Advance Approval</h5>
            </div>
            <div class="col-sm-6">
                <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve" value="Approve">
                <input class="btn-sm  btn-danger buttonsubmit " data-value="reject" type="button" value="Reject" id="btn_reject">
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
                                        <div class="dataTables_length" id="responsive-datatable_length" data-select2-id="responsive-datatable_length">
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
                                            <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>
                                                                    #
                                                                </th>
                                                                <th>
                                                                    EMPLOYEE
                                                                </th>
                                                                <th>
                                                                    Advance date
                                                                </th>
                                                                <th>
                                                                    advance type
                                                                </th>
                                                                <th>
                                                                    Amount
                                                                </th>
                                                                <th>
                                                                    interest amount </th>
                                                                <th>
                                                                    STATUS
                                                                </th>
                                                                <th>
                                                                    ACTION
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Adrian</td>
                                                                <td>Terry</td>
                                                                <td>Casual</td>
                                                                <td>2013/04/21</td>
                                                                <td>$543,769</td>
                                                                <td>0.5</td>
                                                                <td>0.5</td>
                                                                <td class="text-center">
                                                                    @if ($privileges->edit)
                                                                    <a href="" data-short_name="" data-name="" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                                                                        EDIT</a>
                                                                    @endif
                                                                    @if ($privileges->delete)
                                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url=""><i class="fa fa-trash"></i>
                                                                        DELETE</a>
                                                                    @endif
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

</div>




@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush