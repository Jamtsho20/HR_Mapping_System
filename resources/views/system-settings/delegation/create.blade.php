@extends('layouts.app')
@section('page-title', 'Delegation')
@section('content')

<form action="" method="POST">
    @csrf
    <div class="card">
        <div class="card-header ">
            <h3 class="card-title">Add Delegation</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-4">
                    <label for="type">Type<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="type" value="{{ old('type') }}" required="required">
                </div>


                <div class="form-group col-4">
                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="js-datepicker form-control js-datepicker" id="example-datepicker1" name="example-datepicker1" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>

                <div class="form-group col-4">
                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="js-datepicker form-control js-datepicker" id="example-datepicker1" name="example-datepicker1" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>

                <div class="form-group col-4">
                    <label for="">Delegate To <span class="text-danger">*</span></label>
                    <select class="form-control" name="delegate_to">
                    <option value="">Select an employee</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->emp_id_name }} 
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status">
                        <option value="" disabled selected hidden>Select Status</option>
                        @foreach (config('global.status') as $key => $type)
                        <option value="{{ $key}}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> Save
        </button>
        <a href="{{ url('system-setting/delegations') }}" class="btn btn-danger "> CANCEL</a>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush