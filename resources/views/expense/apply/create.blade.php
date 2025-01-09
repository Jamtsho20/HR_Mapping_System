@extends('layouts.app')
@section('page-title', 'Apply Expense')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($headers as $header)
                    @php
                        $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($header->name));
                        $id = $header->id;
                    @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $itemType == $id ? 'active' : '' }}" id="tab-{{ $sanitizedName }}"
                            data-bs-toggle="pill" data-bs-target="#content-{{ $sanitizedName }}" type="button"
                            role="tab" aria-controls="content-{{ $sanitizedName }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $header->name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content" id="pills-tabContent">
            @foreach ($headers as $header)
                @php
                    $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($header->name));
                    $id = $header->id;
                @endphp
                <div class="tab-pane fade {{ $itemType == $id ? 'show active' : '' }}" id="content-{{ $sanitizedName }}"
                    role="tabpanel" aria-labelledby="tab-{{ $sanitizedName }}" data-item-type="{{ $id }}">
                    @if ($id == 2)
                        <form action="{{ route('apply-expense.store') }}" method="post" enctype="multipart/form-data"
                            id="apply_expense">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="expense_no">Expense No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="expense_no" id="expense_no" placeholder="Generating..." readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="expense_type">Expense Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="expense_type" name="expense_type" required>
                                                <option value="" disabled selected hidden>Select your option
                                                </option>
                                                @foreach ($expenses as $expense)
                                                    <option value="{{ $expense->id }}"
                                                        {{ old('expense_type') == $expense->id ? 'selected' : '' }}>
                                                        {{ $expense->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date">Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                value="{{ old('date', now()->format('Y-m-d')) }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="display: none;" id="vehicle">
                                        <label for="mas_vehicle_id">Vehicle No <span class="text-danger">*</span></label>
                                        <select class="form-control" id="mas_vehicle_id" name="mas_vehicle_id">
                                            <option value="" disabled selected hidden>Select your option
                                            </option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}"
                                                    {{ old('mas_vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                    {{ $vehicle->vehicle_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="amount">Amount <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                value="{{ old('amount') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="description" name="description"
                                                value="{{ old('description') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="file-uploader">
                                                <label for="file">Upload File <span id="attachment_required"
                                                        class="text-danger" style="display:none;">*</span></label>
                                                <div class="file-upload-box">
                                                    <div class="box-title">
                                                        <!-- <span class="file-instruction">Drag files here or</span> -->
                                                        <span class="file-browse-button">Upload Files</span>
                                                    </div>
                                                    <input class="file-browse-input" type="file" multiple hidden
                                                        name="attachments[]" id="attachment" class="form-control"
                                                        accept="image/*,.pdf,.doc,.docx">

                                                </div>
                                                <ul class="file-list">

                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- sd -->

                                    <div class="tab-pane" id="vehiclefuelclaimsection">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table id="vehiclefuelclaimtable"
                                                        class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>#</th>
                                                                <th>Date</th>
                                                                <th>Initial (KM) Reading</th>
                                                                <th>Final (KM) Reading</th>
                                                                <th>Qty.(Ltrs.)</th>
                                                                <th>Mileage</th>
                                                                <th>Rate</th>
                                                                <th>Amount (NU.)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <a href=""
                                                                        class="delete-table-row btn btn-danger btn-sm"><i
                                                                            class="fa fa-times"></i></a>
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="date"
                                                                        name="fuel_claim_details[AAAAA][date]"
                                                                        class="form-control form-control-sm resetKeyForNew"
                                                                        required />
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="number"
                                                                        name="fuel_claim_details[AAAAA][initial_reading]"
                                                                        class="form-control form-control-sm resetKeyForNew initial-reading"
                                                                        readonly required />
                                                                </td>

                                                                <td class="text-center">
                                                                    <input type="number"
                                                                        name="fuel_claim_details[AAAAA][final_reading]"
                                                                        class="form-control form-control-sm resetKeyForNew final-reading"
                                                                        required />
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="text"
                                                                        name="fuel_claim_details[AAAAA][quantity]"
                                                                        class="form-control form-control-sm resetKeyForNew"
                                                                        required />
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="number"
                                                                        name="fuel_claim_details[AAAAA][mileage]"
                                                                        class="form-control form-control-sm resetKeyForNew notclearfornew"
                                                                        readonly required />
                                                                </td>

                                                                <td class="text-center">
                                                                    <input type="number" min="0" step="0.01"
                                                                        name="fuel_claim_details[AAAAA][rate]"
                                                                        class="form-control form-control-sm resetKeyForNew"
                                                                        required />
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="number" min="0" step="0.01"
                                                                        name="fuel_claim_details[AAAAA][amount]"
                                                                        class="form-control form-control-sm resetKeyForNew" />
                                                                </td>
                                                            </tr>

                                                            <tr class="notremovefornew">
                                                                <td colspan="7"></td>
                                                                <td class="text-right">
                                                                    <a href="#"
                                                                        class="add-table-row btn btn-sm btn-info"
                                                                        style="font-size: 12px">
                                                                        <i class="fa fa-plus"></i> Add New Row</a>
                                                                </td>
                                                            </tr>

                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--Conveyance Form-->
                                @include('expense.apply.types.conveyance')
                            </div>
                            <div class="card-footer">
                                @include('layouts.includes.buttons', [
                                    'buttonName' => 'SUBMIT',
                                    'cancelUrl' => url('expense/apply-expense'),
                                    'cancelName' => 'CANCEL',
                                ])

                            </div>
                        </form>
                    @elseif ($id == 3)
                        <form action="{{ route('dsa-claim-settlement.store') }}" method="post"
                            enctype="multipart/form-data" id="apply_dsa_claim">
                            @csrf
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
                                                <input type="text" class="form-control" name="dsa_claim_no" id="dsa_claim_no" placeholder="Generating..." readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="travel_authorization_id">Travel No <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" id="travel_autorization"
                                                    name="travel_authorization_id" required>
                                                    <option value="" selected disabled>Select your option</option>
                                                    @foreach ($travels as $travel)
                                                        <option value="{{ $travel->id }}">
                                                            {{ $travel->travel_authorization_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dsa_advance_tour">Advance No</label>
                                                <select class="form-control" id="dsa_advance_tour" name="advance_no">
                                                    <option value="" selected disabled>Select your option</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="advance_amount">Advance Amount </label>
                                                <input type="number" class="form-control" id="advance_amount"
                                                    name="advance_amount" value="{{ old('advance_amount', 0) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="grand_total_amount">Total Amount </label>
                                                <input type="number" class="form-control" id="grand_total_amount"
                                                    name="amount" value="{{ old('amount') }}" required readonly />
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="file">Attachment (s)</label>
                                                <input type="file" id="attachment" class="form-control"
                                                    name="file">
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
                                        <p class="text-danger small px-3 py-2">* The total number of days may differ from
                                            the selected dates, as 0.5 is subtracted for each half day.</p>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table id="travelstable"
                                                    class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="text-center" rowspan="2">#</th>
                                                            <th class="text-center" colspan="2">From</th>
                                                            <th class="text-center" colspan="2">To</th>
                                                            <th class="text-center" rowspan="2">Total Days</th>
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
                                                        <tr class="data-row">
                                                            <td class="text-center">
                                                                <a href="#"
                                                                    class="delete-table-row btn btn-danger btn-sm">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="date"
                                                                    name="dsa_claim_detail[AAAAA][from_date]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    required />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text"
                                                                    name="dsa_claim_detail[AAAAA][from_location]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    required />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="date"
                                                                    name="dsa_claim_detail[AAAAA][to_date]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    required />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text"
                                                                    name="dsa_claim_detail[AAAAA][to_location]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    required />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="dsa_claim_detail[AAAAA][total_days]"
                                                                    class="form-control form-control-sm resetKeyForNew mycal" />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" min="0"
                                                                    name="dsa_claim_detail[AAAAA][daily_allowance]"
                                                                    value="{{ $dailyAllowance->da_in_country }}"
                                                                    class="form-control form-control-sm resetKeyForNew notclearfornew"
                                                                    readonly />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" min="0"
                                                                    name="dsa_claim_detail[AAAAA][travel_allowance]"
                                                                    class="form-control form-control-sm resetKeyForNew" />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="dsa_claim_detail[AAAAA][total_amount]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    readonly />
                                                            </td>
                                                            <td class="text-center">
                                                                <textarea name="dsa_claim_detail[AAAAA][remark]" class="form-control form-control-sm resetKeyForNew" rows="2"></textarea>
                                                            </td>
                                                        </tr>
                                                        <tr class="notremovefornew">
                                                            <td colspan="9"></td>
                                                            <td class="text-right">
                                                                <a href="#"
                                                                    class="add-table-row btn btn-sm btn-info">
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
                                        'buttonName' => 'SUBMIT',
                                        'cancelUrl' => url('/expense/dsa-claim-settlement'),
                                        'cancelName' => 'CANCEL',
                                    ])

                                </div>

                            </div>
                        </form>
                    @elseif ($id == 4)
                        <form action="{{ route('transfer-claim.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="employeeid">Employee ID </label>
                                                <input type="text" class="form-control" name="employee"
                                                    value="{{ $empIdName }}" disabled />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Designation</label>
                                                <input type="text" class="form-control" name="designation"
                                                    value=""
                                                    placeholder="{{ isset(auth()->user()->empJob->designation->name) ? auth()->user()->empJob->designation->name : 'NA' }}"
                                                    disabled>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Department</label>
                                                <input type="text" class="form-control" name="department"
                                                    value=""
                                                    placeholder="{{ isset(auth()->user()->empJob->department->name) ? auth()->user()->empJob->department->name : 'NA' }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label for="">Basic Pay</label>
                                                <input type="text" class="form-control" name="basicpay"
                                                    value=""
                                                    placeholder="{{ isset(auth()->user()->empJob->basic_pay) ? auth()->user()->empJob->basic_pay : 'NA' }}"
                                                    disabled>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transfer_claim_no">Claim No <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="transfer_claim_no"

                                                    id="transfer_claim_no"

                                                    placeholder="Generating..." readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transferclaim">Transfer Claim <span
                                                        class="text-danger">*</span></label>
                                                <select name="transfer_claim" id="transferclaim"
                                                    class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>Select an option</option>s
                                                    @foreach ($transferClaimTypes as $transferClaimType)
                                                        <option value="{{ $transferClaimType->id }}"
                                                            {{ old('transfer_claim') == $transferClaimType->id ? 'selected' : '' }}>
                                                            {{ $transferClaimType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Current Location <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="current_location"
                                                    value="{{ old('current_location') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">New Location <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="new_location"
                                                    value="{{ old('new_location') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="distanceField" style="display: none;">
                                            <div class="form-group">
                                                <label for="distance">Distance (KM) <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="distance_travelled"
                                                    value="{{ old('distance_travelled') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Amount Claimed <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="amount"
                                                    id="amount_claimed" value="{{ old('amount') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Attachment</label>
                                                <span class="text-danger" id="attachment-required"
                                                    style="display: none;">*</span> <!-- Initially hidden -->
                                                <input type="file" class="form-control" name="attachment"
                                                    id="transfer-attachment">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                @include('layouts.includes.buttons', [
                                    'buttonName' => 'SUBMIT',
                                    'cancelUrl' => url('expense/apply-expense'),
                                    'cancelName' => 'CANCEL',
                                ])

                            </div>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            window.DAILY_ALLOWANCE = {{ $dailyAllowance->da_in_country }};

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
            const dateFields = document.querySelectorAll('input[name^="fuel_claim_details"][name$="[date]"]');

            // Iterate through each field and set the min attribute
            // dateFields.forEach(field => {
            //     field.setAttribute('min', '2025-01-01');
            // });

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

            const initialType = $('#expense_type').val();
            toggleFuelClaimSection(initialType === '5');

            function toggleFuelClaimSection(shouldShow) {
                const fuelClaimSection = $('#vehiclefuelclaimsection');
                if (shouldShow) {
                    fuelClaimSection.show();
                    fuelClaimSection.find('input, select, textarea').prop('disabled', false).attr('required', true);
                } else {
                    fuelClaimSection.hide();
                    fuelClaimSection.find('input, select, textarea').prop('disabled', true).attr('required', false);
                }
            }

            $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                const targetContentId = $(e.target).data('bs-target').replace('#content-', '');
                const targetContent = $(`#content-${targetContentId}`);
                const itemType = targetContent.data('item-type');

                const url = new URL(window.location.href);
                url.searchParams.set('item_type', itemType);
                history.pushState(null, '', url);
            });

            var selectedExpenseType = $('#expense_type');
            var formSections = $('.dynamic-form');

            selectedExpenseType.on('change', function() {
                var selectedType = selectedExpenseType.val();

                // Hide all dynamic form sections and disable their inputs
                formSections.each(function() {
                    $(this).hide();
                    disableFormFields($(this));
                });

                if (selectedType === '1') {
                    const section = $('#conveyance_expense_form');
                    section.show();
                    enableFormFields(section);
                } else {
                    const section = $('#vehicle');
                    const fuelClaimSection = $('#vehiclefuelclaimsection');

                    // Show/hide sections based on selectedType
                    const showVehicle = selectedType === '5' || selectedType ===
                        '6'; // fuel || parking fee.
                    const showFuelClaim = selectedType === '5';

                    section.toggle(showVehicle);
                    fuelClaimSection.toggle(showFuelClaim);

                    if (showVehicle) {
                        enableFormFields(section);
                        toggleFuelClaimSection(showFuelClaim);

                        if (selectedType === '5') {
                            $('#amount').prop('readonly', true);
                        } else {
                            $('#amount').prop('readonly', false);
                        }
                    } else {
                        // Reset form sections when no matching type
                        formSections.each(function() {
                            $(this).hide();
                            disableFormFields($(this));
                        });

                        toggleFuelClaimSection(false);
                        section.find('select').attr("required", false);
                        disableFormFields(section);
                    }
                }
            });

            // Initially hide all dynamic form sections
            formSections.each(function() {
                $(this).hide();
                disableFormFields($(this));
            });

            // Show the correct form section based on the old input value
            var oldSelectedExpenseType = '{{ old('expense_type') }}';
            if (oldSelectedExpenseType) {
                selectedExpenseType.val(oldSelectedExpenseType);
                selectedExpenseType.trigger('change'); // Trigger the change event to show the relevant section
            }

            // Function to enable form fields in the visible section
            function enableFormFields(form) {
                form.find('input, select, textarea').prop('disabled', false); // Enable the input fields
            }


            // Function to disable form fields in hidden sections
            function disableFormFields(form) {
                form.find('input, select, textarea').prop('disabled', true); // Disable the input fields
            }

            var distanceField = $('#distanceField');
            var amountField = $('#amount_claimed');
            var attachmentField = $('#transfer-attachment');
            var attachmentAsterisk = $('#attachment-required'); // Asterisk span for attachment

            function handleTransferClaimChange() {
                var selectedValue = $('#transferclaim').val(); // Get the selected value of the dropdown

                if (selectedValue === '2') {
                    distanceField.show(); // Show the distance field
                    amountField.removeAttr('max'); // Remove the max attribute
                    attachmentAsterisk.show(); // Show the asterisk
                    attachmentField.prop('required', true); // Make attachment field required

                    // Remove any input restriction for the amount field
                    amountField.off('input');
                } else {
                    distanceField.hide(); // Hide the distance field
                    amountField.attr('max', 20000); // Set max value of 20000
                    attachmentAsterisk.hide(); // Hide the asterisk
                    attachmentField.prop('required', false); // Make attachment field not required

                    // Restrict the input value of the amount field
                    amountField.off('input').on('input', function() {
                        var amount = parseInt($(this).val(), 10);
                        if (amount > 20000) {
                            alert('Amount cannot exceed 20000.'); // Display an alert
                            $(this).val(20000); // Set the value to 20000
                        }
                    });
                }
            }

            // Initial call to handle the current value of the dropdown
            handleTransferClaimChange();

            // Change event listener for the dropdown
            $('#transferclaim').on('change', handleTransferClaimChange);




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
                const travelAuthorizationId = $("#travel_autorization").val();

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
                const travelAuthorizationId = $("#travel_autorization").val();

                if (travelAuthorizationId !== '') {
                    $.ajax({
                        url: `/getdsaadvancebytravelauth/${travelAuthorizationId}`,
                        dataType: 'JSON',
                        type: 'GET',
                        success: function(data) {
                            $("#dsa_advance_tour").empty();

                            // Check if data contains any options
                            if (data.length > 0) {
                                // Append a placeholder or default option
                                $("#dsa_advance_tour").append(
                                    '<option value="">Select DSA Advance</option>');

                                // Loop through the data and create options
                                data.forEach(item => {
                                    const option =
                                        `<option value="${item.id}">${item.advance_no}</option>`;
                                    $("#dsa_advance_tour").append(option);
                                });
                            } else {
                                // Append a message if no data is available
                                $("#dsa_advance_tour").append(
                                    '<option value="">No DSA Advances availed</option>');
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
                            $('#advance_amount').val(data.amount ?? 0);
                            calculateNetPayable();
                        },
                        error: function(error) {
                            alert("Error fetching data", error);
                        }
                    });
                }
            }

            function calculateFuelClaimTotal() {
                let totalAmount = 0;

                $('input[name^="fuel_claim_details"][name$="[amount]"]').each(function() {
                    const value = parseFloat($(this).val());
                    if (!isNaN(value)) {
                        totalAmount += value;
                    }
                });

                console.log(totalAmount);

                $('#amount').val(totalAmount.toFixed(2));
            }

            function getVehicleDetails(vehicleId) {
                $.ajax({
                    url: '/getvehicledetailtypebyid/' + vehicleId,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);

                        var initialReading = response.final_reading;
                        var mileage = response.vehicle_type.mileage;

                        if (!mileage) {
                            alert('Mileage not set for this vehicle')
                        }
                        if (!initialReading) {
                            alert('Initial reading not set for this vehicle')
                        }

                        $('input[name="fuel_claim_details[AAAAA][initial_reading]"]').val(
                            initialReading);
                        $('input[name="fuel_claim_details[AAAAA][mileage]"]').val(mileage);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error: ' + textStatus + ' - ' + errorThrown);
                        alert('An error occurred while fetching vehicle details.');
                    }
                });
            }

            function calculateFuelFromDistance(row) {
                var initialReading = parseFloat(row.find(
                    'input[name^="fuel_claim_details"][name$="[initial_reading]"]').val()) || 0;
                var finalReadingReading = parseFloat(row.find(
                    'input[name^="fuel_claim_details"][name$="[final_reading]"]').val()) || 0;

                var mileage = parseFloat(row.find('input[name^="fuel_claim_details"][name$="[mileage]"]').val()) ||
                    0;

                if (finalReadingReading > initialReading) {
                    if (mileage > 0) {
                        var distance = finalReadingReading - initialReading;
                        var fuelinLtr = (distance / mileage).toFixed(2);

                        row.find('input[name^="fuel_claim_details"][name$="[quantity]"]').val(fuelinLtr)
                    }
                } else {
                    row.find('input[name^="fuel_claim_details"][name$="[quantity]"]').val(0)
                }
            }

            function calculateMileageFromFuel(row) {
                var initialReading = parseFloat(row.find(
                    'input[name^="fuel_claim_details"][name$="[initial_reading]"]').val()) || 0;
                var finalReadingReading = parseFloat(row.find(
                    'input[name^="fuel_claim_details"][name$="[final_reading]"]').val()) || 0;

                var fuelinLtr = parseFloat(row.find('input[name^="fuel_claim_details"][name$="[quantity]"]')
                    .val()) || 0;

                if (fuelinLtr > 0) {
                    var distance = finalReadingReading - initialReading;
                    var mileage = (distance / fuelinLtr).toFixed(2);

                    row.find('input[name^="fuel_claim_details"][name$="[mileage]"]').val(mileage);
                } else {
                    row.find('input[name^="fuel_claim_details"][name$="[mileage]"]').val(mileage);
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
            $(document).on("change", "#travel_autorization", getTravelAuthorizationDetails);
            $(document).on("change", "#travel_autorization", getDsaAvanceByTravelAuth);
            $(document).on("change", "#dsa_advance_tour", getDsaAvanceDetails);
            $(document).on("input change", "#grand_total_amount, #advance_amount, input[name*='[total_amount]']",
                calculateNetPayable);
            $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[amount]"]', function() {
                calculateFuelClaimTotal();
            });

            $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[final_reading]"]',
                function() {
                    var row = $(this).closest('tr');
                    calculateFuelFromDistance(row);
                });

            $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[quantity]"]', function() {
                var row = $(this).closest('tr');
                calculateMileageFromFuel(row);
            });

            $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[rate]"]', function() {
                var row = $(this).closest('tr');
                calculateTotalAmountForRow(row);
            });

            $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[quantity]"]', function() {
                var row = $(this).closest('tr');
                calculateTotalAmountForRow(row);
            });
        });
    </script>
@endpush
