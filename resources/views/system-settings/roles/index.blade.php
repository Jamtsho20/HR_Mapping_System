@extends('layouts.app')
@section('page-title', 'System Roles')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/roles/create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Role</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="dataTables_scroll">
                            <div class="dataTables_scrollHead"
                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                <div class="dataTables_scrollHeadInner"
                                    style="box-sizing: content-box; padding-right: 0px;">
                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                        <thead>
                                            <tr class="thead-light">
                                                <th class="text-center">#</th>
                                                <th>Role</th>
                                                <th class="text-center"><i class="fa fa-asterisk"></i> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($roles as $role)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td class="text-center">
                                                    @if ($privileges->view)
                                                    <a href="{{ url('system-setting/roles/' . $role->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                    @endif
                                                    @if ($privileges->edit)
                                                    <a href="{{ url('system-setting/roles/' . $role->id . '/edit') }}" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i> Edit</a>
                                                    @endif
                                                    @if ($privileges->delete)
                                                    <a href="#" class="delete-btn btn btn-sm btn-outline-danger" data-url="{{ url('system-setting/roles/' . $role->id) }}"><i class="fa fa-trash"></i> Delete</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="text-danger text-center">No roles to be displayed</td>
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
@include('layouts.includes.delete-modal')
@endsection