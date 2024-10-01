@extends('layouts.app')
@section('page-title', 'View Advance')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Advance Loan Details</h5>
        <a href="{{ url('advance-loan/apply') }}" class="close custom-close-btn" data-dismiss="modal">
            &times;
        </a>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="form-group col-sm-4">
                <label>Advance No</label>
                <input type="text" class="form-control" value="{{ $advance->advance_no }}" readonly>
            </div>
            <div class="form-group col-sm-4">
                <label>Date</label>
                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($advance->date)->format('Y-m-d') }}" readonly>
            </div>
            <div class="form-group col-sm-4">
                <label>Advance/Loan Type</label>
                <input type="text" class="form-control" value="{{ optional($advance->advanceType)->advancetype ?? 'N/A' }}" readonly>
            </div>
        </div>

        {{-- Dynamic Fields Based on Advance Type --}}
        @if ($advance->advance_type_id == 1) {{-- Advance-to-Staff --}}
            <div class="row">
                <div class="form-group col-sm-4">
                    <label>Mode Of Travel</label>
                    <input type="text" class="form-control" value="{{ $advance->mode_of_travel }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>From Location</label>
                    <input type="text" class="form-control" value="{{ $advance->from_location }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>To Location</label>
                    <input type="text" class="form-control" value="{{ $advance->to_location }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>From Date</label>
                    <input type="text" class="form-control" value="{{ $advance->from_date }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>To Date</label>
                    <input type="text" class="form-control" value="{{ $advance->to_date }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>Amount</label>
                    <input type="text" class="form-control" value="{{ number_format($advance->amount, 2) }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>Purpose</label>
                    <input type="text" class="form-control" value="{{ $advance->purpose }}" readonly>
                </div>
            </div>
        @elseif ($advance->advance_type_id == 2) {{-- DSA Advance --}}
            <div class="row">
            <div class="form-group col-sm-4">
                    <label>Mode Of Travel</label>
                    <input type="text" class="form-control" value="{{ $advance->mode_of_travel }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>From Location</label>
                    <input type="text" class="form-control" value="{{ $advance->from_location }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>To Location</label>
                    <input type="text" class="form-control" value="{{ $advance->to_location }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>From Date</label>
                    <input type="text" class="form-control" value="{{ $advance->from_date }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>To Date</label>
                    <input type="text" class="form-control" value="{{ $advance->to_date }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>Amount</label>
                    <input type="text" class="form-control" value="{{ number_format($advance->amount, 2) }}" readonly>
                </div>
            </div>
        @elseif ($advance->advance_type_id == 3) {{-- Salary Advance --}}
            <div class="row">
                <div class="form-group col-sm-4">
                    <label>No Of EMI</label>
                    <input type="text" class="form-control" value="{{ $advance->no_of_emi }}" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label>Monthly EMI Amount</label>
                    <input type="text" class="form-control" value="{{ number_format($advance->monthly_emi, 2) }}" readonly>
                </div>
            </div>
        @endif
      
    </div>
</div>

@endsection
