@extends('layouts.app')
@section('page-title', 'DSA Claim and Settlement')
@section('content')



<form action="{{ route('dsa-claim-settlement.store') }}" method="post" enctype="multipart/form-data" id="apply_dsa">
    @csrf
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
                        <label for="advance_no">Advance No </label>
                        <select class="form-control" id="advance_no" name="advance_no">
                            <option value="" selected disabled>Select your option</option>
                            @foreach ($advances as $advance)
                                <option value="{{ $advance['id'] }}" {{ old('advance_no') == $advance['id'] ? 'selected' : '' }}>{{ $advance['advance_no'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="advance_no">Advance Amount </label>
                        <input type="number" class="form-control" id="advance_amount" name="advance_amount" value="{{ old('advance_amount') }}" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_amount">Total Amt Adjusted </label>
                        <input type="number" class="form-control" id="total_amount_adjusted" name="total_amount_adjusted" value="{{ old('total_amount_adjusted') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="netpayable">Net Payable Amount</label>
                        <input type="number" class="form-control" id="net_payable_amount" name="net_payable_amount" value="{{ old('net_payable_amount') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="balance_amount">Balance Amount </label>
                        <input type="text" class="form-control" id="balance_amount" name="balance_amount" value="{{ old('balance_amount') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="file">Attachment (s)</label>
                        <input type="file" id="attachment" class="form-control" name="attachment">
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
                    <p class="text-danger large">*The total number of days may differ from the selected dates, as 0.5 is subtracted for each half day.</p>
                    <div class="table-responsive">
                        <table id="qualifications" class="table table-condensed table-bordered table-striped table-sm">
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
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td class="text-center">
                                        <input type="date" name="dsa_claim_detail[AAAAA][from_date]" class="form-control form-control-sm resetKeyForNew" />

                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="dsa_claim_detail[AAAAA][from_location]" class="form-control form-control-sm resetKeyForNew" />
                                    </td>

                                    <td class="text-center">
                                        <input type="date" name="dsa_claim_detail[AAAAA][to_date]" class="form-control form-control-sm resetKeyForNew" />
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="dsa_claim_detail[AAAAA][to_location]" class="form-control form-control-sm resetKeyForNew" />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="dsa_claim_detail[AAAAA][total_days]" class="form-control form-control-sm resetKeyForNew mycal hasDatepicker"   step="0.5"/>
                                    </td>

                                    <td class="text-center">
                                        <input type="number" name="dsa_claim_detail[AAAAA][daily_allowance]" value="{{ DAILY_ALLOWANCE }}" class="form-control form-control-sm resetKeyForNew" disabled />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="dsa_claim_detail[AAAAA][travel_allowance]" class="form-control form-control-sm resetKeyForNew" />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="dsa_claim_detail[AAAAA][total_amount]" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>
                                    <td class="text-center">
                                        {{-- <input type="text" name="dsa_claim_detail[AAAAA][from_date]" class="form-control form-control-sm resetKeyForNew" style="background-color: rgb(255, 255, 255);" disabled> --}}
                                        <textarea name="dsa_claim_detail[AAAAA][remark]" class="form-control form-control-sm resetKeyForNew" rows="2"></textarea>
                                    </td>

                                </tr>
                                <tr class="notremovefornew">
                                    <td colspan="9"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px"><i class="fa fa-plus"></i> Add New Row</a>
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
            'cancelName' => 'CANCEL'
            ])

        </div>

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
    window.DAILY_ALLOWANCE = {{ json_encode(constant('DAILY_ALLOWANCE')) }};
@endpush
