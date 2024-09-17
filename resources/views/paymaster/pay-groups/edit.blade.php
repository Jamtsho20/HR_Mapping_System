@extends('layouts.app')

@section('page-title', 'Edit Pay Group')

@section('content')
<form action="{{ url('paymaster/pay-groups/' . $payGroup->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pay-group-name" name="name" value="{{ old('name', $payGroup->name) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="applicable_on">Applicable On<span class="text-danger">*</span></label>
                <select name="applicable_on" id="applicable_on" class="form-control" required>
                    <option value="" disabled selected hidden>Select an option</option>
                    <option value="1" {{ old('applicable_on', $payGroup->applicable_on) == 1 ? 'selected' : '' }}>Employee Group</option>
                    <option value="2" {{ old('applicable_on', $payGroup->applicable_on) == 2 ? 'selected' : '' }}>Grade</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-groups') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
<!-- Pay Group Details Form -->
@include('paymaster.pay-group-details.index', ['payGroup' => $payGroup])

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the applicable_on select element and the form sections
        var applicableOnSelect = document.getElementById('applicable_on');
        var gradeSection = document.getElementById('grade-section');
        var employeeGroupSection = document.getElementById('employee-group-section');

        // Function to toggle the form sections
        function toggleFormSections() {
            var selectedValue = applicableOnSelect.value;

            if (selectedValue == '1') {
                // Show employee group section, hide grade section
                employeeGroupSection.style.display = 'block';
                gradeSection.style.display = 'none';
            } else if (selectedValue == '2') {
                // Show grade section, hide employee group section
                gradeSection.style.display = 'block';
                employeeGroupSection.style.display = 'none';
            } else {
                // Hide both sections if nothing is selected
                gradeSection.style.display = 'none';
                employeeGroupSection.style.display = 'none';
            }
        }

        // Initial toggle based on the preselected value
        toggleFormSections();

        // Event listener for when the user changes the selection
        applicableOnSelect.addEventListener('change', toggleFormSections);
    });
</script>
@endpush
