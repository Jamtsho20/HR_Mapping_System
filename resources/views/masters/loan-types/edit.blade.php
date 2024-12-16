@extends('layouts.app')
@section('page-title', 'Edit Loan Type')
@section('content')
<form action="{{ url('master/loan-types/' . $loanType->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $loanType->name) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $loanType->code) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'UPDATE',
                'cancelUrl' => url('master/loan-types'),
                'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>
@endsection
