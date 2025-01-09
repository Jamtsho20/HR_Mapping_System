@extends('layouts.app')
@section('page-title', 'Create Modules and Sub Modules')
@section('content')
<form action="{{ url('system-setting/modules') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Module</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Main Module Name *</label>
                        <input type="text" name="main_module_name" class="form-control form-control-sm" value="{{ old('main_module_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Module Icon *</label>
                        <input type="text" name="module_icon" class="form-control form-control-sm" value="{{ old('module_icon') }}" placeholder="example: fa-check" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Display Order *</label>
                        <input type="number" name="module_display_order" class="form-control form-control-sm" value="{{ old('module_display_order') }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Sub Modules</h3>
                </div>
                <div class="card-body p-0">
                    <table id="sub-module" class="table table-condensed table-sm table-bordered">
                        <thead>
                            <th class="text-center">#</th>
                            <th>Sub Module Name *</th>
                            <th>Route *</th>
                            <th>Order *</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                </td>
                                <td>
                                    <input type="text" name="submodules[AAAAA][sub_module_name]" class="form-control form-control-sm resetKeyForNew" required>
                                </td>
                                <td>
                                    <input type="text" name="submodules[AAAAA][route]" class="form-control form-control-sm resetKeyForNew" required>
                                </td>
                                <td width="8%">
                                    <input type="number" name="submodules[AAAAA][display_order]" class="form-control form-control-sm resetKeyForNew" required>
                                </td>
                            </tr>
                            <tr class="notremovefornew">
                                <td colspan="3"></td>
                                <td class="text-center">
                                    <a href="#" class="add-table-row btn btn-info btn-sm"><i class="fa fa-plus"></i> Add New Row</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> CREATE MODULE</button>
                    <a href="{{ url('system-setting/modules') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection