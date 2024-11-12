@extends('layouts.app')
@section('page-title', 'Edit Advance')
@section('content')

<form action="{{ route('apply.update', $advance->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Edit Advance Loan</h3>
                <a href="{{ route('apply.index') }}" class="close custom-close-btn" id="btn_addClose" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="advance_no">Advance No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="advance_no" value="{{ $advance->advance_no }}" id="advance_no" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="advance_type">Advance Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="advance_type" value="{{ optional($advance->advanceType)->name ?? 'N/A' }}" readonly>
                            <input type="hidden" name="advance_type" value="{{ $advance->advanceType->id ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" value="{{ old('date', $advance->date) }}" id="date" required readonly>
                        </div>
                    </div>
                </div>
                <div id="dynamic-fields">
                    @if($advance->advanceType)

                    @if($advance->advanceType->name === 'Advance to Staff')
                    @include('advance-loan.apply.edit.advance_to_staff')

                    @elseif($advance->advanceType->name === 'DSA Advance(Tour)')
                    @include('advance-loan.apply.edit.dsa_advance')

                    @elseif($advance->advanceType->name === 'Electricity Imprest Advance')
                    @include('advance-loan.apply.edit.electricity_imprest_advance')

                    @elseif($advance->advanceType->name === 'Imprest Advance')
                    @include('advance-loan.apply.edit.general_imprest_advance')

                    @elseif($advance->advanceType->name === 'Gadget EMI')
                    @include('advance-loan.apply.edit.gadget_emi')

                    @elseif($advance->advanceType->name === 'SIFA LOAN')
                    @include('advance-loan.apply.edit.sifa_loan')

                    @elseif($advance->advanceType->name === 'Salary Advance')
                    @include('advance-loan.apply.edit.salary_advance')

                    @endif
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remark">Remark</label>
                            <input type="text" class="form-control" id="remark" name="remark" value="{{ $advance->remark }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="attachment">Attachment</label>
                            @if($advance->attachment)
                            <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*,application/pdf">
                            <a href="{{ asset($advance->attachment) }}" target="_blank" class="btn btn-link">
                                <i class="fas fa-file-alt"></i> View Attachment
                            </a><br>
                            <small class="text-muted">Leave blank if you don't want to change the attachment.</small>
                            @else
                            <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*,application/pdf">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Update </button>
                    <a href="{{ url('advance-loan/apply') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL </a>
                </div>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection