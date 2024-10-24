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
                    @include('advance-loan.apply.show.advance-to-staff')

                    @elseif($advance->advanceType->name === 'DSA Advance(Tour)')
                    @include('advance-loan.apply.show.dsa-advance')

                    @elseif($advance->advanceType->name === 'Electricity Imprest Advance')
                    @include('advance-loan.apply.show.electricity-imprest')

                    @elseif($advance->advanceType->name === 'Imprest Advance')
                    @include('advance-loan.apply.show.general-imprest')

                    @elseif($advance->advanceType->name === 'Gadget EMI')
                    @include('advance-loan.apply.show.gadget-emi')

                    @elseif($advance->advanceType->name === 'SIFA LOAN')
                    @include('advance-loan.apply.show.sifa-loan')
                    
                    @elseif($advance->advanceType->name === 'Salary Advance')
                    @include('advance-loan.apply.show.salary-advance')
                    
                    @endif
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection