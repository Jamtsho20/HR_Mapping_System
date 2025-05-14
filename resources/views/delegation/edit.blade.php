@extends('layouts.app')
@section('page-title', 'Delegation')
@section('content')

<form action="{{ url('delegation/delegations/' . $delegation->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="role">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-control" id="role">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($delegatorRoles as $role)
                        <option value="{{ $role->id }}" {{ $delegation->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="delegatee">Delegatee <span class="text-danger">*</span></label>
                    <select name="delegatee" class="form-control" id="delegatee">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $delegation->delegatee_id == $employee->id ? 'selected' : '' }}>{{ $employee->emp_id_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') ?? $delegation->start_date }}" required="required">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') ?? $delegation->end_date }}" required="required">
                </div>
                <div class="form-group col-md-4">
                    <label for="code">Remark</label>
                    <textarea class="form-control" name="remark">{{ old('remark') ?? $delegation->remark }}</textarea>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" id="status" required>
                        <option value="" disabled selected hidden>Select Your Option</option>
                        @foreach (config('global.status') as $key => $type)
                            <option value="{{ $key }}" {{ $key == $delegation->status ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('delegation/delegations') ,
            'cancelName' => 'CANCEL'
            ])
           
        </div>
    </div>

</form>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    $(document).ready(function () {
        const $startDate = $('#start_date');
        const $endDate = $('#end_date');

        // On page load, enforce min for end_date
        if ($startDate.val()) {
            $endDate.attr('min', $startDate.val());
        }

        // On start_date change, update min for end_date
        $startDate.on('change', function () {
            let startDate = $(this).val();
            $endDate.attr('min', startDate);

            if ($endDate.val() < startDate) {
                $endDate.val('');
            }
        });
    });
</script>

@endpush