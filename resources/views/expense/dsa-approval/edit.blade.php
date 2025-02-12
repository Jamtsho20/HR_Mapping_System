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
                    <label for="dsa_claim_no">Claim No <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dsa_claim_no"
                        id="dsa_claim_no" value="{{ $dsa->dsa_claim_no }}" placeholder="Generating..." readonly>
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
                                    {{ $travel->travel_authorization_no }}
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
                        value="{{ old('total_number_of_days', 0) }}" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="advance_amount">Total Advance Amount </label>
                    <input type="number" class="form-control" id="advance_amount"
                        name="advance_amount"
                        readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="grand_total_amount">Total Amount </label>
                    <input type="number" class="form-control" id="grand_total_amount"
                        name="amount" value="{{ old('amount', 0) }}" required readonly />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="netpayable">Net Payable Amount</label>
                    <input type="number" class="form-control" id="net_payable_amount"
                        name="net_payable_amount" value="{{ old('net_payable_amount') }}"
                        required readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="balance_amount">Balance Amount </label>
                    <input type="number" class="form-control" id="balance_amount"
                        name="balance_amount" value="{{ old('balance_amount', 0) }}"
                        readonly />
                </div>
            </div>

        </div>
    </div>
    <p class="text-green p-3 pt-0" style=" text-indent: -.01em; padding-left: 1em;">
        <span style="">*</span>
        For each travel authorization application, the total number of days,
        the formula used for calculating the amount, and the final amount will be
        displayed at the end of each application.
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

                            <tr><td colspan="13" class="text-center text-danger">No Travel Authorization Application Selected</td></tr>
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
    window.DAILY_ALLOWANCE = {{$da}}
$(document).ready(function() {

    $('#travel_authorization').select2({
    placeholder: "Select travel numbers",
    allowClear: true
}).hide();
let selectedTexts = [];

    $('#travel_authorization option:selected').each(function() {
        selectedTexts.push($(this).text().trim());
    });

    $('#selected_travel_authorizations').val(selectedTexts.join(', '));
    $('.select2-container').hide();
    getTravelAuthorizationDetailsMultiple();
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

$formula='';
        if(newDays <= 15){

                    formula= DAILY_ALLOWANCE + " * " + newDays + "day(s)";
                }else{

                    formula= "(" + DAILY_ALLOWANCE + " * 15day(s))"+"+"+"(" + DAILY_ALLOWANCE/2 + " * " + (newDays-15) + "day(s)) ="  ;
                };

const formulaSpan = row.find('span.formula-span');
formulaSpan.text(formula);
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
            let totalAmount = 0;
            if(totalDays > 15){
                totalAmount = (dailyAllowance * 15) + (totalDays - 15) * (dailyAllowance / 2) + travelAllowance;
            }else{
                totalAmount = (dailyAllowance * totalDays) + travelAllowance;
            }
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

    function getTravelAuthorizationDetailsMultiple() {
// Get selected travel authorization IDs (could be from a multi-select or input)
const travelAuthorizationIds = $("#travel_authorization").val(); // Assuming this is a multi-select element

const extractedIds = travelAuthorizationIds.map(item => JSON.parse(item).id);

if (travelAuthorizationIds && travelAuthorizationIds.length > 0) {
$.ajax({
    url: `/gettravelauthorizationbytravelauthorizationidsMultiple`, // Adjust API endpoint to handle multiple IDs
    type: 'GET',
    data: { ids: extractedIds }, // Send the list of IDs in the query params
    dataType: 'JSON',

    success: function(data) {


const tbody = $("#travelstable tbody");
tbody.empty();

$('input[name="advance_ids"]').val(JSON.stringify(data.travel_authorization_details.advance_ids));

if (data && data.travel_authorization_details.travel_authorizations.length > 0) {
let grandTotal = 0;
let totalAdvanceAmount = 0;
let totalDays = 0;

let attachments = data.travel_authorization_details.attachments;
// Loop through the returned travel authorizations
data.travel_authorization_details.travel_authorizations.forEach((travel_authorizations, authIndex) => {

    const travelAuthGroupClass = `${travel_authorizations.travelAuthorization.id}`;
    let attachmentLinks = '';

    let matchingAttachments = attachments
        .filter(att => att.travel_authorization_id === travel_authorizations.travelAuthorization.id)
        .flatMap(att => JSON.parse(att.attachment)) // Parse JSON & flatten
        .filter((file, index, self) => self.indexOf(file) === index); // Flatten in case of multiple records



    if (matchingAttachments.length > 0) {
        matchingAttachments.forEach((file) => {
                attachmentLinks += `
                <a href="${file}" class="btn btn-sm btn-primary mb-1" target="_blank">
                    <i class="fas fa-file-alt"></i> View Attachment
                    </a><br>
                    <input type="hidden" name="files[${travel_authorizations.travelAuthorization.id}]" value="${file}">
                    `;
                });


                }else {
                attachmentLinks += `<span class="text-danger">No attachment available.</span>`;
            }

                tbody.append(`
                    <tr class="travel-auth-${travelAuthGroupClass}">
                        <td colspan="4" class="text-center" style="color: black; font-weight: bold;">
                            <span name="dsa_claim_detail[${travel_authorizations.travelAuthorization.id}][travel_authorization_id]" data-value="${travel_authorizations.travelAuthorization.id}: ${travel_authorizations.advance_details ? travel_authorizations.advance_details.id : ''}">
                                Travel Authorization Number: ${travel_authorizations.travelAuthorization.travel_authorization_no}
                            </span>
                        </td>
                        <td colspan="4" class="text-center" style="color: black; font-weight: bold;">
                            <span name="dsa_claim_detail[${travel_authorizations.details.id}][advance_detail_id]" data-value="${travel_authorizations.advance_detail ? travel_authorizations.advance_detail.id : ''}">
                                ${travel_authorizations.advance_details && travel_authorizations.advance_details.advance_no
                                    ? `Advance Number: ${travel_authorizations.advance_details.advance_no}, Advance Amount: ${travel_authorizations.advance_details.amount || 'N/A'}`
                                    : 'Advance Number: N/A, Advance Amount: N/A'}
                            </span>
                        </td>
                        <td colspan="4" class="text-center" style="color: black;">
                            ${attachmentLinks}
                        </td>
                    </tr>
                `);
        totalDays += parseFloat(travel_authorizations.no_of_days) || 0;
        if(travel_authorizations.advance_details){
            totalAdvanceAmount +=  parseFloat(travel_authorizations.advance_details.amount) || 0;
            }
        // Loop through the travel authorization details for each authorization
        if (travel_authorizations.details && travel_authorizations.details.length > 0) {
            let taAmount = 0;
            let days = 0;
            travel_authorizations.details.forEach((detail, index) => {

                const totalAmount = DAILY_ALLOWANCE * detail.no_of_days;

                days+=parseFloat(detail.no_of_days);

            const row = `
                <tr class="data-row travel-auth-${travelAuthGroupClass}">
                    <td>
                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        <input type="hidden" name="dsa_claim_detail[${detail.id}][id]" class="resetKeyForNew" value="${detail.id}">

                        <input type="hidden" name="dsa_claim_detail[${detail.id}][travel_authorization_id]" class="resetKeyForNew" value="${travel_authorizations.travelAuthorization.id}">
                    </td>
                    <td class="text-center">
                        <input type="date" value="${detail.from_date}" name="dsa_claim_detail[${detail.id}][from_date]" class="form-control form-control-sm resetKeyForNew" min="${detail.from_date}" max="${detail.to_date}" required />
                    </td>
                    <td class="text-center">
                        <input type="text" value="${detail.from_location}" name="dsa_claim_detail[${detail.id}][from_location]" class="form-control form-control-sm resetKeyForNew" required />
                    </td>
                    <td class="text-center">
                        <input type="date" value="${detail.to_date}" name="dsa_claim_detail[${detail.id}][to_date]" class="form-control form-control-sm resetKeyForNew" min="${detail.from_date}" max="${detail.to_date}" required />
                    </td>
                    <td class="text-center">
                        <input type="text" value="${detail.to_location}" name="dsa_claim_detail[${detail.id}][to_location]" class="form-control form-control-sm resetKeyForNew" required />
                    </td>
                    <td class="text-center">
                        <input type="number" min="0" max="${detail.no_of_days}" step="0.5" name="dsa_claim_detail[${detail.id}][total_days]" value="${detail.no_of_days}" class="form-control form-control-sm resetKeyForNew" />
                    </td>
                    <td class="text-center">
                        <input type="number" name="dsa_claim_detail[${detail.id}][daily_allowance]" value="${DAILY_ALLOWANCE}" class="form-control form-control-sm resetKeyForNew notclearfornew" readonly />
                    </td>
                    <td class="text-center">
                        <input type="number" min="0" name="dsa_claim_detail[${detail.id}][travel_allowance]" class="form-control form-control-sm resetKeyForNew" />
                    </td>
                    <td class="text-center">
                        <input type="number" value="${totalAmount}" name="dsa_claim_detail[${detail.id}][total_amount]" class="form-control form-control-sm resetKeyForNew" readonly>
                    </td>
                    <td class="text-center">
                        <textarea name="dsa_claim_detail[${detail.id}][remark]" class="form-control form-control-sm resetKeyForNew" rows="2"></textarea>
                    </td>
                </tr>`;

            tbody.append(row); // Append the row to the table body
        });
        $formula='';
        if(days <= 15){
                    taAmount+=DAILY_ALLOWANCE * days;
                    formula= DAILY_ALLOWANCE + " * " + days + "day(s)";
                }else{
                    taAmount+=(DAILY_ALLOWANCE/2)*(days-15) + DAILY_ALLOWANCE * 15;
                    formula= "(" + DAILY_ALLOWANCE + " * 15day(s))"+"+"+"(" + DAILY_ALLOWANCE/2 + " * " + (days-15) + "day(s)) ="  ;
                };
                grandTotal+=taAmount;

        tbody.append(`
            <tr class="travel-auth-${travelAuthGroupClass} last-row">
                <td colspan="1" class="text-center" style="color: black;">
                </td>
                <td colspan="1" class="text-center" style="color: black; font-weight: bold;">
                    <span>
                        Total Days:
                    </span>
                    <span class="days-span">
                         ${days}
                    </span>
                    <input type="hidden" id="total_days" name="total_days[${travel_authorizations.travelAuthorization.id}]" value="${days}">
                </td>
                <td colspan="5" class="text-center" style="color: black; ">
                    <span style="font-weight: bold;">Formula:</span>
                    <span class="formula-span">
                         ${formula}
                    </span>
                </td>
                <td colspan="1" class="text-center" style="color: black;  font-weight: bold;">
                    <span>
                        Travel Authorization Amount:
                    </span>
                </td>

                <td colspan="1" class="text-center" style="color: black;  font-weight: bold;">
                    <input type="number" id="ta_amount" style="color: black;  font-weight: bold;" class="form-control" name="ta_amount[${travel_authorizations.travelAuthorization.id}]" value="${taAmount}"readonly>
                    <input type="hidden" id="advance_amount" name="advance_amount[${travel_authorizations.travelAuthorization.id}]" value="${travel_authorizations?.advance_details?.amount ?? ''}">
                </td>
                <td colspan="1" class="text-center" style="color: black;">

                </td>
            </tr>
        `);


// Update the grand total
$('#grand_total_amount').val(grandTotal);

const totalNumDays = document.getElementById('total_number_of_days');
totalNumDays.value = totalDays;
$('#advance_amount').val(totalAdvanceAmount ?? 0);
calculateTotalNumberOfDays()
calculateGrandTotal();
calculateNetPayable();

}
})}}
,
    error: function(error) {
        alert(`Error fetching data: ${error.responseText || error.statusText}`);
        $("#travelstable tbody").empty().append(`
            <tr><td colspan="9" class="text-center text-danger">Error fetching details</td></tr>
        `);
    }

});
}
}

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
getTravelAuthorizationDetailsMultiple(); // Fetch data if selections exist
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
