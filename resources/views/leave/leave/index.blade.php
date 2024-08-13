@extends('layouts.app')
@section('page-title', 'Leave')
@section('content')
<div class="block">
    <div class="block-options">
        <div class="block-options-item">
            <a href="{{ route('leave.leave-encashment')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Leave Encashment
            </a>
            <a href="{{route('leave-apply.create')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Apply Leave
            </a>
            <a href="{{ route('leave.leave-balance')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-calendar"></i> Leave Balance
            </a>
        </div>
    </div>
    <br>
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}" placeholder="Leave Type">
        </div>
        @endcomponent
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
                                                                LEAVE TYPE
                                                            </th>
                                                            <th>
                                                                FROM DATE
                                                            </th>
                                                            <th>
                                                                TO DATE
                                                            </th>
                                                            <th>
                                                                NO OF DAYS
                                                            </th>
                                                            <th>
                                                                STATUS
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
@include('layouts.includes.delete-modal')
@endsection