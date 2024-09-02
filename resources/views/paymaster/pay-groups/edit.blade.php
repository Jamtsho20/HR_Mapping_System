@extends('layouts.app')

@section('page-title', 'Edit Pay Group')

@section('content')
<form action="{{ url('paymaster/pay-groups/' . $payGroup->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pay-group-name" name="name" value="{{ old('name', $payGroup->name) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="applicable_on">Applicable On<span class="text-danger">*</span></label>
                <select name="applicable_on" id="applicable_on" class="form-control" required>
                    <option value="" disabled selected hidden>Select an option</option>
                    <option value="1" {{ old('applicable_on', $payGroup->applicable_on) == 1 ? 'selected' : '' }}>Employee Group</option>
                    <option value="2" {{ old('applicable_on', $payGroup->applicable_on) == 2 ? 'selected' : '' }}>Grade</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-groups') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
<!-- Pay Group Details Form -->
@include('paymaster.pay-group-details.index', ['payGroup' => $payGroup])

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush