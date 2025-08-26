@extends('layouts.app')
@section('page-title', 'Create New Field Employee')
@section('content')

<form action="{{ route('field-employee.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                        <select class="form-control select2 select2-hidden-accessible" name="mas_employee_id">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach (employeeList() as $employee)
                            <option value="{{ $employee->id }}" {{ old('mas_employee_id') == $employee->id ? 'selected' : '' }}>
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
            'buttonName' => 'SAVE',
            'cancelUrl' => url('employee/field-employee') ,
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush