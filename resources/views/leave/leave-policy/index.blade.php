@extends('layouts.app')
@section('page-title', 'Leave Policy')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('leave-policy.create')}}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i>Add New Leave Policy
</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}" placeholder="Leave Type">
    </div>
    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                <thead>
                                                    <tr role="row" class="thead-light">
                                                        <th>
                                                            #
                                                        </th>
                                                        <th>
                                                            Policy
                                                        </th>
                                                        <th>
                                                            LEAVE TYPE
                                                        </th>
                                                        <th>
                                                            Start DATE
                                                        </th>
                                                        <th>
                                                            End DATE
                                                        </th>

                                                        <th>
                                                            STATUS
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                    @foreach($leavePolicy as $leave)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$leave->name}}</td>
                                                        <td>{{$leave->leaveType->name}}</td>
                                                        <td>{{$leave->start_date}}</td>
                                                        <td>{{$leave->end_date}}</td>
                                                        <td><span class="badge rounded-pill  bg-{{$leave->status == 1 ? 'primary' : 'danger' }} me-1 mt-1"> {{$leave->status == 1 ? 'Enforced' : 'Draft'}}</span></td>
                                                        <td class="text-center">
                                                            @if ($privileges->view)
                                                            <a href="{{ url('leave/leave-policy/' . $leave->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                            @endif
                                                            @if ($privileges->edit)
                                                            <a href="{{ url('leave/leave-policy/' . $leave->id . '/edit') }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                            @endif
                                                            @if ($privileges->delete)
                                                            <a href="#" data-url="{{ url('leave/leave-policy/' . $leave->id) }}" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"><i class="fa fa-trash"></i>
                                                                DELETE</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
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