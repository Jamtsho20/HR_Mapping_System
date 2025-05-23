@extends('layouts.app')
@section('page-title', 'Role & Permission')
@section('buttons')
    <a href="{{ url('system-setting/roles')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Showing role details and permissions for <strong class="text-danger">{{ $role->name }}</strong></h3>
            </div>
            <div class="card-body p-0">
                <table id="set-access" class="table table-condensed table-hover table-sm table-bordered">
                    <thead class="thead-light">
                        <th>Main Module</th>
                        <th>Sub Module</th>
                        <th class="text-center">View</th>
                        <th class="text-center">Create</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                    </thead>
                    <tbody>
                        @php $currentTopMenu = ''; @endphp
                        @foreach($modules as $module)
                            @if($module->top_menu != $currentTopMenu && $currentTopMenu != '')
                                <thead class="thead-light">
                                    <th><strong>Main</strong> Module</th>
                                    <th><strong>Sub</strong> Module</th>
                                    <th class="text-center"><strong>View</strong></th>
                                    <th class="text-center"><strong>Create</strong></th>
                                    <th class="text-center"><strong>Edit</strong></th>
                                    <th class="text-center"><strong>Delete</strong></th>
                                </thead>
                            @endif
                            <tr>
                                <td>{{ $module->top_menu != $currentTopMenu ? $module->top_menu : '' }}</td>
                                <td>
                                    {{ $module->sub_menu }}
                                </td>
                                <td class="text-center">
                                    {!! $module->view == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                                </td>
                                <td class="text-center">
                                    {!! $module->create == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                                </td>
                                <td class="text-center">
                                    {!! $module->edit == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                                </td>
                                <td class="text-center">
                                    {!! $module->delete == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                                </td>
                            </tr>
                            @php $currentTopMenu = $module->top_menu; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection