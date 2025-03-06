@extends('layouts.app')
@section('page-title', 'Apply Expense')
@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    @include('layouts.includes.loader')
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
                                            <input type="text" class="form-control" name="expense_no" id="expense_no"
                                                placeholder="Generating..." readonly>
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
                                        <select class="form-control select2" id="mas_vehicle_id" name="mas_vehicle_id" required>
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
                            enctype="multipart/form-data" id="apply_dsa">
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
                                                <input type="text" class="form-control" name="dsa_claim_no"
                                                    id="dsa_claim_no" placeholder="Generating..." readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">

                                                <label for="travel_authorization_id">Travel No(s) <span
                                                        class="text-danger">*</span></label>
                                                        <select class="form-control" id="travel_authorization"
                                                                name="travel_authorization_id[]" multiple required>
                                                            @foreach ($travels as $travel)
                                                                <option value='@json(["id" => $travel->id, "advance_id" => $travel->advance->id ?? null])'>
                                                                    {{ $travel->travel_authorization_no }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    <input type="hidden" name='advance_ids'>
                                            </div>
                                        </div>


                                        {{-- <div class="col-md-3">
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
                                        </div> --}}
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dsa_advance_tour">Advance No</label>
                                                <select class="form-control" id="dsa_advance_tour" name="advance_no">
                                                    <option value="" selected disabled>Select your option</option>
                                                </select>
                                            </div>
                                        </div> --}}
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
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="file">Attachment (s)</label>
                                                <input type="file" id="attachment" class="form-control"
                                                    name="attachments">
                                            </div>
                                            <!-- Display area for uploaded file -->
                                            <div id="uploaded-file" style="margin-top: 10px;">
                                                <!-- Placeholder for uploaded file -->
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <p class="info-green p-3 pt-0" style="text-indent: -.01em; padding-left: 1em;">
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
                                                        {{-- <tr class="data-row">
                                                            <td class="text-center">
                                                                <a href="#"
                                                                    class="delete-table-row btn btn-danger btn-sm">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                                <input
                                                                    name="dsa_claim_detail[AAAAA][travel_no]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    required />
                                                            </td>
                                                            <td class="text-center">
                                                                <input
                                                                    name="dsa_claim_detail[AAAAA][advance_no]"
                                                                    class="form-control form-control-sm resetKeyForNew"
                                                                    required />
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
                                                            <td colspan="11"></td>
                                                            <td class="text-right">
                                                                <a href="#"
                                                                    class="add-table-row btn btn-sm btn-info">
                                                                    <i class="fa fa-plus"></i> Add New Row
                                                                </a>
                                                            </td>
                                                        </tr> --}}
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
                                        'cancelUrl' => url('/expense/apply-expense'),
                                        'cancelName' => 'CANCEL',
                                    ])

                                </div>

                            </div>
                        </form>
                    @elseif ($id == 4)
                        <form action="{{ route('transfer-claim.store') }}" method="POST" id="apply_transfer"
                            enctype="multipart/form-data">
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
                                                    id="transfer_claim_no" placeholder="Generating..." readonly>
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
            $('#travel_authorization').select2({
            placeholder: "Select travel numbers",
            allowClear: true
        });

       // Bind the change event so that your function is called whenever the selection changes.
    $('#travel_authorization').on('change', function() {
        getTravelAuthorizationDetailsMultiple();
    });

    // Gather all option values for auto-selection.
    var allValues = [];
    $('#travel_authorization option').each(function() {
        allValues.push($(this).val());
    });

    // Select all options on load, which triggers the change event.
    $('#travel_authorization').val(allValues).trigger('change');


            const form = document.getElementById('apply_expense');
            const dsaForm = document.getElementById('apply_dsa');
            const transferForm = document.getElementById('apply_transfer');
            const loader = document.getElementById('loader');
            const submitBtn = document.getElementById('submitBtn');

            transferForm.addEventListener('submit', function(e) {
                // Show loader
                loader.style.display = 'flex';
            });
            dsaForm.addEventListener('submit', function(e) {
                // Show loader
                loader.style.display = 'flex';
            });
            form.addEventListener('submit', function(e) {
                // Show loader
                loader.style.display = 'flex';
            });
            window.DAILY_ALLOWANCE = {{ $dailyAllowance->da_in_country }};

            // function calculateGrandTotal() {
            //     let grandTotal = 0;

            //     // Loop through each row and sum up the total amounts
            //     $("input[name*='[total_amount]']").each(function() {
            //         const rowTotal = parseFloat($(this).val() || 0, 10);
            //         grandTotal += rowTotal;
            //     });

            //     // Update the grand total input field
            //     $('#grand_total_amount').val(grandTotal);
            // }
            // const dateFields = document.querySelectorAll('input[name^="fuel_claim_details"][name$="[date]"]');

            // // Iterate through each field and set the min attribute
            // // dateFields.forEach(field => {
            // //     field.setAttribute('min', '2025-01-01');
            // // });

            // const dsaFields = document.querySelectorAll('input[name^="dsa_claim_detail"][name$="[from_date]"]');

            // // // Iterate through each field and set the min attribute
            // dsaFields.forEach(field => {
            //     field.setAttribute('min', '2025-01-01');
            // });


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
                            showErrorMessage('Amount cannot exceed 20000.'); // Display an showErrorMessage
                            $(this).val(20000); // Set the value to 20000
                        }
                    });
                }
            }

            // Initial call to handle the current value of the dropdown
            handleTransferClaimChange();

            // Change event listener for the dropdown
            $('#transferclaim').on('change', handleTransferClaimChange);


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
                            showErrorMessage("The 'To Date' must be equal to or later than the 'From Date'.");
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

            $(document).on("click", ".delete-table-row", function () {
    const row = $(this).closest('tr'); // Get the row being deleted
    const travelAuthClassMatch = row.closest("[class*='travel-auth-']").attr('class')?.match(/travel-auth-(\d+)/);

    row.remove(); // Remove the row first

    if (travelAuthClassMatch) {
        const travelAuthGroupClass = travelAuthClassMatch[1];
        //updateDateConstraints(travelAuthGroupClass); // Recalculate constraints
    }

    $('input[name*="dsa_claim_detail"][name*="total_amount"]').trigger('change');
    calculateTotalNumberOfDays();
    calculateGrandTotal();
    calculateNetPayable();
});

$(document).on("click", ".add-table-row", function () {
    const table = $(this).closest("table");
    const lastRow = table.find("tr:last"); // Get the last row
    const newRow = lastRow.clone(); // Clone last row
    newRow.find("input").val(""); // Clear input values
    table.append(newRow); // Add the new row

    const travelAuthClassMatch = table.closest("[class*='travel-auth-']").attr('class')?.match(/travel-auth-(\d+)/);
    if (travelAuthClassMatch) {
        const travelAuthGroupClass = travelAuthClassMatch[1];
        //updateDateConstraints(travelAuthGroupClass); // Recalculate constraints
    }

    calculateGrandTotal();
    calculateNetPayable();
});

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

// Example: When a row is added, you might want to update the constraints as well:
$(document).on("click", ".add-table-row", function () {
    // Assume your row cloning or creation logic here
    // After adding the new row, update constraints for the group:
    const travelAuthClassMatch = $(this).closest("[class*='travel-auth-']").attr('class')?.match(/travel-auth-(\d+)/);

    if (travelAuthClassMatch) {
        const travelAuthGroupClass = travelAuthClassMatch[1];
        updateAllRowDateConstraints(travelAuthGroupClass);

    }
});


 // Add the row for adding a new entry (Add New Row)
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



    // Generate a new unique row ID
    const newRowId = `${Date.now()}${Math.floor(Math.random() * 10)}`;

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

document.addEventListener("click", function (event) {
    if (event.target.closest(".add-row-btn")) {
        event.preventDefault();
        addNewRow(event.target);
    }
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


                    // Loop through the returned travel authorizations
                    data.travel_authorization_details.travel_authorizations.forEach((travel_authorizations, authIndex) => {
                        // <td colspan="4" class="text-center" style="color: black;">
                        //                 <input type="hidden" name="dsa_claim_detail[${travel_authorizations.travelAuthorization.id}][travel_authorization_id]" value="${travel_authorizations.travelAuthorization.id}">
                        //                 Travel Authorization Number: ${travel_authorizations.travelAuthorization.travel_authorization_no}
                        //             </td>
                        //             <td colspan="4" class="text-center" style="color: black;">
                        //                 <input type="hidden" name="dsa_claim_detail[${travel_authorizations.details.id}][advance_detail_id]" value="${travel_authorizations.advance_detail ? travel_authorizations.advance_detail.id : ''}">
                        //                 ${travel_authorizations.advance_details && travel_authorizations.advance_details.advance_no
                        //                     ? `Advance Number: ${travel_authorizations.advance_details.advance_no}, Advance Amount: ${travel_authorizations.advance_details.amount || 'N/A'}`
                        //                     : 'Advance Number: N/A, Advance Amount: N/A'}
                        //             </td>
                        // Append as a single row
                        const travelAuthGroupClass = `${travel_authorizations.travelAuthorization.id}`;
                        tbody.append(`
                                <tr class="travel-auth-${travelAuthGroupClass}">

                                    <td colspan="4" class="text-center" style="color: black;  font-weight: bold;">
                                        <span name="dsa_claim_detail[${travel_authorizations.travelAuthorization.id}][travel_authorization_id]" data-value="${travel_authorizations.travelAuthorization.id}: ${travel_authorizations.advance_details ? travel_authorizations.advance_details.id : ''}">
                                            Travel Authorization Number: ${travel_authorizations.travelAuthorization.travel_authorization_no}
                                        </span>
                                    </td>
                                    <td colspan="4" class="text-center" style="color: black;  font-weight: bold;">
                                        <span name="dsa_claim_detail[${travel_authorizations.details.id}][advance_detail_id]" data-value="${travel_authorizations.advance_detail ? travel_authorizations.advance_detail.id : ''}">
                                            ${travel_authorizations.advance_details && travel_authorizations.advance_details.advance_no
                                                ? `Advance Number: ${travel_authorizations.advance_details.advance_no}, Advance Amount: ${travel_authorizations.advance_details.amount || 'N/A'}`
                                                : 'Advance Number: N/A, Advance Amount: N/A'}
                                        </span>
                                    </td>

                                    <td colspan="4" class="text-center" style="color: black;">
                                        <input type="file" id="attachment" class="form-control" name="files[${travel_authorizations.travelAuthorization.id}]" enctype="multipart/form-data">
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


                                        taAmount+=DAILY_ALLOWANCE * days;


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



                            const btnRow = `
                        <tr class=" travel-auth-${travelAuthGroupClass} notremovefornew">
                            <td colspan="9"></td>
                            <td class="text-right">
                               <a href="#" class=" add-row-btn btn btn-sm btn-info "  style="font-size: 12px">
    <i class="fa fa-plus"></i> Add New Row
</a>

                            </td>
                        </tr>`;
                    tbody.append(btnRow);
                    setMaxToDate(travelAuthGroupClass);


                                            // const lastDataRow = tbody.find("tr.data-row").last();
                                            // const lastDataRowDailyAllowanceField = lastDataRow.find(
                                            //     "input[name*='[daily_allowance]']");
                                            // lastDataRowDailyAllowanceField.val(
                                            //     DAILY_ALLOWANCE);
                        }

                    });



// Event listener for dynamically added buttons
$(document).on("click", ".add-specific-row", function (e) {
    e.preventDefault();
    addNewRow(this);
});


                    // Update the grand total
                    $('#grand_total_amount').val(grandTotal);

                    const totalNumDays = document.getElementById('total_number_of_days');
                    totalNumDays.value = totalDays;
                    $('#advance_amount').val(totalAdvanceAmount ?? 0);
                    calculateTotalNumberOfDays()
                    calculateGrandTotal();
                    calculateNetPayable();

                }
            }
            ,
            error: function(error) {
                showErrorMessage(`Error fetching data: ${error.responseText || error.statusText}`);
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
            function getTravelAuthorizationDetails() {
                const travelAuthorizationId = $("#travel_autorization").val();

                if (travelAuthorizationId !== '') {
                    $.ajax({
                        url: `/gettravelauthorizationbytravelauthorizationid/${travelAuthorizationId}`,
                        dataType: 'JSON',
                        type: 'GET',
                        success: function(data) {
                            const totalNumberOfDays = data.total_days;
                            const totalNumDays = document.getElementById('total_number_of_days');
                            totalNumDays.value = totalNumberOfDays;

                            const tbody = $("#travelstable tbody");
                            tbody.empty(); // Clear the existing rows

                            if (data.travel_authorization_details && data.travel_authorization_details
                                .details.length > 0) {
                                let grandTotal = 0;

                                // Loop through the travel authorization details
                                data.travel_authorization_details.details.forEach((detail, index) => {
                                    // const totalAmount = DAILY_ALLOWANCE * detail.no_of_days;
                                    let totalAmount = 0;
                                    if(totalNumberOfDays > 15){
                                        totalAmount = (DAILY_ALLOWANCE * 15) + (totalNumberOfDays - 15) * (DAILY_ALLOWANCE / 2);
                                    }else{
                                        totalAmount = DAILY_ALLOWANCE * totalNumberOfDays;
                                    }
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
                                    step="0.5"
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
                                $('#grand_total_amount').val(grandTotal ?? 0);

                            } else {
                                tbody.append(
                                    `<tr><td colspan="9" class="text-center text-danger">No details found</td></tr>`
                                );
                            }
                        },
                        error: function(error) {
                            showErrorMessage(`Error fetching data: ${error.responseText || error.statusText}`);
                            $("#travelstable tbody").empty().append(`
                    <tr>
                        <td colspan="9" class="text-center text-danger">Error fetching details</td>
                    </tr>`);
                        }
                    });
                }
            }

            // function getDsaAvanceByTravelAuth() {
            //     const travelAuthorizationId = $("#travel_autorization").val();

            //     if (travelAuthorizationId !== '') {
            //         $.ajax({
            //             url: `/getdsaadvancebytravelauth/${travelAuthorizationId}`,
            //             dataType: 'JSON',
            //             type: 'GET',
            //             success: function(data) {
            //                 $("#dsa_advance_tour").empty();

            //                 // Check if data contains any options
            //                 if (data.length > 0) {
            //                     // Append a placeholder or default option
            //                     $("#dsa_advance_tour").append(
            //                         '<option value="">Select DSA Advance</option>');

            //                     // Loop through the data and create options
            //                     data.forEach(item => {
            //                         const option =
            //                             `<option value="${item.id}">${item.advance_no}</option>`;
            //                         $("#dsa_advance_tour").append(option);
            //                     });
            //                 } else {
            //                     // Append a message if no data is available
            //                     $("#dsa_advance_tour").append(
            //                         '<option value="">No DSA Advances availed</option>');
            //                 }
            //             },
            //             error: function(error) {
            //                 showErrorMessage("Error fetching data", error);
            //             }
            //         });
            //     }
            // }

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
                            showErrorMessage("Error fetching data", error);
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
                            showErrorMessage("Error fetching data", error);
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



                $('#amount').val(totalAmount.toFixed(2));
            }

            function getVehicleDetails(vehicleId) {
                $.ajax({
                    url: '/getvehicledetailtypebyid/' + vehicleId,
                    type: 'GET',
                    success: function(response) {


                        var initialReading = response.final_reading;
                        var mileage = response.vehicle_type.mileage;

                        if (!mileage) {
                            showErrorMessage('Mileage not set for this vehicle')
                        }
                        if (!initialReading) {
                            showErrorMessage('Initial reading not set for this vehicle')
                        }

                        $('input[name="fuel_claim_details[AAAAA][initial_reading]"]').val(
                            initialReading);
                        $('input[name="fuel_claim_details[AAAAA][mileage]"]').val(mileage);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                        showErrorMessage('An error occurred while fetching vehicle details.');
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
                    showErrorMessage('Please select a valid vehicle.');
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
            $(document).on('input change', 'input[name^="fuel_claim_details"][name$="[amount]"]', function() {
                calculateFuelClaimTotal();
            });

            function setMaxToDate(travelAuthGroupClass) {
    let allRows = $(".travel-auth-" + travelAuthGroupClass);

    if (allRows.length > 2) { // Ensure at least 3 rows exist
        let thirdLastRow = allRows.eq(-3); // Get the third-last row

        if (thirdLastRow.length) {
            let maxDate = thirdLastRow.find("input[name$='[to_date]']").val(); // Get its to_date

            if (maxDate) {
                // Apply maxDate to all rows except the last two
                allRows.slice(0, -2).each(function () {
                    $(this).find("input[name$='[to_date]']").attr("max", maxDate);
                });
            }
        }
    }
}


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
