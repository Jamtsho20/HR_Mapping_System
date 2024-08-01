@extends('layouts.app')
@section('page-title', 'Pay Groups')
@section('content')

<form action="{{ route('pay-groups.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="">Name </label>
                <input type="text" class="form-control" name="name" value="" required="required">
            </div>
            <div class="form-group col-md-12">
                <label for="applicable_on">Applicable on</label>
                    <select name="applicable_on" id="applicable_on" class="form-control form-control-sm" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="1">Employee Category</option>
                        <option value="2">Grade</option>
                    </select>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('paymaster/pay-groups') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush