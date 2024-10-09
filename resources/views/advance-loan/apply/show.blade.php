@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Advance Loan Application Details</h3>
            <a href="{{ route('apply.index') }}" class="close custom-close-btn" id="btn_addClose" aria-label="Close">
                <span aria-hidden="true">×</span>
            </a>
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="advance_no">Advance No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="advance_no" value="{{ $advance->advance_no }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="date" value="{{ \Carbon\Carbon::parse($advance->date)->format('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="advance_type">Advance Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="advance_type" value="{{ optional($advance->advanceType)->name ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Dynamic fields based on advance type -->
                <div id="dynamic-fields">
                    @if($advance->advanceType)
                    @if($advance->advanceType->name === 'Advance to Staff')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mode_of_travel">Mode of Travel</label>
                                <input type="text" class="form-control" id="mode_of_travel" value="{{ $advance->mode_of_travel_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="from_location">From Location</label>
                                <input type="text" class="form-control" id="from_location" value="{{ $advance->from_location }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="to_location">To Location</label>
                                <input type="text" class="form-control" id="to_location" value="{{ $advance->to_location }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="text" class="form-control" id="from_date" value="{{ $advance->from_date ? \Carbon\Carbon::parse($advance->from_date)->format('Y-m-d') : 'N/A' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="text" class="form-control" id="to_date" value="{{ $advance->to_date ? \Carbon\Carbon::parse($advance->to_date)->format('Y-m-d') : 'N/A' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="purpose">Purpose</label>
                                <input type="text" class="form-control" id="purpose" value="{{ $advance->purpose }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                @if($advance->attachment)
                                <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
                                @else
                                <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
                                @endif
                            </div>
                        </div>
                    </div>

                    @elseif($advance->advanceType->name === 'DSA Advance(Tour)')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mode_of_travel">Mode of Travel</label>
                                <input type="text" class="form-control" id="mode_of_travel" value="{{ $advance->mode_of_travel_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="from_location">From Location</label>
                                <input type="text" class="form-control" id="from_location" value="{{ $advance->from_location }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="to_location">To Location</label>
                                <input type="text" class="form-control" id="to_location" value="{{ $advance->to_location }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="text" class="form-control" id="from_date" value="{{ $advance->from_date ? \Carbon\Carbon::parse($advance->from_date)->format('Y-m-d') : 'N/A' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="text" class="form-control" id="to_date" value="{{ $advance->to_date ? \Carbon\Carbon::parse($advance->to_date)->format('Y-m-d') : 'N/A' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}" readonly>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="purpose">Purpose</label>
                                <input type="text" class="form-control" id="purpose" value="{{ $advance->purpose }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                @if($advance->attachment)
                                <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
                                @else
                                <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
                                @endif
                            </div>
                        </div>
                    </div>
                    @elseif($advance->advanceType->name === 'Electricity Imprest Advance')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="purpose">Purpose</label>
                                <input type="text" class="form-control" id="purpose" value="{{ $advance->purpose }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                @if($advance->attachment)
                                <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
                                @else
                                <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
                                @endif
                            </div>
                        </div>
                    </div>
                    @elseif($advance->advanceType->name === 'Gadget EMI')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="item_type">Item Type</label>
                                <input type="text" class="form-control" id="item_type" value="{{ $advance->item_type ?? 'N/A' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interest_rate">Interest Rate</label>
                                <input type="text" class="form-control" id="interest_rate" value="{{ $advance->interest_rate ?? 'N/A' }}%" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_amount">Total Amount</label>
                            <input type="text" class="form-control" id="total_amount" value="{{ number_format($advance->total_amount, 2) ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_of_emi">No. of EMI</label>
                            <input type="text" class="form-control" id="no_of_emi" value="{{ $advance->no_of_emi ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="monthly_emi_amount">Monthly EMI Amount</label>
                            <input type="text" class="form-control" id="monthly_emi_amount" value="{{ number_format($advance->monthly_emi_amount, 2) ?? 'N/A' }}" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="deduction_from_period">Deduction From Period</label>
                                    <input type="text" class="form-control" id="deduction_from_period" value="{{ $advance->deduction_from_period ? \Carbon\Carbon::parse($advance->deduction_from_period)->format('Y-m-d') : 'N/A' }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <input type="text" class="form-control" id="purpose" value="{{ $advance->purpose }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="attachment">Attachment</label>
                                        @if($advance->attachment)
                                        <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
                                        @else
                                        <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @elseif($advance->advanceType->name === 'Imprest Advance')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <input type="text" class="form-control" id="purpose" value="{{ $advance->purpose }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="attachment">Attachment</label>
                                        @if($advance->attachment)
                                        <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
                                        @else
                                        <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @elseif($advance->advanceType->name === 'Gadget EMI')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_of_emi">No. of EMI</label>
                                        <input type="text" class="form-control" id="no_of_emi" value="{{ $advance->no_of_emi ?? 'N/A' }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="deduction_from_period">Deduction From Period</label>
                                        <input type="text" class="form-control" id="deduction_from_period" value="{{ $advance->deduction_from_period ? \Carbon\Carbon::parse($advance->deduction_from_period)->format('Y-m-d') : 'N/A' }}" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="purpose">Purpose</label>
                                            <input type="text" class="form-control" id="purpose" value="{{ $advance->purpose }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="attachment">Attachment</label>
                                            @if($advance->attachment)
                                            <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
                                            @else
                                            <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @elseif($advance->advanceType->name === 'SIFA LOAN')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="interest_rate">Interest Rate</label>
                                            <input type="text" class="form-control" id="interest_rate" value="{{ $advance->interest_rate ?? 'N/A' }}%" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_amount">Total Amount</label>
                                            <input type="text" class="form-control" id="total_amount" value="{{ number_format($advance->total_amount, 2) ?? 'N/A' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="no_of_emi">No. of EMI</label>
                                            <input type="text" class="form-control" id="no_of_emi" value="{{ $advance->no_of_emi ?? 'N/A' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="monthly_emi_amount">Monthly EMI Amount</label>
                                            <input type="text" class="form-control" id="monthly_emi_amount" value="{{ number_format($advance->monthly_emi_amount, 2) ?? 'N/A' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="deduction_from_period">Deduction From Period</label>
                                            <input type="text" class="form-control" id="deduction_from_period" value="{{ $advance->deduction_from_period ? \Carbon\Carbon::parse($advance->deduction_from_period)->format('Y-m-d') : 'N/A' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="purpose">Purpose</label>
                                            <input type="text" class="form-control" id="purpose" value="{{ $advance->purpose }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="attachment">Attachment</label>
                                            @if($advance->attachment)
                                            <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
                                            @else
                                            <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endif
                    </div>

            </form>
        </div>
    </div>
</div>
@endsection