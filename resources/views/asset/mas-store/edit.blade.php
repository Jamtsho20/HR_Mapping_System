@extends('layouts.app')
@section('page-title', 'Edit Store')
@section('content')
    <form action="{{ url('asset/mas-store/' . $store->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Main Store</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Main Store *</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $store->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Location *</label>
                            <input type="text" class="form-control" name="location" value="{{ old('location', $store->location) }}" required>
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
                                    {{ old('status.is_active', $store->status) == 1 ? 'checked' : '' }} />
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
    @if ($store->subStores->isEmpty())
        <tr>
            <td class="text-center">
                <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
            </td>
            <td>
                <input type="text" name="sub_stores[0][name]" class="form-control form-control-sm resetKeyForNew" required>
            </td>
            <td>
                <input type="text" name="sub_stores[0][location]" class="form-control form-control-sm resetKeyForNew">
            </td>
            <td>
                <select name="sub_stores[0][status]" class="form-control form-control-sm resetKeyForNew" required>
                    <option value="1">Active</option>
                    <option value="0">Not Active</option>
                </select>
            </td>
        </tr>
    @else
        @foreach ($store->subStores as $key => $subStore)
            <tr>
                <td class="text-center">
                    <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="sub_stores[{{ $subStore->id }}][name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sub_stores.' . $subStore->id . '.name', $subStore->name) }}" required>
                </td>
                <td>
                    <input type="text" name="sub_stores[{{ $subStore->id }}][location]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sub_stores.' . $subStore->id . '.location', $subStore->location) }}">
                </td>
                <td>
                    <select name="sub_stores[{{ $subStore->id }}][status]" class="form-control form-control-sm resetKeyForNew" required>
                        <option value="1" {{ old('sub_stores.' . $subStore->id . '.status', $subStore->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('sub_stores.' . $subStore->id . '.status', $subStore->status) == '0' ? 'selected' : '' }}>Not Active</option>
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
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Update</button>
                <a href="{{ url('asset/mas-store') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </form>
@endsection
