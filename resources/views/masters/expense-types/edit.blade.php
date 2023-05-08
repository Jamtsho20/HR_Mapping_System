@extends('layouts.app')
@section('page-title', 'Expense Type')
@section('content')
<form action="{{url('master/expense-types/' .$expense->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">
            <div class="form-group">
                <label for="region">Expense Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="expense_type" value="{{$expense->expense_type}}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> UPDATE
        </button>
        <a href="{{ url('master/expense-types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection