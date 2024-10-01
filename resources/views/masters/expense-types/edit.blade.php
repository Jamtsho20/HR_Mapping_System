@extends('layouts.app')
@section('page-title', 'Expense Type')
@section('content')
<form action="{{url('master/expense-types/' .$expense->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card card-themed card-transparent mb-0">

        <div class="card-header ">
            <h5 class="card-title">Create Expense</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="expense_type">Expense Category Name <span class="text-danger">*</span></label>
                        <label for="mas_expense_type_id"></label>
                        <select name="mas_expense_type_id" class="form-control">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($parentExpenseTypes as $parentType)
                            <option value="{{ $parentType->id }}"
                                {{ old('mas_expense_type_id', $expense->mas_expense_type_id? $expense->mas_expense_type_id: $expense->id) == $parentType->id ? 'selected' : '' }}>
                                {{ $parentType->name }}
                            </option>
                            @endforeach


                        </select>
                        <small>If no expense Type is selected, a new Type will be created.</small>

                    </div>

                </div>
                @if($expense->mas_expense_type_id!=null)
                <div class="col-md-6">
                    <label for="expense_type">Expense Name <span class="text-danger">*</span></label>
                    <input type="text" name="expense_names" class="form-control form-control-sm" value="{{$expense->mas_expense_type_id !=null?$expense->name:''}}" required>

                </div>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-check"></i> UPDATE
            </button>
            <a href="{{ url('master/expense-types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>

</form>
@include('layouts.includes.delete-modal')
@endsection