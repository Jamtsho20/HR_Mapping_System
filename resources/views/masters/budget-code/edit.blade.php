@extends('layouts.app')
@section('page-title', 'Edit Budget Code')
@section('content')
<form action="{{ url('master/budget-code/' . $budgetCode->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">Budget Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $budgetCode->code) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="particular">Particular <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="particular" value="{{ old('particular', $budgetCode->particular) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="budget_type">Budget Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="budget_type" name="budget_type" required>
                            <option value="" disabled>Select Budget Type</option>
                            <option value="1" {{ old('budget_type', $budgetCode->budget_type) == 1 ? 'selected' : '' }}>Capital</option>
                            <option value="2" {{ old('budget_type', $budgetCode->budget_type) == 2 ? 'selected' : '' }}>Current</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/budget-code'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>
@endsection
