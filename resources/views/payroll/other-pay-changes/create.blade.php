@extends('layouts.app')
@section('page-title', 'Other Pay Change')
@section('buttons')
    <a href="{{ route('other-pay-changes.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')
    <form action="{{ route('other-pay-changes.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body d-flex justify-content-between">
                <div class="form-group col-md-6">
                    <label for="for_month">For Month <span class="text-danger">*</span></label>
                    <input type="month" class="form-control" name="for_month" required="required">
                </div>
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fa fa-upload"></i> Save
                    </button>
                    &nbsp;
                    <a href="{{ url('payroll/other-pay-changes') }}" class="btn btn-danger">
                        <i class="fa fa-undo"></i> CANCEL
                    </a>
                </div>
            </div>
        </div>
    </form>

    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
