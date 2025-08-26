@extends('layouts.app')
@section('page-title', 'Edit Field Employees')
@section('content')

<form action="{{ route('field-employee.update', $field->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="mas_employee_id">
                            <option value="" disabled>Select your option</option>
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ (old('mas_employee_id', $field->mas_employee_id ?? '') == $employee->id) ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('employee/field-employee'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush