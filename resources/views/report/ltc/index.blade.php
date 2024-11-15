@extends('layouts.app')
@section('page-title', 'LTC Report')
@section('content')


<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-4 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}" placeholder="Year">
    </div>

    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Travel Concession (LTC) Report</h3>
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
                            <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                <thead class="thead-light">
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
                                        <td colspan="8" class="text-center text-danger">No LTC Reports found</td>
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





@endsection