@extends('layouts.app')
@section('page-title', 'Region')
@section('content')
<form action="{{url('master/regions/' .$region->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="region">Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="region" value="{{ old('region', $region->name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="mas_employee_id">Regional manager</label>
                    <select class="form-control" name="mas_employee_id" required>
                        <option value="" hidden selected disabled>Select your option</option>
                        @foreach(employeeList() as $employee)
                        <option value="{{ $employee->id }}" {{ $region->mas_employee_id == $employee->id ? 'selected' : '' }}>
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
                        <!-- Checkbox to pass '1' when checked -->
                        <input type="checkbox"
                            name="status[is_active]"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active', $region->status) == 1 ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' =>url('master/regions'),
            'cancelName' => 'CANCEL'
            ])
           
        </div>
    </div>
</form>
<!--Region Location Details -->

@include('masters.region-location.index')




@include('layouts.includes.delete-modal')
@endsection