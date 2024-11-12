@extends('layouts.app')
@section('page-title', 'Edit Advance')
@section('content')

<form action="{{ route('apply-travel-authorization.update', $travelAuthorization->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Edit Travel Authorization</h3>
                <a href="{{ route('apply-travel-authorization.index') }}" class="close custom-close-btn" id="btn_addClose" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remark">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="from_date" value="{{ $travelAuthorization->from_date}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remark">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="to_date" value="{{ $travelAuthorization->to_date }}">
                        </div>
                    </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="number_of_days">Number of Days</label>
                                <input type="text" class="form-control" id="number_of_days" name="number_of_days" value="{{ $travelAuthorization->number_of_days }}">
                            </div>
                        </div>
                   
                
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from_location">From Location<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="from_location"  id="from_location" value="{{$travelAuthorization->from_location}}"required>
                                </div>
                        </div>

                        <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to_location">To Location<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="to_location" id="to_location" value="{{$travelAuthorization->to_location}}" required>
                                </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mode_of_travel">Mode of Travel <span class="text-danger">*</span></label>
                                <select class="form-control" name="mode_of_travel" required>
                                <option value="{{ $travelAuthorization->mode_of_travel }}" disabled selected hidden>{{config('global.travel_modes')[$travelAuthorization->mode_of_travel] ?? 'Unknown' }}</option>
                                @foreach(config('global.travel_modes') as $key => $label)
                                    <option value="{{ $key }}" {{ old('mode_of_travel') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="from_location">Estimated Travel Expenses<span class="text-danger"></span></label>
                            <input type="number" class="form-control" name="estimated_travel_expenses"  id="esitmated_travel_expenses" readonly required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_location">Advance Required<span class="text-danger"></span></label>
                            <input type="number" class="form-control" name="advance_amount" id="advance_required" value="{{ $travelAuthorization->advance_amount }}">
                        </div>
                    </div>                   
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="purpose">Purpose of trip</label>
                            <textarea rows="2" class="form-control" name="purpose" id="purpose">{{$travelAuthorization->purpose}}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_location">Daily Allowance<span class="text-danger"></span></label>
                        <input type="hidden" class="form-control" name="daily_allowance" id="daily_allowance" value="{{$travelAuthorization->daily_allowance}}" readonly>
                    </div>
                </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Update </button>
                    <a href="{{ route('apply-travel-authorization.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL </a>
                </div>
            </div>
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
        const daysDifferenceInput = document.getElementById('number_of_days');
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
            console.log(estimatedAmount)
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
    calculateDaysDifference();
    calculateEstimatedTravelExpenses();
});
</script>

@endpush