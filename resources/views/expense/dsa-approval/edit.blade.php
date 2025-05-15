@extends('layouts.app')
@section('page-title', 'DSA Claim and Settlement')
@section('content')


<form action="{{ route('approval.update', $dsa->id) }}" method="POST"
enctype="multipart/form-data" id="apply_dsa">
@csrf
@method('PUT')
<div class="card">
    <div class="card-body">
        <input type="hidden" name="dsa_claim_type_id" id="dsa_claim_type_id"
            value="1">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="employee_name">Employee</label>
                    <input type="text" class="form-control" name="employee"
                        value="{{ $empIdName }}" disabled>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="transaction_no">Claim No <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="transaction_no"
                        id="transaction_no" value="{{ $dsa->transaction_no }}" placeholder="Generating..." readonly>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label for="travel_authorization_id">Travel No(s) <span
                            class="text-danger">*</span></label>
                            <select class="form-control" style="display: none;" id="travel_authorization"
                                name="travel_authorization_id[]" multiple required>
                            @foreach ($dsa->dsaClaimMappings as $travel)
                                <option value="{{ json_encode(['id' => $travel->travel_authorization_id, 'advance_id' => $travel->advance_application_id ?? null]) }}" selected>
                                    {{ $travel->transaction_no }}
                                </option>

                            @endforeach
                        </select>

                        <!-- Display Selected Travel Authorization Numbers Here -->

                        <input type="text" class="form-control" name="selected_travel_authorizations"
                        id="selected_travel_authorizations" readonly>


                        <input type="hidden" name='advance_ids'>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="total_number_of_days">Total Number of Days</label>
                    <input type="number" class="form-control" id="total_number_of_days"
                        name="total_number_of_days"
                        value="{{ old('total_number_of_days', $dsa->total_number_of_days) }}" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="advance_amount">Total Advance Amount </label>
                    <input type="number" class="form-control" id="advance_amount"
                        name="advance_amount"
                        value="{{ old('advance_amount', $dsa->advance_amount) }}" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="grand_total_amount">Total Amount </label>
                    <input type="number" class="form-control" id="grand_total_amount"
                        name="amount" value="{{ old('amount', $dsa->amount) }}" required readonly />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="netpayable">Net Payable Amount</label>
                    <input type="number" class="form-control" id="net_payable_amount"
                        name="net_payable_amount" value="{{ old('net_payable_amount', $dsa->net_payable_amount) }}"
                        required readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="balance_amount">Balance Amount </label>
                    <input type="number" class="form-control" id="balance_amount"
                        name="balance_amount" value="{{ old('balance_amount', $dsa->balance_amount) }}"
                        readonly />
                </div>
            </div>

        </div>
    </div>
    <p class="info-green p-3 pt-0" style=" text-indent: -.01em; padding-left: 1em;">
        <span style="">*</span>
        The "0.5" in the number of days represents either a half-day duration or a half-day allowance.
    </p>
    <div class="tab-pane">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="travelstable"
                        class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                        <thead>
                            <tr role="row">
                                <th class="text-center" rowspan="2">#</th>
                                <th class="text-center" colspan="2">From</th>
                                <th class="text-center" colspan="2">To</th>
                                <th class="text-center" rowspan="2">Number of Days</th>
                                <th class="text-center" rowspan="2">Daily Allowance</th>
                                <th class="text-center" rowspan="2">Travel Allowance</th>
                                <th class="text-center" rowspan="2">Total Amount</th>
                                <th class="text-center" rowspan="2">Remarks</th>
                            </tr>
                            <tr role="row">
                                <th class="text-center">Date</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dsa->dsaClaimMappings as $mapping)


                                <tr class="travel-auth-${travelAuthGroupClass}">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold;">
                                        <span name="dsa_claim_detail[{{$mapping->travel_authorization_id}}][travel_authorization_id]" data-value="{{$mapping->travel_authorization_id}}">
                                            Travel Authorization Number: {{$mapping->transaction_no }}}}
                                        </span>
                                    </td>
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold;">
                                        <span name="dsa_claim_detail[{{$mapping->travel_authorization_id}}][advance_detail_id]" data-value="{{$mapping->advance_application_id ?? ''}}">
                                            Advance Number: {{$mapping->advance_application_id ?? 'N/A'}}, Advance Amount: {{$mapping->advance_amount ?? 'N/A'}}
                                        </span>
                                    </td>
                                    <td style="padding-left:25px;"> @php
                                        $attachments = $mapping->attachment ?? NULL; // Decode JSON to array
                                    
                                        @endphp

                                        @if ($attachments)
                                        
                                        <a href="{{ asset($attachments) }}" class="btn btn-sm btn-primary mb-1"
                                            target="_blank">
                                            <i class="fas fa-file-alt"></i> View Attachment
                                        </a><br>
                                        
                                        @else
                                        <span class="text-danger">No attachment available.</span>
                                        @endif

                                    </td>
                                </tr>
                                @foreach ( $mapping->dsaDetails as $detail )

                                <tr class="data-row travel-auth-{{$mapping->travel_authorization_id}}">
                                    <td>
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                        <input type="hidden" name="dsa_claim_detail[{{$detail->id}}][id]" class="resetKeyForNew" value="{{$detail->id}}">

                                        <input type="hidden" name="dsa_claim_detail[{{$detail->id}}][travel_authorization_id]" class="resetKeyForNew" value="{{$mapping->travel_authorization_id}}">
                                    </td>
                                    <td class="text-center">
                                        <input type="date" value="{{$detail->from_date}}" name="dsa_claim_detail[{{$detail->id}}][from_date]" class="form-control form-control-sm resetKeyForNew" min="{{$detail->from_date}}" max="{{$detail->to_date}}" required />
                                    </td>
                                    <td class="text-center">
                                        <input type="text" value="{{$detail->from_location}}" name="dsa_claim_detail[{{$detail->id}}][from_location]" class="form-control form-control-sm resetKeyForNew" required />
                                    </td>
                                    <td class="text-center">
                                        <input type="date" value="{{$detail->to_date}}" name="dsa_claim_detail[{{$detail->id}}][to_date]" class="form-control form-control-sm resetKeyForNew" min="{{$detail->from_date}}" max="{{$detail->to_date}}" required />
                                    </td>
                                    <td class="text-center">
                                        <input type="text" value="{{$detail->to_location}}" name="dsa_claim_detail[{{$detail->id}}][to_location]" class="form-control form-control-sm resetKeyForNew" required />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" min="0" max="{{$detail->total_days}}" step="0.5" name="dsa_claim_detail[{{$detail->id}}][total_days]" value="{{$detail->total_days}}" class="form-control form-control-sm resetKeyForNew" />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="dsa_claim_detail[{{$detail->id}}][daily_allowance]" value="{{$detail->daily_allowance}}" class="form-control form-control-sm resetKeyForNew notclearfornew" readonly />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="dsa_claim_detail[{{$detail->id}}][travel_allowance]" value="{{$detail->travel_allowance}}" class="form-control form-control-sm resetKeyForNew notclearfornew" />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" value="{{$detail->total_amount}}" name="dsa_claim_detail[{{$detail->id}}][total_amount]" class="form-control form-control-sm resetKeyForNew" readonly />
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="dsa_claim_detail[{{$detail->id}}][remark]" value="{{$detail->remark}}" class="form-control form-control-sm resetKeyForNew notclearfornew" readonly />
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="travel-auth-{{ $mapping->travel_authorization_id }} last-row">
                                    <td colspan="1" class="text-center" style="color: black;">
                                    </td>
                                    <td colspan="1" class="text-center" style="color: black; font-weight: bold;">
                                        <span>
                                            Total Days:
                                        </span>
                                        <span class="days-span">
                                             {{$mapping->number_of_days}}
                                        </span>
                                        <input type="hidden" id="total_days" name="total_days[{{$mapping->travel_authorization_id}}]" value="{{$mapping->number_of_days}}">
                                    </td>
                                    <td colspan="5" class="text-center" style="color: black; ">

                                    </td>
                                    <td colspan="2" class="text-center" style="color: black;  font-weight: bold;">
                                        <span>
                                            Travel Authorization Amount:
                                        </span>
                                    </td>

                                    <td colspan="1" class="text-center" style="color: black;  font-weight: bold;">
                                        <input type="number" id="ta_amount" style="color: black;  font-weight: bold;" class="form-control" name="ta_amount[{{$mapping->travel_authorization_id}}]" value="{{$mapping->ta_amount}}"readonly>
                                        <input type="hidden" id="advance_amount" name="advance_amount[{{$mapping->travel_authorization_id}}]" value="{{$mapping->advance_amount}}">
                                    </td>

                                    </td>
                                </tr>
                                <tr class=" travel-auth-{{$mapping->travel_authorization_id}} notremovefornew">
                                    <td colspan="9"></td>
                                    <td class="text-right">
                                       <a href="#" class=" add-row-btn btn btn-sm btn-info "  style="font-size: 12px">
                                            <i class="fa fa-plus"></i> Add New Row
                                        </a>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        @include('layouts.includes.buttons', [
            'buttonName' => 'SUBMIT',
            'cancelUrl' => url('/approval/applications'),
            'cancelName' => 'CANCEL',
        ])

    </div>

</div>
</form>

    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    window.DAILY_ALLOWANCE = {{$da}};
$(document).ready(function() {

    $('#travel_authorization').select2({
    placeholder: "Select travel numbers",
    allowClear: true
}).hide();
let selectedTexts = [];


function addNewRow(button) {
    // Find the closest travel authorization row
    const travelAuthRow = $(button).closest('tr');
const travelAuthClassMatch = travelAuthRow.attr('class')?.match(/travel-auth-(\d+)/);
updateAllRowDateConstraints(travelAuthClassMatch[1]);
if (!travelAuthClassMatch) {
    showErrorMessage("Invalid travel authorization row.");
    return;
}

const travelAuthGroupClass = travelAuthClassMatch[1];

let lastToDateInput = $(`.travel-auth-${travelAuthGroupClass} input[name^='dsa_claim_detail'][name$='[to_date]']`).last();
let maxDate = lastToDateInput.attr("max") || lastToDateInput.val();
let minDate = lastToDateInput.val();

if (minDate) {
    let minDateObj = new Date(minDate);
    minDateObj.setDate(minDateObj.getDate() + 1); // Add 1 day
    minDate = minDateObj.toISOString().split("T")[0]; // Format back to YYYY-MM-DD
} else {
    showErrorMessage("Please complete filling out the current row before adding a new one.");
    return;
}


    // Generate a new unique row ID (You may need a better way to generate unique IDs)
    const newRowId = `${Date.now()}${Math.floor(Math.random() * 100)}`;


    // Define the structure of the new row
    const newRow = `
        <tr class="data-row travel-auth-${travelAuthGroupClass}">
            <td>
                <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                <input type="hidden" name="dsa_claim_detail[${newRowId}][id]" class="resetKeyForNew" value="${newRowId}">

                <input type="hidden" name="dsa_claim_detail[${newRowId}][travel_authorization_id]" class="resetKeyForNew" value="${travelAuthGroupClass}">
            </td>
            <td class="text-center">
                <input type="date" name="dsa_claim_detail[${newRowId}][from_date]" min=${minDate} max=${maxDate} class="form-control form-control-sm resetKeyForNew" required />
            </td>
            <td class="text-center">
                <input type="text" name="dsa_claim_detail[${newRowId}][from_location]" class="form-control form-control-sm resetKeyForNew" required />
            </td>
            <td class="text-center">
                <input type="date" name="dsa_claim_detail[${newRowId}][to_date]" max=${maxDate} class="form-control form-control-sm resetKeyForNew" required disabled />
            </td>
            <td class="text-center">
                <input type="text" name="dsa_claim_detail[${newRowId}][to_location]" class="form-control form-control-sm resetKeyForNew" required />
            </td>
            <td class="text-center">
                <input type="number" min="0" step="0.5" name="dsa_claim_detail[${newRowId}][total_days]" class="form-control form-control-sm resetKeyForNew" />
            </td>
            <td class="text-center">
                <input type="number" name="dsa_claim_detail[${newRowId}][daily_allowance]" value="${DAILY_ALLOWANCE}" class="form-control form-control-sm resetKeyForNew notclearfornew" readonly />
            </td>
            <td class="text-center">
                <input type="number" min="0" name="dsa_claim_detail[${newRowId}][travel_allowance]" class="form-control form-control-sm resetKeyForNew" />
            </td>
            <td class="text-center">
                <input type="number" name="dsa_claim_detail[${newRowId}][total_amount]" class="form-control form-control-sm resetKeyForNew" readonly>
            </td>
            <td class="text-center">
                <textarea name="dsa_claim_detail[${newRowId}][remark]" class="form-control form-control-sm resetKeyForNew" rows="2"></textarea>
            </td>
        </tr>`;

    // Insert the new row immediately after the calling travel authorization row
    $(`.travel-auth-${travelAuthGroupClass}.last-row`).before(newRow);


};

function updateDateConstraints(travelAuthGroupClass) {
    let lastToDateInput = $(`.travel-auth-${travelAuthGroupClass} input[name^='dsa_claim_detail'][name$='[to_date]']`).last();
    let prevToDateInput = lastToDateInput.closest('tr').prev('tr').find("input[name^='dsa_claim_detail'][name$='[to_date]']");
// Check if the previous input was found
let maxDate, minDate; // Declare outside the if block
if (prevToDateInput.length) {
    maxDate = prevToDateInput.attr("max") || prevToDateInput.val();
    minDate = prevToDateInput.val();

} else {
    console.error("Previous to_date input not found");
}
    if (minDate) {
        let minDateObj = new Date(minDate);
        minDateObj.setDate(minDateObj.getDate() + 1); // Add 1 day
        minDate = minDateObj.toISOString().split("T")[0]; // Format back to YYYY-MM-DD
    } else {
        showErrorMessage("Please complete filling out the current row before adding a new one.");
        return;
    }

}

function updateNextRowMinDate(changedRow) {
    // Get the changed row's to_date value
    let currentToDate = changedRow.find("input[name^='dsa_claim_detail'][name$='[to_date]']").val();
    if (!currentToDate) return; // nothing to update if current to_date is empty

    // Calculate the new minimum date for the next row
    let nextMinDate = new Date(currentToDate);
    nextMinDate.setDate(nextMinDate.getDate() + 1);
    nextMinDate = nextMinDate.toISOString().split("T")[0];

    // Find the next row (if it exists) within the same travel auth group
    let nextRow = changedRow.next("tr");
    if (nextRow.length) {
        let fromDateField = nextRow.find("input[name^='dsa_claim_detail'][name$='[from_date]']");
        fromDateField.attr("min", nextMinDate);
    }
}

function updateAllRowDateConstraints(travelAuthGroupClass) {
    // Select all rows within the travel auth group
    const rows = $(`tr.travel-auth-${travelAuthGroupClass}`);
    rows.each(function(index, row) {
        let $row = $(row);
        // For rows beyond the first, update the from_date min based on the previous row's to_date
        if (index > 0) {
            let $prevRow = $row.prev("tr");
            let prevToDate = $prevRow.find("input[name^='dsa_claim_detail'][name$='[to_date]']").val();
            if (prevToDate) {
                let newMin = new Date(prevToDate);
                newMin.setDate(newMin.getDate() + 1);
                newMin = newMin.toISOString().split("T")[0];
                $row.find("input[name^='dsa_claim_detail'][name$='[from_date]']").attr("min", newMin);
            }
        }
        // Also ensure each row's to_date min is its own from_date value
        let fromDateValue = $row.find("input[name^='dsa_claim_detail'][name$='[from_date]']").val();
        if (fromDateValue) {
            $row.find("input[name^='dsa_claim_detail'][name$='[to_date]']").removeAttr("disabled");
            $row.find("input[name^='dsa_claim_detail'][name$='[to_date]']").attr("min", fromDateValue);
        }
    });
}

// When a to_date field changes, update the next row's from_date min
$(document).on("change", "input[name^='dsa_claim_detail'][name$='[to_date]']", function () {
    const changedRow = $(this).closest('tr');
    updateNextRowMinDate(changedRow);
});

$(document).on("change", "input[name^='dsa_claim_detail'][name$='[from_date]']", function () {
    const changedRow = $(this).closest('tr');
    let changedRowFromDate = changedRow.find("input[name^='dsa_claim_detail'][name$='[from_date]']").val();
    changedRow.find("input[name^='dsa_claim_detail'][name$='[to_date]']").attr("min", changedRowFromDate);
    changedRow.find("input[name^='dsa_claim_detail'][name$='[to_date]']").removeAttr("disabled");
});

// When a row is deleted, update the entire group's constraints
$(document).on("click", ".delete-table-row", function () {
    const row = $(this).closest('tr');
    // Find the travel auth group from the closest parent that has a class matching travel-auth-*
    const travelAuthClassMatch = row.closest("[class*='travel-auth-']").attr('class')?.match(/travel-auth-(\d+)/);
    row.remove(); // Remove the row

    if (travelAuthClassMatch) {
        const travelAuthGroupClass = travelAuthClassMatch[1];
        updateAllRowDateConstraints(travelAuthGroupClass);

    }
});

document.addEventListener("click", function (event) {
    if (event.target.closest(".add-row-btn")) {
        event.preventDefault();
        addNewRow(event.target);
    }
});

    $('#travel_authorization option:selected').each(function() {
        selectedTexts.push($(this).text().trim());
    });

    $('#selected_travel_authorizations').val(selectedTexts.join(', '));
    $('.select2-container').hide();
    // getTravelAuthorizationDetailsMultiple();
    const form = document.getElementById('apply_expense');
    const dsaForm = document.getElementById('apply_dsa');
    const loader = document.getElementById('loader');
    const submitBtn = document.getElementById('submitBtn');

    dsaForm.addEventListener('submit', function(e) {
        // Show loader
        loader.style.display = 'flex';
    });

    function calculateGrandTotal() {
        let totalDays = 0;

        let grandTotal = 0;

        // Loop through each row and sum up the total amounts
        $("input[name^='ta_amount']").each(function() {
            grandTotal += parseFloat($(this).val() || 0, 10);
        });



// Update the grand total input field
$('#grand_total_amount').val(grandTotal);

        // Update the grand total input field
        $('#grand_total_amount').val(grandTotal);
    }
    const dateFields = document.querySelectorAll('input[name^="fuel_claim_details"][name$="[date]"]');

    const dsaFields = document.querySelectorAll('input[name^="dsa_claim_detail"][name$="[from_date]"]');

    // // Iterate through each field and set the min attribute
    dsaFields.forEach(field => {
        field.setAttribute('min', '2025-01-01');
    });


    function calculateNetPayable() {
        // Retrieve input values
        let totalAmount = parseFloat($('#grand_total_amount').val()) || 0;
        let advanceAmount = parseFloat($('#advance_amount').val()) || 0;

        // Calculate net payable
        let netPayable = totalAmount - advanceAmount;

        // Update net payable amount field
        $('#net_payable_amount').val(netPayable.toFixed(2));
    }

    calculateNetPayable();

    function updateTravelAuthRow(travelAuthorizationId, newTaAmount, newAdvanceAmount, newDays) {
// Find the last row with the specific travel authorization ID
const row = $(`tr.travel-auth-${travelAuthorizationId}.last-row`).last();  // Selecting the last row with the class travel-auth-75


// Find the ta_amount input field
const taAmountInput = row.find(`input[name='ta_amount[${travelAuthorizationId}]']`);

// Check if the input is found and update it
if (taAmountInput.length > 0) {


// If the input is readonly, remove the readonly attribute, update the value, then add readonly back
if (taAmountInput.is('[readonly]')) {
    taAmountInput.removeAttr('readonly');  // Remove readonly
}

taAmountInput.val(newTaAmount);  // Update the value

// Reapply readonly if you want to keep it
taAmountInput.attr('readonly', true);  // Set it back to readonly
} else {

}

const daysSpan = row.find('span.days-span');
daysSpan.text(newDays);



// Find and update the advance_amount input field
const advanceAmountInput = row.find(`input[name='advance_amount[${travelAuthorizationId}]']`);
if (advanceAmountInput.length > 0) {

advanceAmountInput.val(newAdvanceAmount);  // Update the advance amount
} else {

}
}



    // Event delegation to handle dynamically added rows

    $(document).on('change input', 'input[name*="dsa_claim_detail"][name*="total_amount"]', function() {
            const row = $(this).closest('tr'); // Get the row containing the changed input
            const parentTable = row.closest('table'); // Get the parent table (or use tbody if needed)

            // Extract the travelAuthId from the row's class
            const travelAuthClass = row.attr('class').split(' ').find(cls => cls.startsWith('travel-auth-'));
            if (!travelAuthClass) {

                return;
            }


            // Sum only rows within the same table/tbody that match this travelAuthId
            let taAmount = 0;
            let days = 0;
            const travelAuthId = row.attr('class').match(/travel-auth-(\d+)/)?.[1];
            parentTable.find(`tr.travel-auth-${travelAuthId}`).each(function() {
                const taInput = $(this).find('input[name*="dsa_claim_detail"][name*="total_amount"]');
                const dInput = $(this).find('input[name*="dsa_claim_detail"][name*="total_days"]');
                if (taInput.length > 0 && dInput.length > 0) {
                    taAmount += parseFloat(taInput.val()) || 0;
                    days += parseFloat(dInput.val()) || 0;
                }
            });




            // Find advance amount from the last row of this travelAuthId group within the same table
            const lastRow = parentTable.find(`tr.travel-auth-${travelAuthId}.last-row`);
            const advanceAmount = parseFloat(lastRow.find('input[name*="advance_amount"]').val()) || 0;



            // Update only the last row with the new calculated values
            updateTravelAuthRow(travelAuthId, taAmount, advanceAmount, days);
        });




    $(document).on(
        "input change",
        "input[name*='[daily_allowance]'],  input[name*='[travel_allowance]'], input[name*='[total_days]'], input[name*='[from_date]'], input[name*='[to_date]']",
        function() {
            // Find the closest row for the current input
            const $row = $(this).closest("tr");


            // const travelAuthId = $row.attr('class').split(' ').find(cls => cls.startsWith('travel-auth-'));

            // Ensure all values are fetched from the same row
            const dailyAllowance = parseFloat($row.find("input[name*='[daily_allowance]']").val() ||
                DAILY_ALLOWANCE, 10);
            const travelAllowance = parseFloat($row.find("input[name*='[travel_allowance]']").val() ||
                0, 10);

            // Get the current total days (allow manual edit)
            let totalDays = parseFloat($row.find("input[name*='[total_days]']").val() || 0, 10);

            // Recalculate total days if from_date and to_date are present
            const fromDate = $row.find("input[name*='[from_date]']").val();
            const toDate = $row.find("input[name*='[to_date]']").val();

            if (fromDate && toDate) {
                const from = new Date(fromDate);
                const to = new Date(toDate);

                // Check if to_date is less than from_date
                if (to < from) {
                    alert("The 'To Date' must be equal to or later than the 'From Date'.");
                    $row.find("input[name*='[to_date]']").val(""); // Clear invalid date
                    return; // Exit the function early
                }

                // Recalculate total days only when dates are modified
                if ($(this).is("input[name*='[from_date]'], input[name*='[to_date]']")) {
                    totalDays = Math.ceil((to - from) / (1000 * 60 * 60 * 24)) + 1;
                    $row.find("input[name*='[total_days]']").val(totalDays); // Update total days input
                }
            }

            // Calculate the total amount for the current row
            let totalAmount  = (dailyAllowance * totalDays) + travelAllowance;

            // grandTotal += totalAmount;
            // const totalAmount = (dailyAllowance * totalDays) + travelAllowance;

            // Update the total amount for the current row only
            $row.find("input[name*='[total_amount]']").val(totalAmount);
            $('input[name*="dsa_claim_detail"][name*="total_amount"]').trigger('change');

            calculateTotalNumberOfDays()
            calculateGrandTotal();
            calculateNetPayable();
        }
    );

    $(document).on("click", ".delete-table-row", function() {
        $('input[name*="dsa_claim_detail"][name*="total_amount"]').trigger('change');
        calculateTotalNumberOfDays()
        calculateGrandTotal();
       calculateNetPayable();
    })

    $(document).on("click", ".add-table-row", function() {
        calculateGrandTotal();
        calculateNetPayable();
    });



$(document).on('input', 'input[name*="dsa_claim_detail"][name*="total_days"]', function() {
const maxVal = parseFloat($(this).attr('max')); // Get max value
const currentVal = parseFloat($(this).val());   // Get current value

if (currentVal > maxVal) {
showErrorMessage(`Value cannot be more than the number of days`);
$(this).val(maxVal); // Reset to max value
}
});

    function getDsaAvanceByTravelAuth(id) {
        const travelAuthorizationId = id

        if (travelAuthorizationId !== '') {
            $.ajax({
                url: `/getdsaadvancebytravelauth/${travelAuthorizationId}`,
                dataType: 'JSON',
                type: 'GET',
                success: function(data) {
                    return data
                },
                error: function(error) {
                    alert("Error fetching data", error);
                }
            });
        }
    }

    function getDsaAvanceDetails() {
        const dsaAdvanceId = $("#dsa_advance_tour").val();

        if (dsaAdvanceId !== '') {
            $.ajax({
                url: `/getdsaadvancedetails/${dsaAdvanceId}`,
                dataType: 'JSON',
                type: 'GET',
                success: function(data) {
                    $('#advance_amount').val(data.amount ?? 0);
                    calculateNetPayable();
                },
                error: function(error) {
                    alert("Error fetching data", error);
                }
            });
        }
    }


    function calculateTotalAmountForRow(row) {
        var fuelinLtr = parseFloat(row.find('input[name^="fuel_claim_details"][name$="[quantity]"]')
            .val()) || 0;
        var rate = parseFloat(row.find('input[name^="fuel_claim_details"][name$="[rate]"]').val()) || 0;

        var amount = (fuelinLtr * rate).toFixed(2);

        row.find('input[name^="fuel_claim_details"][name$="[amount]"]').val(amount);
        calculateFuelClaimTotal();
    }

    $('#mas_vehicle_id').change(function() {
        var vehicleId = $(this).val();

        if (vehicleId) {
            getVehicleDetails(vehicleId);
        } else {
            alert('Please select a valid vehicle.');
        }
    });

    // Trigger the function when the dropdown value changes
    $("#travel_authorization").on("change", function() {
const selectedValues = $(this).val(); // Get selected values

if (!selectedValues || selectedValues.length === 0) {
$('#grand_total_amount').val(0);
$('#total_number_of_days').val(0);
$('#advance_amount').val(0);
const tbody = $('#travelstable tbody');
tbody.empty();
tbody.append(`<tr><td colspan="13" class="text-center text-danger">No Travel Authorization Application Selected</td></tr>`);
$('#grand_total_amount').val(0);  // Reset grand total
} else {
//getTravelAuthorizationDetailsMultiple(); // Fetch data if selections exist
}
});
    $(document).on("change", "#travel_authorization", getDsaAvanceByTravelAuth);
    $(document).on("change", "#dsa_advance_tour", getDsaAvanceDetails);
    $(document).on("input change", "#grand_total_amount, #advance_amount, input[name*='[total_amount]']",
        calculateNetPayable);
    $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[rate]"]', function() {
        var row = $(this).closest('tr');
        calculateTotalAmountForRow(row);
    });

    $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[quantity]"]', function() {
        var row = $(this).closest('tr');
        calculateTotalAmountForRow(row);
    });

    function calculateTotalNumberOfDays() {
        var totalNumberOfDays = 0;
        $('#travelstable tbody tr').each(function() {
            totalNumberOfDays += parseFloat($(this).find('input[name*="[total_days]"]').val() || 0);
        });

        $('#total_number_of_days').val(totalNumberOfDays);
    }
});


</script>
    @endpush
