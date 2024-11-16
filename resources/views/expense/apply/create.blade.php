@extends('layouts.app')
@section('page-title', 'Create Expense')
@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($headers as $header)
                    @php
                        $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($header->name));
                    @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $sanitizedName }}"
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
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ $sanitizedName }}"
                    role="tabpanel" aria-labelledby="tab-{{ $sanitizedName }}">
                    @if ($id == 2)
                        <form action="{{ route('apply-expense.store') }}" method="post" enctype="multipart/form-data"
                            id="apply_expense">
                            @csrf
                            <div class="card-body">
                                <div class="row">
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
                                                value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="amount">Amount <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                value="{{ old('amount') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="description" name="description"
                                                value="{{ old('description') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="file">Upload File <span id="attachment_required"
                                                    class="text-danger" style="display:none;">*</span></label>
                                            <input type="file" id="attachment" class="form-control" name="file"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <!--Conveyance Form-->
                                @include('expense.apply.types.conveyance')
                            </div>
                            <div class="card-footer">
                                @include('layouts.includes.buttons', [
                                    'buttonName' => 'Apply Expense',
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
                                                <label for="advance_no">Advance No </label>
                                                <select class="form-control" id="advance_no" name="advance_no">
                                                    <option value="" selected disabled>Select your option</option>
                                                    @foreach ($advances as $advance)
                                                        <option value="{{ $advance['id'] }}"
                                                            {{ old('advance_no') == $advance['id'] ? 'selected' : '' }}>
                                                            {{ $advance['advance_no'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="advance_no">Advance Amount </label>
                                                <input type="number" class="form-control" id="advance_amount"
                                                    name="advance_amount" value="{{ old('advance_amount') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="total_amount">Total Amt Adjusted </label>
                                                <input type="number" class="form-control" id="total_amount_adjusted"
                                                    name="total_amount_adjusted"
                                                    value="{{ old('total_amount_adjusted') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="netpayable">Net Payable Amount</label>
                                                <input type="number" class="form-control" id="net_payable_amount"
                                                    name="net_payable_amount" value="{{ old('net_payable_amount') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="balance_amount">Balance Amount </label>
                                                <input type="text" class="form-control" id="balance_amount"
                                                    name="balance_amount" value="{{ old('balance_amount') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
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
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table id="qualifications"
                                                    class="table table-condensed table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>#</th>
                                                            <th>From Date</th>
                                                            <th>From Location</th>
                                                            <th>To Date</th>
                                                            <th>To Location</th>
                                                            <th>Total Days</th>
                                                            <th>DA</th>
                                                            <th>TA</th>
                                                            <th>Total Amount</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">
                                                                <a href="#"
                                                                    class="delete-table-row btn btn-danger btn-sm"><i
                                                                        class="fa fa-times"></i></a>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="date"
                                                                    name="dsa_claim_detail[AAAAA][from_date]"
                                                                    class="form-control form-control-sm resetKeyForNew" />

                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text"
                                                                    name="dsa_claim_detail[AAAAA][from_location]"
                                                                    class="form-control form-control-sm resetKeyForNew" />
                                                            </td>

                                                            <td class="text-center">
                                                                <input type="date"
                                                                    name="dsa_claim_detail[AAAAA][to_date]"
                                                                    class="form-control form-control-sm resetKeyForNew" />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text"
                                                                    name="dsa_claim_detail[AAAAA][to_location]"
                                                                    class="form-control form-control-sm resetKeyForNew" />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="dsa_claim_detail[AAAAA][total_days]"
                                                                    class="form-control form-control-sm resetKeyForNew mycal hasDatepicker" />
                                                            </td>

                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="dsa_claim_detail[AAAAA][daily_allowance]"
                                                                    value="{{ DAILY_ALLOWANCE }}"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    disabled />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="dsa_claim_detail[AAAAA][travel_allowance]"
                                                                    class="form-control form-control-sm resetKeyForNew" />
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="dsa_claim_detail[AAAAA][total_amount]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    disabled>
                                                            </td>
                                                            <td class="text-center">
                                                                {{-- <input type="text" name="dsa_claim_detail[AAAAA][from_date]" class="form-control form-control-sm resetKeyForNew" style="background-color: rgb(255, 255, 255);" disabled> --}}
                                                                <textarea name="dsa_claim_detail[AAAAA][remark]" class="form-control form-control-sm resetKeyForNew" rows="2"></textarea>
                                                            </td>

                                                        </tr>
                                                        <tr class="notremovefornew">
                                                            <td colspan="9"></td>
                                                            <td class="text-right">
                                                                <a href="#"
                                                                    class="add-table-row btn btn-sm btn-info"
                                                                    style="font-size: 12px"><i class="fa fa-plus"></i> Add
                                                                    New Row</a>
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

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transferclaim">Transfer Claim <span
                                                        class="text-danger">*</span></label>
                                                <select name="transfer_claim" id="transferclaim"
                                                    class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>Select an option</option>s
                                                    @foreach ($transferClaim as $transfer)
                                                        <option value="{{ $transfer->name }}"
                                                            {{ old('transfer_claim') == $transfer->name ? 'selected' : '' }}>
                                                            {{ $transfer->name }}</option>
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
                                                <input type="number" class="form-control" name="amount_claimed"
                                                    value="{{ old('amount_claimed') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Attachment</label>
                                                <input type="file" class="form-control" name="attachment">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                @include('layouts.includes.buttons', [
                                    'buttonName' => 'SUBMIT',
                                    'cancelUrl' => url('expense/transfer-claim'),
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
            window.DAILY_ALLOWANCE = {{ json_encode(constant('DAILY_ALLOWANCE')) }};

            var selectedExpenseType = $('#expense_type');
            var formSections = $('.dynamic-form');

            selectedExpenseType.on('change', function() {
                var selectedType = selectedExpenseType.val();

                // Hide all dynamic form sections and disable their inputs
                formSections.each(function() {
                    $(this).hide();
                    disableFormFields($(this));
                });

                // Show and enable the corresponding form section based on the selected type
                if (selectedType === '1') {
                    var section = $('#conveyance_expense_form');
                    section.show();
                    enableFormFields(section);
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

            $('#transferclaim').on('change', function() {
                var selectedValue = $(this).val();
                var distanceField = $('#distanceField');

                if (selectedValue === 'Carriage Charge') {
                    distanceField.show();
                } else {
                    distanceField.hide();
                }
            });

        });
    </script>
@endpush
