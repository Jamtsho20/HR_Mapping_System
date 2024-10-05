@extends('layouts.app')
@section('page-title', 'Create Store')
@section('content')
<form action="{{ route('mas-store.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Main Store</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Main Store *</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location *</label>
                        <input type="text" class="form-control" name="location" value="{{ old('location') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="custom-switch">
                            <!-- Hidden input to pass '0' when checkbox is unchecked -->
                            <input type="hidden" name="status[is_active]" value="0">
                            <!-- Checkbox to pass '1' when checked -->
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

                <div class="col-md-8">
                    <div class="table-responsive">
                        <table id="sub-store" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Sub Store Name *</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('sub_stores') == '')
                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <input type="text" name="sub_stores[AAAAA][name]" class="form-control form-control-sm resetKeyForNew" required>
                                    </td>
                                    <td>
                                        <input type="text" name="sub_stores[AAAAA][location]" class="form-control form-control-sm resetKeyForNew">
                                    </td>
                                    <td>
                                        <select name="sub_stores[AAAAA][status]" class="form-control form-control-sm resetKeyForNew" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </td>
                                </tr>
                                @else
                                @foreach (old('sub_stores') as $key => $value)
                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <input type="text" name="sub_stores[AAAAA{{ $key }}][name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('name', $value['name']) }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="sub_stores[AAAAA{{ $key }}][location]" class="form-control form-control-sm resetKeyForNew" value="{{ old('location', $value['location']) }}">
                                    </td>
                                    <td>
                                        <select name="sub_stores[AAAAA{{ $key }}][status]" class="form-control form-control-sm resetKeyForNew" required>
                                            <option value="active" {{ (old('status', $value['status']) == 'active') ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ (old('status', $value['status']) == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                <tr class="notremovefornew">
                                    <td colspan="3"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
            <a href="{{ url('asset/mas-store') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
@endsection