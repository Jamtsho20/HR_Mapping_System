@extends('layouts.app')
@section('page-title', 'Account Heads')
@section('content')
<form action="{{ route('paymaster/account-heads/ .$accountHead->id) ') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="code">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" value="{{$accountHead->code}}" required="required">
            </div>
            <div class="form-group col-md-12">
                <label for="">Name </label>
                <input type="text" class="form-control" name="name" value="{{$accountHead->name}}" required="required">
            </div>
            <div class="form-group col-md-12">
                <label for="{{$accountHead->type}}">Type </label>
                    <select name="type" class="form-control form-control-sm" required placeholer="Select an option">
                        <option value="Credit">Credit</option>
                        <option value="Debit">Debit</option>
                    </select>
            </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
                    <a href="{{ url('paymaster/account-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
                </div>
        </div>
    </div>

 
</form>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush