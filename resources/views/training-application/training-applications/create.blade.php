@extends('layouts.app')
@section('page-title', 'Create New Training Application')
@section('content')

<form action="{{ route('training-application.training-applications.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card">
        <div class="card-body">
            <input type="hidden" name="status" id="status" value="1">

            <!-- Training List Selection -->
            <div class="row">
                <div class="col-md-6">
                    <label for="training_list_id">Training List <span class="text-danger">*</span></label>
                    <select class="form-control select2" id="training_list_id" name="training_list_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($trainingLists as $list)
                        <option value="{{ $list->id }}" {{ old('training_list_id') == $list->id ? 'selected' : '' }}>
                            {{ $list->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Training Details Section -->
            <div class="card mt-4" id="training-details" style="display:none;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> Training Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Training Title</label>
                            <input type="text" class="form-control" id="training-title" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Training Type</label>
                            <input type="text" class="form-control" id="training-type" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Country</label>
                            <input type="text" class="form-control" id="training-country" readonly>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label>Training Nature</label>
                            <input type="text" class="form-control" id="training-nature" readonly>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label>Funding Type</label>
                            <input type="text" class="form-control" id="training-funding" readonly>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label>Department</label>
                            <input type="text" class="form-control" id="training-department" readonly>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label>Start Date</label>
                            <input type="text" class="form-control" id="training-start" readonly>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label>End Date</label>
                            <input type="text" class="form-control" id="training-end" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Selection Section -->
            <div class="card mt-4" id="employee-selection" style="display:none;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-users me-2"></i> Assign Employee to Training</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="employee-table" class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Employee</th>
                                    <th>Is Available for Training</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <select name="employees[AAAAA][employee_id]" class="form-control select2 resetKeyForNew" required>
                                            <option value="" disabled selected hidden>Select Employee</option>
                                            @foreach(employeeList() as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="employees[AAAAA][is_available]" class="form-control resetKeyForNew" required>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="notremovefornew">
                                    <td colspan="2"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('training/training-applications'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        // Initially hide both cards
        $('#training-details').hide();
        $('#employee-selection').hide();

        $('#training_list_id').on('change', function() {
            const id = $(this).val();

            if (id) {
                $.ajax({
                    url: "{{ url('training-applications/training-list') }}/" + id + "/details",
                    type: "GET",
                    success: function(data) {
                        // Populate training details
                        $('#training-title').val(data.title);
                        $('#training-type').val(data.training_type);
                        $('#training-country').val(data.country);
                        $('#training-nature').val(data.training_nature);
                        $('#training-funding').val(data.funding_type);
                        $('#training-start').val(data.start_date);
                        $('#training-end').val(data.end_date);
                        $('#training-department').val(data.department);

                        // Show the training details card
                        $('#training-details').slideDown();

                        // Show employee selection card after training details
                        $('#employee-selection').slideDown();
                    },
                    error: function() {
                        $('#training-details').hide();
                        $('#employee-selection').hide();
                    }
                });
            } else {
                $('#training-details').hide();
                $('#employee-selection').hide();
            }
        });
    });
</script>
@endpush