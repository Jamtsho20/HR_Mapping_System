@extends('layouts.app')
@section('page-title', 'DSA Claim and Settlement')
@section('content')

    <form action="{{ route('dsa-claim-settlement.update', $dsaClaimApplication->id) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee_name">Employee</label>
                            <input type="text" class="form-control" name="employee" value="{{ $empIdName }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dsa_claim_no">Claim No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="dsa_claim_no"
                                value="{{ old('dsa_claim_no', $dsaClaimApplication->dsa_claim_no) }}" id="dsa_claim_no"
                                value="{{ old('dsa_claim_no', $dsaClaimApplication->dsa_claim_no) }}"
                                placeholder="Generating..." readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="travel_authorization_id">Travel No <span class="text-danger">*</span></label>
                            <select class="form-control" id="travel_authorization" name="travel_authorization_id" required>
                                <option value="" selected disabled>Select your option</option>
                                @foreach ($travels as $travel)
                                    <option value="{{ $travel->id }}"
                                        {{ $dsaClaimApplication->travel_authorization_id == $travel->id ? 'selected' : '' }}>
                                        {{ $travel->travel_authorization_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dsa_advance_tour">Advance No</label>
                            <select class="form-control" id="dsa_advance_tour" name="advance_no">
                                <option value="" disabled>Select your option</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="advance_amount">Advance Amount </label>
                            <input type="number" class="form-control" id="advance_amount" name="advance_amount"
                                value="{{ $dsaClaimApplication->dsaadvance }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grand_total_amount">Total Amount </label>
                            <input type="number" class="form-control" id="grand_total_amount" name="total_amount"
                                value="{{ $dsaClaimApplication->amount }}" required readonly />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="netpayable">Net Payable Amount</label>
                            <input type="number" class="form-control" id="net_payable_amount" name="net_payable_amount"
                                value="{{ old('net_payable_amount') }}" required readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="balance_amount">Balance Amount </label>
                            <input type="number" class="form-control" id="balance_amount" name="balance_amount"
                                value="{{ old('balance_amount', 0) }}" readonly />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="file">Attachment (s)</label>
                            <input type="file" id="attachment" class="form-control" name="file">
                        </div>
                        <!-- Display area for uploaded file -->
                        <div id="uploaded-file" style="margin-top: 10px;">
                            <!-- Placeholder for uploaded file -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="travelstable"
                                class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" rowspan="2">#</th>
                                        <th colspan="2">From</th>
                                        <th colspan="2">To</th>
                                        <th rowspan="2">Total Days</th>
                                        <th rowspan="2">Daily Allowance</th>
                                        <th rowspan="2">Travel Allowance</th>
                                        <th rowspan="2">Total Amount</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr role="row">
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dsaClaimApplication->dsaClaimDetails as $dsaClaimDetail)
                                        <tr class="data-row">
                                            <td class="text-center">
                                                <a href="#" class="delete-table-row btn btn-danger btn-sm">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][id]"
                                                    value="{{ $dsaClaimDetail->id }}"
                                                    class="form-control form-control-sm resetKeyForNew" />

                                                <input type="date"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][from_date]"
                                                    value="{{ $dsaClaimDetail->from_date }}"
                                                    class="form-control form-control-sm resetKeyForNew" required />
                                            </td>
                                            <td class="text-center">
                                                <input type="text"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][from_location]"
                                                    value="{{ $dsaClaimDetail->from_location }}"
                                                    class="form-control form-control-sm resetKeyForNew" required />
                                            </td>
                                            <td class="text-center">
                                                <input type="date"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][to_date]"
                                                    value="{{ $dsaClaimDetail->to_date }}"
                                                    class="form-control form-control-sm resetKeyForNew" required />
                                            </td>
                                            <td class="text-center">
                                                <input type="text"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][to_location]"
                                                    value="{{ $dsaClaimDetail->to_location }}"
                                                    class="form-control form-control-sm resetKeyForNew" required />
                                            </td>
                                            <td class="text-center">
                                                <input type="number"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][total_days]"
                                                    value="{{ $dsaClaimDetail->total_days }}"
                                                    class="form-control form-control-sm resetKeyForNew mycal" />
                                            </td>
                                            <td class="text-center">
                                                <input type="number" min="0"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][daily_allowance]"
                                                    value="{{ $dailyAllowance->da_in_country }}"
                                                    class="form-control form-control-sm resetKeyForNew notclearfornew"
                                                    readonly />
                                            </td>
                                            <td class="text-center">
                                                <input type="number" min="0"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][travel_allowance]"
                                                    value="{{ $dsaClaimDetail->travel_allowance }}"
                                                    class="form-control form-control-sm resetKeyForNew" />
                                            </td>
                                            <td class="text-center">
                                                <input type="number"
                                                    name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][total_amount]"
                                                    value="{{ $dsaClaimDetail->total_amount }}"
                                                    class="form-control form-control-sm resetKeyForNew" readonly />
                                            </td>
                                            <td class="text-center">
                                                <textarea name="dsa_claim_detail[AAAAA{{ $dsaClaimDetail->id }}][remark]"
                                                    class="form-control form-control-sm resetKeyForNew" rows="2">{{ $dsaClaimDetail->remark }}</textarea>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No travel details found.</td>
                                        </tr>
                                    @endforelse
                                    <tr class="notremovefornew">
                                        <td colspan="9"></td>
                                        <td class="text-right">
                                            <a href="#" class="add-table-row btn btn-sm btn-info">
                                                <i class="fa fa-plus"></i> Add New Row
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('layouts.includes.buttons', [
                    'buttonName' => 'Submit',
                    'cancelUrl' => url('/expense/apply-expense'),
                    'cancelName' => 'CANCEL',
                ])

            </div>

        </div>
    </form>

    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            getDsaAvanceDetails();
            window.DAILY_ALLOWANCE = {{ $dailyAllowance->da_in_country }};

            getDsaAvanceByTravelAuth();

            function calculateGrandTotal() {
                let grandTotal = 0;

                // Loop through each row and sum up the total amounts
                $("input[name*='[total_amount]']").each(function() {
                    const rowTotal = parseFloat($(this).val() || 0, 10);
                    grandTotal += rowTotal;
                });

                // Update the grand total input field
                $('#grand_total_amount').val(grandTotal);
            }

            function calculateNetPayable() {
                // Retrieve input values
                let totalAmount = parseFloat($('#grand_total_amount').val()) || 0;
                let advanceAmount = parseFloat($('#advance_amount').val()) || 0;

                // Calculate net payable
                let netPayable = totalAmount - advanceAmount;

                // Update net payable amount field
                $('#net_payable_amount').val(netPayable.toFixed(2));
            }

            // Event delegation to handle dynamically added rows
            $(document).on(
                "input change",
                "input[name*='[daily_allowance]'], input[name*='[travel_allowance]'], input[name*='[total_days]'], input[name*='[from_date]'], input[name*='[to_date]']",
                function() {
                    // Find the closest row for the current input
                    const $row = $(this).closest("tr");

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
                    const totalAmount = (dailyAllowance * totalDays) + travelAllowance;

                    // Update the total amount for the current row only
                    $row.find("input[name*='[total_amount]']").val(totalAmount);

                    calculateGrandTotal();
                    calculateNetPayable();
                }
            );

            $(document).on("click", ".add-table-row", function() {
                calculateGrandTotal();
                calculateNetPayable();
            });

            function getTravelAuthorizationDetails() {
                const travelAuthorizationId = $("#travel_authorization").val();

                if (travelAuthorizationId !== '') {
                    $.ajax({
                        url: `/gettravelauthorizationbytravelauthorizationid/${travelAuthorizationId}`,
                        dataType: 'JSON',
                        type: 'GET',
                        success: function(data) {
                            const tbody = $("#travelstable tbody");
                            tbody.empty(); // Clear the existing rows

                            if (data.travel_authorization_details && data.travel_authorization_details
                                .details.length > 0) {
                                let grandTotal = 0;

                                // Loop through the travel authorization details
                                data.travel_authorization_details.details.forEach((detail, index) => {
                                    const totalAmount = DAILY_ALLOWANCE * detail.no_of_days;
                                    grandTotal += totalAmount;

                                    const row = `
                        <tr class="data-row">
                            <td>
                                <a href=""
                                        class="delete-table-row btn btn-danger btn-sm"><i
                                            class="fa fa-times"></i></a>
                            </td>
                            <td class="text-center">
                                <input type="date"
                                    value="${detail.from_date}"
                                    name="dsa_claim_detail[${detail.id}][from_date]"
                                    class="form-control form-control-sm resetKeyForNew"  required />
                            </td>
                            <td class="text-center">
                                <input type="text"
                                    value="${detail.from_location}"
                                    name="dsa_claim_detail[${detail.id}][from_location]"
                                    class="form-control form-control-sm resetKeyForNew"  required />
                            </td>
                            <td class="text-center">
                                <input type="date"
                                    name="dsa_claim_detail[${detail.id}][to_date]"
                                    value="${detail.to_date}"
                                    class="form-control form-control-sm resetKeyForNew"  required />
                            </td>
                            <td class="text-center">
                                <input type="text"
                                    name="dsa_claim_detail[${detail.id}][to_location]"
                                    value="${detail.to_location}"
                                    class="form-control form-control-sm resetKeyForNew"  required />
                            </td>
                            <td class="text-center">
                                <input type="number"
                                    min="0"
                                    name="dsa_claim_detail[${detail.id}][total_days]"
                                    value="${detail.no_of_days}"
                                    class="form-control form-control-sm resetKeyForNew" />
                            </td>
                            <td class="text-center">
                                <input type="number"
                                    name="dsa_claim_detail[${detail.id}][daily_allowance]"
                                    value="${DAILY_ALLOWANCE}"
                                    class="form-control form-control-sm resetKeyForNew notclearfornew"
                                    readonly />
                            </td>
                            <td class="text-center">
                                <input type="number"
                                    min="0"
                                    name="dsa_claim_detail[${detail.id}][travel_allowance]"
                                    class="form-control form-control-sm resetKeyForNew" />
                            </td>
                            <td class="text-center">
                                <input type="number"
                                    value="${totalAmount}"
                                    name="dsa_claim_detail[${detail.id}][total_amount]"
                                    class="form-control form-control-sm resetKeyForNew" readonly>
                            </td>
                            <td class="text-center">
                                <textarea name="dsa_claim_detail[${detail.id}][remark]" class="form-control form-control-sm resetKeyForNew" rows="2"></textarea>
                            </td>
                        </tr>`;

                                    tbody.append(row); // Append the row to the table body
                                });

                                // Add the row for adding a new entry (Add New Row)
                                const btnRow = `
                    <tr class="notremovefornew">
                        <td colspan="9"></td>
                        <td class="text-right">
                            <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px">
                                <i class="fa fa-plus"></i> Add New Row
                            </a>
                        </td>
                    </tr>`;
                                tbody.append(btnRow);

                                const lastDataRow = tbody.find("tr.data-row").last();
                                const lastDataRowDailyAllowanceField = lastDataRow.find(
                                    "input[name*='[daily_allowance]']");
                                lastDataRowDailyAllowanceField.val(
                                    DAILY_ALLOWANCE);

                                // Update the grand total
                                $('#grand_total_amount').val(grandTotal);
                            } else {
                                tbody.append(
                                    `<tr><td colspan="9" class="text-center text-danger">No details found</td></tr>`
                                );
                            }

                            $("#advance_amount").val('');
                        },
                        error: function(error) {
                            alert(`Error fetching data: ${error.responseText || error.statusText}`);
                            $("#travelstable tbody").empty().append(`
                    <tr>
                        <td colspan="9" class="text-center text-danger">Error fetching details</td>
                    </tr>`);
                        }
                    });
                }
            }

            function getDsaAvanceByTravelAuth() {
                const travelAuthorizationId = $("#travel_authorization").val();

                if (travelAuthorizationId !== '') {
                    $.ajax({
                        url: `/getdsaadvancebytravelauth/${travelAuthorizationId}`,
                        dataType: 'JSON',
                        type: 'GET',
                        success: function(data) {
                            const selectedAdvanceId =
                                "{{ $dsaClaimApplication->advance_application_id ?? '' }}"; // Preselected value
                            $("#dsa_advance_tour").empty();

                            // Check if data contains any options
                            if (data.length > 0) {
                                // Append a placeholder or default option
                                $("#dsa_advance_tour").append(
                                    '<option value="">Select DSA Advance</option>');

                                // Loop through the data and create options
                                data.forEach(item => {
                                    const selected = item.id == selectedAdvanceId ? 'selected' :
                                        '';
                                    const option =
                                        `<option value="${item.id}" ${selected}>${item.advance_no}</option>`;
                                    $("#dsa_advance_tour").append(option);

                                    getDsaAvanceDetails();
                                });
                            } else {
                                // Append a message if no data is available
                                $("#dsa_advance_tour").append(
                                    '<option value="">No DSA Advances available</option>');
                            }
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
                            $('#advance_amount').val(data.amount ?? 0.00);
                            calculateNetPayable();
                        },
                        error: function(error) {
                            alert("Error fetching data", error);
                        }
                    });
                } else {
                    $('#advance_amount').val(0.00);
                    calculateNetPayable();
                }
            }

            // Trigger the function when the dropdown value changes
            $(document).on("change", "#travel_authorization", getTravelAuthorizationDetails);
            $(document).on("change", "#travel_authorization", getDsaAvanceByTravelAuth);
            $(document).on("change", "#travel_authorization", calculateNetPayable);
            $(document).on("change", "#dsa_advance_tour", getDsaAvanceDetails);
            $(document).on("input change", "#grand_total_amount", calculateNetPayable);
            $(document).on("input change", "#advance_amount", calculateNetPayable);
            $(document).on("input change", "input[name*='[total_amount]']", calculateNetPayable);
        });
    </script>
@endpush
