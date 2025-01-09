@extends('layouts.app')
@section('page-title', 'Create New Budget Code')
@section('content')

<form action="{{ route('budget-code.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="particular">Particular <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="particular" value="{{ old('particular') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="example-select">Budget Type<span class="text-danger">*</span></label>
                        <select class="form-control" id="example-select" name="budget_type" required="required">
                            <option value="" hidden>Select your option</option> <!-- Removed disabled and selected -->
                            @foreach($budgetTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/budget-code'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush