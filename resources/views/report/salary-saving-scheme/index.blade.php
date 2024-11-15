@extends('layouts.app')
@section('page-title', 'SSS')
@section('content')


<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-4 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}" placeholder="Year">
    </div>
    @endcomponent
   
    <div class="row">
        <div class="col-md-12">
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Salary Saving Sheme (SSS) Report</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="col-sm-12">
                                    <label>
                                        Show
                                        <select class="select2">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        entries
                                    </label>
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                                    <thead class="thead-light">
                                                        <tr role="row">
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                Full name
                                                            </th>
                                                            <th>
                                                                policy number
                                                            </th>
                                                            <th>
                                                                sss amount
                                                            </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No SSS Reports found</td>
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