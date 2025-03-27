@extends('layouts.app')
@section('page-title', 'Leave Balance')
@section('buttons')
<a href="{{ url('leave/leave-apply') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Leave List</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header block-header-default">
                    <div class="form-group">
                        <div class="row">
                            @component('layouts.includes.filter')
                                <div class="col-8">
                                    <select class="form-control" id="mas_leave_type_id" name="mas_leave_type_id">
                                        <option value="" disabled selected hidden>Select Leave Type</option>
                                        @foreach ($leaveTypes as $type)
                                            <option @if ($type->id == request()->get('mas_leave_type_id')) selected @endif
                                                value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div class="row row-sm">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>EMPLOYEE</th>
                                                <th>LEAVE TYPE</th>
                                                <th>OPENING BALANCE</th>
                                                <th>CURRENT ENTITLEMENT</th>
                                                <th>LEAVE AVAILED</th>
                                                <th>CLOSING BALANCE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($balances as $balance)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $balance->employee->emp_id_name }}</td>
                                                    <td>{{ $balance->leaveType->name }}</td>
                                                    <td>{{ $balance->opening_balance }}</td>
                                                    <td>{{ $balance->current_entitlement }}</td>
                                                    <td>{{ $balance->leaves_availed }}</td>
                                                    <td>{{ $balance->closing_balance }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">No
                                                        Leave Balance found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if ($balances->hasPages())
                                <div class="card-footer">
                                    {{ $balances->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--End Row-->
    </div>

    @include('layouts.includes.delete-modal')
@endsection
