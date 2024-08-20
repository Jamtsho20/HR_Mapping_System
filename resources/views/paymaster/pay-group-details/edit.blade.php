@extends('layouts.app')
@section('page-title', 'Edit Account Heads')
@section('content')
<form action="{{ url('paymaster/pay-groups/' . $payGroup->id) }}" method="POST">
@csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="mas_pay_group_id" value="{{ $payGroup->id }}" required>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_grade_id">Grade</label>
                        <select name="mas_grade_id" id="mas_grade_id" class="form-control" required>
                            <option value="" disabled>Select an option</option>
                            @foreach($grades as $id => $name)
                                <option value="{{ $id }}" {{ $detail->mas_grade_id == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="calculation_method">Calculation Method</label>
                        <select name="calculation_method" id="calculation_method" class="form-control" required>
                            <option value="" disabled>Select an option</option>
                            @foreach($calculationMethods as $id => $method)
                                <option value="{{ $id }}" {{ $detail->calculation_method == $id ? 'selected' : '' }}>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount" value="{{ old('amount', $detail->amount) }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-groups/' . $payGroup->id . '/edit') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush