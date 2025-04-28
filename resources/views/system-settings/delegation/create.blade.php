@extends('layouts.app')
@section('page-title', 'Delegation')
@section('content')

<form action="{{ url('system-setting/delegations') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                {{-- tabular form --}}
                <div class="table-responsive">
                    <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th>Role*</th>
                                <th>Delegatee*</th>
                                <th>Start Date*</th>
                                <th>End Date*</th>
                                <th>Remark</th>
                                <th>Status*</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td class="text-center">
                                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew select2" name="delegations[AAAAA][role]" required>
                                        <option value="">Select your option</option>
                                        @foreach($delegatorRoles as $role)
                                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew select2" name="delegations[AAAAA][delegatee]" required >
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('delegatee') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->emp_id_name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="date" name="delegations[AAAAA][start_date]" value="" class="form-control form-control-sm resetKeyForNew" required />
                                </td>
                                <td>
                                    <input type="date" name="delegations[AAAAA][end_date]" value="" class="form-control form-control-sm resetKeyForNew" required />
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm resetKeyForNew" name="delegations[AAAAA][remark]"></textarea>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew select2" name="delegations[AAAAA][status]" required>
                                    <option value="" disabled selected hidden>Select Your Option</option>
                                    @foreach (config('global.status') as $key => $type)
                                        <option value="{{ $key }}" {{ $key == 1 ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr class="notremovefornew">
                                <td colspan="6"></td>
                                <td class="text-right">
                                    <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus">
                                        </i> Add New Row</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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

@include('layouts.includes.alert-message')
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