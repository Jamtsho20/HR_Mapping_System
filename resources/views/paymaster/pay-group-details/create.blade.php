@extends('layouts.app')
@section('page-title', 'Create Pay Group Details')
@section('content')

<form action="{{ route('pay-group-details.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <input type="hidden" class="form-control" name="mas_pay_group_id" value="{{ $payGroup->id }}" required>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_grade_id">Grade</label>
                        <select name="mas_grade_id" id="mas_grade_id" class="form-control">
                            <option value="" disabled selected hidden>Select an option</option>
                            @foreach($grades as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="calculation_method">Calculation Method</label>
                        <select name="calculation_method" id="calculation_method" class="form-control">
                            <option value="" disabled selected hidden>Select an option</option>
                            @foreach(config('global.calculation_method') as $key => $value)
                                <option value="{{ $key }}" {{ old('calculation_method') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount" value="{{ old('amount') }}" required>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
            <a href="{{ url('paymaster/pay-groups/' . $payGroup->id . '/edit') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection
