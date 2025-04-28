@extends('layouts.app')
@section('page-title', 'Delegation')
@section('content')

<form action="{{ url('system-setting/delegations') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header ">
            <h3 class="card-title">Add Delegation</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-4">
                    <label for="type">Role <span class="text-danger">*</span></label>
                    <select class="form-control" name="role">
                        <option value="">Select your option</option>
                        @foreach($delegatorRoles as $role)
                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }} 
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="">Delegatee <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="delegatee">
                    <option value="" disabled selected hidden>Select your option</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('delegatee') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->emp_id_name }} 
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="js-datepicker form-control js-datepicker" id="start_date" name="start_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>

                <div class="form-group col-4">
                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="js-datepicker form-control js-datepicker" id="end_date" name="end_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>

                <div class="form-group col-4">
                    <label for="remark">Remark</label>
                    <textarea class="form-control" name="remark">{{ old('remark') }}</textarea>
                </div>

                <div class="form-group col-4">
                    <label for="">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status">
                        {{-- Optional placeholder --}}
                        <option value="" disabled hidden>Select Status</option>
                        @foreach (config('global.status') as $key => $type)
                            <option value="{{ $key }}" {{ $key == 1 ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> Save
        </button>
        <a href="{{ url('system-setting/delegations') }}" class="btn btn-danger "> CANCEL</a>
    </div>
    </div>
   
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    $(document).ready(function () {
        $('#start_date').on('change', function () {
            let startDate = $(this).val();
            let $endDate = $('#end_date');

            // Set the min attribute on end_date
            $endDate.attr('min', startDate);

            // Clear end_date if it's before the selected start_date
            if ($endDate.val() < startDate) {
                $endDate.val('');
            }
        });
    });
</script>

@endpush