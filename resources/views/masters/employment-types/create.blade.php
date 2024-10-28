@extends('layouts.app')
@section('page-title', 'Employment Types')
@section('content')

<form action="{{ route('employment-types.store') }}" method="POST">
    @csrf
    <div class="card">

        <div class="card-body">
            <div class="form-group">
                <label for="name">Employment Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="employment_name" value="{{ old('employment_name') }}" required="required">
            </div>
            <div class="form-group">
                <label for="">Remarks </label>
                <textarea name="remarks" class="form-control" rows="4">{{ old('remarks') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/employment-types'),
            'cancelName' => 'CANCEL'
            ])
            
        </div>
    </div>

</form>

@endsection