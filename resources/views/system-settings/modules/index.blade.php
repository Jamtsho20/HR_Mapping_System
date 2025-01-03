@extends('layouts.app')
@section('page-title', 'System Modules')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/modules/create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Module</a>
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
                                                <th class="text-center">Icon</th>
                                                <th>Modules</th>
                                                <th class="text-center"><i class="fa fa-asterisk"></i> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($modules as $module)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center"><i class="fa {{ $module->icon }}"></i></td>
                                                <td>{{ $module->name }}</td>
                                                <td class="text-center">
                                                    @if ($privileges->edit)
                                                    <a href="{{ url('system-setting/modules/' . $module->id . '/edit') }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-danger text-center">No modules to be displayed</td>
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