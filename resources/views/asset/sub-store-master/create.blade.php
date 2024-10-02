@extends('layouts.app')
@section('page-title', 'Create Store')
@section('content')
<form action="{{ route('sub-store-master.store') }}" method="POST">
    @csrf
    <div class="card">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Main Store *</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="location">Location *</label>
                                <input type="text" class="form-control" name="location" value="{{ old('location') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
                <table id="grade-steps" class="table table-condensed table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th width="3%" class="text-center">#</th>
                            <th>Sub Store Name*</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (old('grade_steps') == '')
                        <tr>
                            <td class="text-center">
                                <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                            </td>
                            <td>
                                <input type="text" name="grade_steps[AAAAA][step_name]" class="form-control form-control-sm resetKeyForNew" required>
                            </td>
                            <td>
                                <input type="number" name="grade_steps[AAAAA][starting_salary]" class="form-control form-control-sm resetKeyForNew" value="0">
                            </td>
                            <td>
                                <input type="number" name="grade_steps[AAAAA][increment]" class="form-control form-control-sm resetKeyForNew" value="0">
                            </td>
                            
                        </tr>
                        @else
                        @foreach (old('grade_steps') as $key => $value)
                        <tr>
                            <td class="text-center">
                                <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                            </td>
                            <td>
                                <input type="text" name="grade_steps[AAAAA{{$key}}][step_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('step_name', $value['step_name']) }}" required>
                            </td>
                            <td>
                                <input type="number" name="grade_steps[AAAAA{{ $key }}][starting_salary]" class="form-control form-control-sm resetKeyForNew" value="{{ old('starting_salary', $value['starting_salary']) }}">
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
    <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('asset/sub-store-master') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
    </div>
</form>
@endsection