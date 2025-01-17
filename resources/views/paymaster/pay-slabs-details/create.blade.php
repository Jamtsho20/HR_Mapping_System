@extends('layouts.app')
@section('page-title', 'Create Pay Slab Details')
@section('content')

<form action="{{ route('pay-slab-details.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <input type="hidden" class="form-control" name="mas_pay_slab_id" value="{{ $paySlab->id }}" required>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="pay_from">Pay From <span class="text-danger">*</span></label></label>
                        <input type="number" class="form-control" name="pay_from" id="pay_from" value="{{ old('pay_from') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="pay_to">Pay To <span class="text-danger">*</span></label></label>
                        <input type="number" class="form-control" name="pay_to" id="pay_to" value="{{ old('pay_to') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="amount">Amount <span class="text-danger">*</span></label></label>
                        <input type="number" class="form-control" name="amount" id="amount" value="{{ old('amount') }}" required>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
            <a href="{{ url('paymaster/pay-slabs/' . $paySlab->id . '/edit') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
