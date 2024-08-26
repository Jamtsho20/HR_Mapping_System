@extends('layouts.app')
@section('page-title', 'Region')
@section('content')

<form action="{{ url('master/regions') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="region">Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="region" value="{{ old('region') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="mas_employee_id">Regional manager</label>
                    <select class="form-control" name="mas_employee_id" required>
                        <option value="" hidden selected disabled>Select your option</option>
                        @foreach(concateEmpNameUserName() as $employee)
                        <option value="{{ $employee->id }}" {{ old('mas_employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="status[is_active]" value="0">
                        <!-- Checkbox to pass '1' when checked, and retain old value -->
                        <input type="checkbox"
                            name="status[is_active]"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active') == '1' ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Region Location Details</h3>
                    </div>
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
                                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="pay_slab_details">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Name <span class="text-danger">*</span></th>
                                                                </th>
                                                                <th>Dzongkhang <span class="text-danger">*</span></th>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][name]" value="{{ old('name') }}" placeholder="Region Name" required>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][dzongkhag]" required>
                                                                        <option value="">Select Dzongkhag</option>
                                                                        @foreach($dzongkhags as $dzongkhag)
                                                                        <option value="{{ $dzongkhag->id }}" {{ old('details.AAAAA.dzongkhag') == $dzongkhag->id ? 'selected' : '' }}>
                                                                            {{ $dzongkhag->dzongkhag }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr class="notremovefornew">
                                                                <td colspan="4" class="text-right">
                                                                    <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                                                </td>
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
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
            <a href="{{ url('master/regions') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection