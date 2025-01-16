@extends('layouts.app')
@section('page-title', 'Resignation Types')
@section('content')

<form action="{{ url('master/resignation-types') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="resignation_type">Resignation Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="resignation_type" value="{{ old('resignation_type') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="4">{{ old('remarks') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' =>url('master/resignation-types'),
            'cancelName' => 'CANCEL'
            ])

        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection