@extends('layouts.app')
@section('page-title', 'Leave Balance')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="leave_balance" class="form-control" value="{{ request()->get('leave_balance') }}">
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
                                                                    OPENING BALANCE
                                                                </th>
                                                                <th>
                                                                    CURRENT ENTITLEMENT
                                                                </th>
                                                                <th>
                                                                    LEAVE AVAILED
                                                                </th>
                                                                <th>
                                                                    CLOSING BALANCE
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($balances as $balance)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                {{-- <td>{{ $balance->short_name }}</td> --}}
                                                                <td>{{$balance->mas_employee_id}}</td>
                                                                <td>{{$balance->mas_leave_type_id}}</td>
                                                                <td>{{$balance->opening_balance}}</td>
                                                                <td>{{$balance->current_entitlement}}</td>
                                                                <td>{{$balance->leaves_availed}}</td>
                                                                <td>{{$balance->closing_balance}}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center text-danger">No Departments found</td>
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
</div>
@include('layouts.includes.delete-modal')
@endsection
