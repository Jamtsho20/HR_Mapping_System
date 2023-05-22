@extends('layouts.app')
@section('page-title', 'Expense Type')
@section('content')
<form action="{{ url('master/expense-types') }}" method="POST">
    @csrf
    <div class="card ">
      
        <div class="card-body">
            <div class="form-group">
                <label for="expense">Expense Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="expense_type" value="{{ old('expense_type') }}" required="required">
            </div>

        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> SAVE
        </button>
        <a href="{{ url('master/expense-types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection