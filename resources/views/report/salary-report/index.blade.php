@extends('layouts.app')
@section('page-title', 'salary report')
@section('content')


<div class="col-sm-6">
    <h5>Salary Report</h5>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default">
                @component('layouts.includes.filter')
                <div class="col-4 form-group">
                    <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}" placeholder="Year">
                </div>
                @endcomponent

            </div>
            <br>
            <div class="row row-sm">
                <div class="card">
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
                                                            Employee Name
                                                        </th>
                                                        <th>
                                                            Job title
                                                        </th>
                                                        <th>
                                                            job nature
                                                        </th>
                                                        <th>
                                                            basic pay
                                                        </th>
                                                        <th>
                                                            house all.
                                                        </th>
                                                        <th>
                                                            medical all.
                                                        </th>
                                                        <th>
                                                            add. work all.
                                                        </th>
                                                        <th>
                                                            coporate all.
                                                        </th>
                                                        <th>
                                                            diff. all.
                                                        </th>
                                                        <th>
                                                            gross earning
                                                        </th>

                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($salaries as $salary)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$salary->employee->name}}</td>
                                                        <td>{{$salary->employee->empJob->designation->name}}</td>
                                                        <td>{{$salary->employee->empJob->empType->name}}</td>
                                                        <td>{{$salary->employee->empJob->basic_pay}}</td>
                                                        <td>{{ $salary->details['allowances']['House ALL'] ?? 'NA'}}</td>
                                                        <td>{{ $salary->details['allowances']['Medical ALL'] ?? 'NA'}}</td>
                                                        <td>{{ $salary->details['allowances']['Overtime ALL'] ?? 'NA'}}</td>
                                                        <td>{{ $salary->details['allowances']['Corporate  ALL'] ?? 'NA'}}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="11" class="text-center text-danger">No Salary Reports found</td>
                                                    </tr>
                                                    @endforelse
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


@endsection