@extends('layouts.app')
@section('page-title', 'Create Travel Authorization')
@section('content')

<form action="{{ route('apply-travel-authorization.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="advance_no">Travel Authorizaiton No <span class="text-danger"></span></label>
                        <input type="text" class="form-control" name="travel_authorization_no" value="{{ $travelAuthorizationNumber }}" id="travel_no" value="{{ old('advance_no') }}" placeholder="Generating..." readonly>
                    </div>
                </div>
      
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger"></span></label>
                        <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" id="date" readonly required>
                    </div>
                </div>  

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="travel_type">Travel Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="travel_type" name="travel_type">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach($travelTypes as $type)
                             <option value="{{ $type->id }}" 
                                {{ (old('travel_type', $defaultTravelTypeId) == $type->id) ? 'selected' : '' }}>
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
                        <label for="days_difference">Number of Days<span class="text-danger"></span></label>
                        <input type="number" step="any" class="form-control" name="days_difference" id="days_difference" required>
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
                        <input type="number" class="form-control" name="advance_required" id="advance_required">
                    </div>
                </div>

            
            </div>

            <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_location">Daily Allowance<span class="text-danger"></span></label>
                            <input type="number" class="form-control" name="daily_allowance" id="daily_allowance" value="{{$dailyAllowance}}" readonly>
                        </div>
                    </div>
            </div>

            <div class="card-body  p-0">
                <div class="table-responsive">
                    <table id="travel_details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>Mode of Travel</th>
                                <th colspan="2">Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <a href="#" class="delete-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                </td>
                                <td>
                                    <input type="date" id="from_date" name="details[0][from_date]" class="from_date form-control form-control-sm" required>
                                </td>
                                <td>
                                    <input type="date" id="to_date" name="details[0][to_date]" class="to_date form-control form-control-sm" disabled>
                                </td>
                                <td>
                                    <input type="text" name="details[0][from_location]" class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <input type="text" name="details[0][to_location]" class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm" name="details[0][mode_of_travel]" required>
                                        <option value="" disabled selected hidden>Select Mode of Travel</option>
                                        @foreach(config('global.travel_modes') as $travelKey => $label)
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
                                    <a href="#" class="add-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
    const dailyAllowanceInput = document.getElementById('daily_allowance');
    const estimatedTravelExpensesInput = document.getElementById('esitmated_travel_expenses');
    const advanceRequiredInput = document.getElementById('advance_required');
    const daysDifferenceInput = document.getElementById('days_difference');
    const travelType = document.getElementById('travel_type');
    const travelNo = document.getElementById('travel_no');
    
    
    let manualEdit = false;


    function calculateDaysDifference() {
        let totalDays = 0;

    // Loop through each row and calculate the days difference
        document.querySelectorAll('input[name^="details["][name$="][from_date]"]').forEach(function(startDateInput, index) {
            const row = startDateInput.closest('tr'); 
            const endDateInput = row.querySelector('input[name$="[to_date]"]');

        // Ensure both start and end dates exist and are enabled
            if (startDateInput && endDateInput && !endDateInput.disabled) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

            if (startDate && endDate && endDate >= startDate) {
                const timeDifference = endDate - startDate;
                const daysDifference = timeDifference / (1000 * 3600 * 24) + 1;
                totalDays += daysDifference;
            }
        }
    });

            if (!manualEdit) {
                daysDifferenceInput.value = totalDays;
            }

            return totalDays;
        }

    
    function calculateEstimatedTravelExpenses() {
        const dailyAllowance = parseFloat(dailyAllowanceInput.value) || 0;
        const advanceAmount = parseFloat(advanceRequiredInput.value) || 0;
        const totalDays = manualEdit ? parseFloat(daysDifferenceInput.value) || 0 : calculateDaysDifference();
        const estimatedAmount = (totalDays * dailyAllowance) - advanceAmount;
        estimatedTravelExpensesInput.value = estimatedAmount > 0 ? estimatedAmount : 0;
    }
document.querySelector('#travel_details').addEventListener('click', function(event) {
if (event.target && event.target.matches('.delete-row')) {
    var thisRow = event.target.closest('tr');
    thisRow.remove();
    calculateEstimatedTravelExpenses(); 
}
});


document.querySelector('#travel_type').addEventListener('change', function(event) {
    if (travel_type.value){
        fetch(`/gettravelbyid/${travel_type.value}`)
        .then(response => response.json()) // Parse the response as JSON
        .then(data => {
            if (data.travel_no) {
                document.querySelector('#travel_no').value = data.travel_no; // Access travel_no
            } else {
                console.error('travel_no not found in response');
            }
        })
        .catch(error => console.error('Error fetching travel number:', error));
        
    }
})
travel_type.dispatchEvent(new Event('change'));
document.querySelector('#travel_details').addEventListener('change', function(event) {
    if (event.target && event.target.matches('.from_date')) {
        var fromDate = event.target.value;
        var toDateField = event.target.closest('tr').querySelector('.to_date');
        
        if (fromDate) {
            toDateField.setAttribute('min', fromDate);
            toDateField.disabled = false;
        } else {
            toDateField.disabled = true;
            toDateField.value = ''; 
        }
        calculateEstimatedTravelExpenses();
        
    }

    if (event.target.matches('input[name^="details["][name$="][from_date]"], input[name^="details["][name$="][to_date]"]')) {
        calculateEstimatedTravelExpenses(); 
    }
});
    // Recalculate days difference and estimated travel expenses when any date input changes
document.querySelector('#travel_details').addEventListener('input', function(event) {
    if (event.target.matches('input[name^="details["][name$="][from_date]"], input[name^="details["][name$="][to_date]"]')) {
        calculateEstimatedTravelExpenses();
    }
});

    // Recalculate estimated travel expenses when the advance amount is changed
    advanceRequiredInput.addEventListener('input', calculateEstimatedTravelExpenses);

    // Recalculate estimated travel expenses when the number of days is manually changed
    daysDifferenceInput.addEventListener('input', function() {
        manualEdit = true; 
        calculateEstimatedTravelExpenses(); // Recalculate expenses based on the manual number of days
    });

    daysDifferenceInput.addEventListener('blur', function() {
        manualEdit = false; 
    });

    let rowCount = document.querySelectorAll('#travel_details tbody tr').length-1;

document.querySelector('.add-row').addEventListener('click', function(e) {
    e.preventDefault();

    const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="text-center">
                <a href="#" class="delete-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
            </td>
            <td>
                <input type="date" id="from_date"  name="details[${rowCount}][from_date]" class="form-control form-control-sm from_date" required>
            </td>
            <td>
                <input type="date" id="to_date" name="details[${rowCount}][to_date]" class="form-control form-control-sm to_date " disabled>
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
                    @foreach(config('global.travel_modes') as $travelKey => $label)
                        <option value="{{ $travelKey }}">{{ $label }}</option>
                    @endforeach
                </select>
            </td>
            <td colspan="2">
                <textarea rows="2" class="form-control" name="details[${rowCount}][purpose]"></textarea>
            </td>
        `;

        const referenceRow = document.querySelector('.notremovefornew');

        // Insert the new row before the reference row
        referenceRow.parentNode.insertBefore(newRow, referenceRow);

        // Increment row count for next row
        rowCount++;
    }); 

    calculateEstimatedTravelExpenses();

});
</script>

@endpush