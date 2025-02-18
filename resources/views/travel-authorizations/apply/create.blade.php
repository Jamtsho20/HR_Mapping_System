@extends('layouts.app')
@section('page-title', 'Create Travel Authorization')
@section('content')
    @include('layouts.includes.loader')
    <form action="{{ route('apply-travel-authorization.store') }}" method="POST" id="apply_travel"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="advance_no">Travel Authorizaiton No <span class="text-danger"></span></label>
                            <input type="text" class="form-control" name="travel_authorization_no" id="travel_no"
                                value="{{ old('advance_no') }}" placeholder="Generating..." readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Date <span class="text-danger"></span></label>

                            <!-- Display formatted date for the user (e.g., 10-Jan-2025) -->
                            <input type="text" class="form-control" id="formatted-date"
                                value="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" readonly>

                            <!-- Hidden input field to store date in YYYY-MM-DD format (for database) -->
                            <input type="hidden" name="date" id="hidden-date" value="{{ old('date', date('Y-m-d')) }}"
                                required>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="travel_type">Travel Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="travel_type" name="travel_type">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($travelTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('travel_type', $defaultTravelTypeId) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_location">Daily Allowance<span class="text-danger"></span></label>
                            <input type="number" class="form-control" name="daily_allowance" id="daily_allowance"
                                value="{{ $dailyAllowance }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="days_difference">Total Number of Days<span class="text-danger"></span></label>
                            <input type="number"  class="form-control" name="days_difference"
                                id="days_difference" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="from_location">Estimated Travel Expenses<span class="text-danger"></span></label>
                            <input type="number" class="form-control" name="estimated_travel_expenses"
                                id="esitmated_travel_expenses" readonly required>
                        </div>
                    </div>
                </div>
                <!--
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_location">Advance Required<span class="text-danger"></span></label>
                            <input type="number" class="form-control" name="advance_required" id="advance_required">
                        </div>
                    </div>
                </div> -->
                <br>

                <div class="card-body  p-0">
                    <p class="text-info ">*For half-day entries, subtract 0.5 from the total number of days for each row.</p>
                    <div class="table-responsive">
                        <table id="travel_details" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Start Date<span class="text-danger">*</span></th>
                                    <th>End Date<span class="text-danger">*</span></th>
                                    <th>Number of Days<span class="text-danger">*</span></th>
                                    <th>From Location<span class="text-danger">*</span></th>
                                    <th>To Location<span class="text-danger">*</span></th>
                                    <th>Mode of Travel<span class="text-danger">*</span></th>
                                    <th colspan="2">Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-row btn btn-danger btn-sm"><i
                                                class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <input type="date" id="from_date" min="2025-01-01" name="details[0][from_date]"
                                            class="from_date form-control form-control-sm" required>
                                    </td>
                                    <td>
                                        <input type="date" id="to_date" name="details[0][to_date]"
                                            class="to_date form-control form-control-sm" disabled>
                                    </td>

                                    <td>
                                        <input type="number" name="details[0][number_of_days]"
                                            class="form-control form-control-sm days-difference" step="0.5" required>
                                    </td>
                                    <td>
                                        <input type="text" name="details[0][from_location]"
                                            class="form-control form-control-sm" required>
                                    </td>
                                    <td>
                                        <input type="text" name="details[0][to_location]"
                                            class="form-control form-control-sm" required>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm" name="details[0][mode_of_travel]"
                                            required>
                                            <option value="" disabled selected hidden>Select Mode of Travel</option>
                                            @foreach (config('global.travel_modes') as $travelKey => $label)
                                                <option value="{{ $travelKey }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <textarea rows="2" class="form-control" name="details[0][purpose]"></textarea>
                                    </td>
                                </tr>

                                <tr class="notremovefornew">
                                    <td colspan="7"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-row btn btn-sm btn-info" style="font-size: 13px"><i
                                                class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>


            <div class="card-footer">
                <button type="submit" id="submitBtn" class="btn btn-primary"><i class="fa fa-upload"></i>
                    Submit</button>
                <a href="{{ route('apply-travel-authorization.index') }}" class="btn btn-danger"><i
                        class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </form>

    @include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('apply_travel');
            const loader = document.getElementById('loader');
            const submitBtn = document.getElementById('submitBtn');
            const gracePeriod = 3;
            const today = new Date();

            form.addEventListener('submit', function(e) {
                // Show loader
                loader.style.display = 'flex';
            });

            const dailyAllowanceInput = document.getElementById('daily_allowance');
            const estimatedTravelExpensesInput = document.getElementById('esitmated_travel_expenses');
            const daysDifferenceInput = document.getElementById('days_difference');
            const travelType = document.getElementById('travel_type');
            const travelNo = document.getElementById('travel_no');

            let manualEdit = false;
            let rowCount = document.querySelectorAll('#travel_details tbody tr').length - 1;


            daysDifferenceInput.addEventListener('input', function() {
                manualEdit = true;
                calculateEstimatedTravelExpenses(); // Recalculate expenses based on the manual number of days
                manualEdit = false;
            });

            // Function to update the date constraints dynamically
            function updateDateConstraints() {
                const tableRows = document.querySelectorAll('#travel_details tr');
                const today = new Date(); // Get today's date
                const todayISO = today.toISOString().split('T')[0]; // Format today's date in YYYY-MM-DD
                const threeDaysEarlier = new Date(today); // Clone today's date
                threeDaysEarlier.setDate(today.getDate() - 3); // Subtract 3 days
                const threeDaysEarlierISO = threeDaysEarlier.toISOString().split('T')[
                0]; // Format date as YYYY-MM-DD

                tableRows.forEach((row, rowIndex) => {
                    const fromDateField = row.querySelector('.from_date');
                    const toDateField = row.querySelector('.to_date');

                    if (!fromDateField || !toDateField) return;

                    // For the first row
                    if (rowIndex === 1) {
                        // Set the min attribute to today's date
                        fromDateField.setAttribute('min', threeDaysEarlierISO);

                    } else {
                        // For subsequent rows, set the min for from_date based on the previous row's to_date
                        const previousRow = tableRows[rowIndex - 1];
                        const previousToDateField = previousRow.querySelector('.to_date');
                        const previousToDate = previousToDateField?.value;

                        if (previousToDate) {
                            const date = new Date(previousToDate);
                            if (!isNaN(date)) { // Check if the date is valid
                                date.setDate(date.getDate() + 1); // Add one day to the previous to_date
                                const minDate = date.toISOString().split('T')[
                                0]; // Set min for the current from_date
                                fromDateField.setAttribute('min', minDate);
                            }
                        }
                    }

                    // For the current row, set min for to_date based on its from_date
                    const fromDateValue = fromDateField.value;
                    if (fromDateValue) {
                        toDateField.setAttribute('min', fromDateValue);
                        toDateField.disabled = false; // Enable to_date field
                    } else {
                        toDateField.disabled = true; // Disable to_date field if from_date is not set
                        toDateField.value = ''; // Clear to_date if from_date is not set
                    }
                });
            }


            // Function to calculate days difference and estimated travel expenses
            function calculateDaysDifference(row) {
                // Find the input fields within the current row
                const startDateInput = row.querySelector('input[name$="[from_date]"]');
                const endDateInput = row.querySelector('input[name$="[to_date]"]');
                const daysDifferenceInput = row.querySelector('.days-difference');

                if (startDateInput && endDateInput && daysDifferenceInput) {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (startDate && endDate && endDate >= startDate) {
                        const timeDifference = endDate - startDate;
                        const maxDaysDifference = timeDifference / (1000 * 3600 * 24) + 1; // Add 1 to include both start and end days

                        // Validate manual input
                        const manualValue = parseFloat(daysDifferenceInput.value);

                        if (manualEdit && !isNaN(manualValue) && manualValue <= maxDaysDifference) {
                            // Manual value is valid, leave it as-is
                            daysDifferenceInput.value = manualValue;
                        } else {
                            // Either no manual input or manual input exceeds calculated value, set to calculated value
                            daysDifferenceInput.value = maxDaysDifference;
                        }
                    } else {
                        daysDifferenceInput.value = ''; // Clear if invalid dates
                    }
                }
            }


            function addDaysDifferenceEventListeners() {


                // Select all elements with the 'days-difference' class
                const daysDifferenceInputs = document.querySelectorAll('.days-difference');

                // Loop through each element and add an event listener
                daysDifferenceInputs.forEach(function(input) {
                    input.addEventListener('change', function() {
                        const row = input.closest('tr');
                        const startDateInput = row.querySelector('input[name$="[from_date]"]');
                        const endDateInput = row.querySelector('input[name$="[to_date]"]');

                        const startDate = new Date(startDateInput.value);
                        const endDate = new Date(endDateInput.value);

                        if (startDate && endDate && endDate >= startDate) {
                            const timeDifference = endDate - startDate;
                            const maxDaysDifference = timeDifference / (1000 * 3600 * 24) + 1; // Add 1 to include both start and end days

                            const manualValue = parseFloat(input.value);

                            if (manualValue > maxDaysDifference) {
                                showErrorMessage('Number of days cannot be greater than the calculated value. Please check the dates and try again.');
                                input.value = maxDaysDifference; // Revert to calculated value if manual input exceeds it
                            }
                            else{
                                input.value = manualValue;
                            }
                            // manualValue = 0;
                            // Get the manually entered value
                            manualEdit = true;
                            calculateEstimatedTravelExpenses(); // Recalculate expenses based on the manual number of days
                            manualEdit = false;

                        }
                    });
                });
            }

            function totalDays() {
                var total = 0;

                const dayTotal = document.getElementById('days_difference');
                document.querySelectorAll('tr').forEach(function(row) {
                    // Find the input fields within the current row
                    const startDateInput = row.querySelector('input[name$="[from_date]"]');
                    const endDateInput = row.querySelector('input[name$="[to_date]"]');
                    const daysDifferenceInput = row.querySelector('.days-difference');
                    if (daysDifferenceInput && daysDifferenceInput.value && parseFloat(daysDifferenceInput
                            .value) > 0) {
                        total += parseFloat(daysDifferenceInput.value);
                    }
                });
                dayTotal.value = total;
                return total;

            }

            function calculateEstimatedTravelExpenses() {

                const dailyAllowance = parseFloat(dailyAllowanceInput.value) || 0;
                addDaysDifferenceEventListeners();
                const totalDaysValue = totalDays() || 0;
                let estimatedAmount = 0;
                if(totalDaysValue > 15){
                    estimatedAmount = (dailyAllowance * 15) + (totalDaysValue - 15) * (dailyAllowance / 2);
                }else{
                    estimatedAmount = dailyAllowance * totalDaysValue;
                }
                estimatedTravelExpensesInput.value = estimatedAmount > 0 ? estimatedAmount : 0;

            }

            // Event listener for adding rows
            document.querySelector('.add-row').addEventListener('click', function(e) {
                e.preventDefault();

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td class="text-center">
                <a href="#" class="delete-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
            </td>
            <td>
                <input type="date" id="from_date" name="details[${rowCount}][from_date]" class="form-control form-control-sm from_date" min="2025-01-01" required>
            </td>
            <td>
                <input type="date" id="to_date" name="details[${rowCount}][to_date]" class="form-control form-control-sm to_date" disabled>
            </td>
            <td>
                  <input type="number" name="details[${rowCount}][number_of_days]" class="form-control form-control-sm days-difference" step='0.5' required>
            </td>
            <td>
                <input type="text" name="details[${rowCount}][from_location]" class="form-control form-control-sm" required>
            </td>
            <td>
                <input type="text" name="details[${rowCount}][to_location]" class="form-control form-control-sm" required>
            </td>
            <td>
                <select class="form-control form-control-sm" name="details[${rowCount}][mode_of_travel]" required>
                    <option value="" disabled selected hidden>Select Mode of Travel</option>
                    @foreach (config('global.travel_modes') as $travelKey => $label)
                        <option value="{{ $travelKey }}">{{ $label }}</option>
                    @endforeach
                </select>
            </td>
            <td colspan="2">
                <textarea rows="2" class="form-control" name="details[${rowCount}][purpose]"></textarea>
            </td>
        `;

                const referenceRow = document.querySelector('.notremovefornew');
                referenceRow.parentNode.insertBefore(newRow, referenceRow);

                rowCount++;
                updateDateConstraints();
            });

            // Event listener for deleting rows
            document.querySelector('#travel_details').addEventListener('click', function(event) {
                if (event.target && event.target.matches('.delete-row, .delete-row *')) {
                    const thisRow = event.target.closest('tr');
                    if (thisRow) {
                        thisRow.remove();
                        updateDateConstraints();
                        calculateEstimatedTravelExpenses();
                    }
                }
            });

            // Update constraints on page load and when date inputs change
           document.querySelector('#travel_details').addEventListener('change', function(event) {
                    if (event.target.matches('.from_date') || event.target.matches('.to_date')) {
                        const row = event.target.closest('tr');
                        updateDateConstraints();
                        const thisRow = event.target.closest('tr');
                        if (thisRow) {
                            calculateDaysDifference(thisRow); // Pass the specific row to the function
                        }
                        calculateEstimatedTravelExpenses();
                    }
                });

            // Initialize constraints on page load
            updateDateConstraints();
            calculateEstimatedTravelExpenses();
        });
    </script>
@endpush
