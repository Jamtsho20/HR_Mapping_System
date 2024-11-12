@extends('layouts.app')
@section('page-title', 'Create Travel Authorization')
@section('content')

<form action="{{ route('apply-travel-authorization.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
            <!-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger"></span></label>
                        <input type="text" class="form-control" name="travel_no" value="travel_no" id="date" readonly required>
                    </div>
                </div> -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger"></span></label>
                        <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" id="date" readonly required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="from_date"  id="start_date"required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">End Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="to_date" id="end_date" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="days_difference">Number of Days<span class="text-danger">*</span></label>
                        <input type="number" step="any" class="form-control" name="days_difference" id="days_difference" required>
                    </div>
                </div>

                

            </div>

            <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="from_location">From Location<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="from_location"  id="from_location"required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_location">To Location<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="to_location" id="to_location" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mode_of_travel">Mode of Travel <span class="text-danger">*</span></label>
                        <select class="form-control" name="mode_of_travel" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.travel_modes') as $key => $label)
                            <option value="{{ $key }}" {{ old('mode_of_travel') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from_location">Estimated Travel Expenses<span class="text-danger"></span></label>
                        <input type="number" class="form-control" name="estimated_travel_expenses"  id="esitmated_travel_expenses" readonly required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_location">Advance Required<span class="text-danger"></span></label>
                        <input type="number" class="form-control" name="advance_required" id="advance_required">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_location">Daily Allowance<span class="text-danger"></span></label>
                        <input type="number" class="form-control" name="daily_allowance" id="daily_allowance" value={{$dailyAllowance}} readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="purpose">Purpose of trip</label>
                        <textarea rows="2" class="form-control" name="remark" id="remark">{{ old('remark') }}</textarea>
                    </div>
                </div>
            </div>



        </div>


        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Create Travel Authorization</button>
            <a href="{{ route('apply-travel-authorization.index')  }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const daysDifferenceInput = document.getElementById('days_difference');
        const estimatedTravelExpensesInput = document.getElementById('esitmated_travel_expenses');
        const advanceRequiredInput = document.getElementById('advance_required');
        const dailyAllowanceInput = document.getElementById('daily_allowance');

    
        function calculateDaysDifference() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

        
            if (startDate && endDate && endDate >= startDate) {
                const timeDifference = endDate - startDate; // Time difference in milliseconds
                const daysDifference = timeDifference / (1000 * 3600 * 24) +1; // Convert milliseconds to days

                daysDifferenceInput.value = daysDifference;
            } else {
            // If the dates are not valid, clear the input
            daysDifferenceInput.value = '';
            }
        }

        function calculateEstimatedTravelExpenses() {
            const dailyAllowance = dailyAllowanceInput.value
            const days = parseFloat(daysDifferenceInput.value) || 0;
            const advanceAmount = parseFloat(advanceRequiredInput.value) || 0; 
            const estimatedAmount = (days * dailyAllowance) - advanceAmount;
            
    
            estimatedTravelExpensesInput.value = estimatedAmount > 0 ? estimatedAmount : 0;
        }

    
    startDateInput.addEventListener('input', calculateDaysDifference);
    endDateInput.addEventListener('input', function() {
            if (endDateInput.value) {
                calculateDaysDifference();
                calculateEstimatedTravelExpenses();
            }
        });

    // Allow manual changes to the days_difference input
    daysDifferenceInput.addEventListener('input', function() {
        calculateEstimatedTravelExpenses()
        // If the user manually changes the days, don't overwrite their input
    });

    

    // Recalculate estimated travel expenses when the advance amount is changed
    advanceRequiredInput.addEventListener('input', calculateEstimatedTravelExpenses);
});
</script>

@endpush