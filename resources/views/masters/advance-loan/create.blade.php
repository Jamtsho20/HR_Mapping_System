@extends('layouts.app')
@section('page-title', 'Advance/Loan')
@section('content')

<form action="{{ url('master/advance-loans') }}" class="js-validation-bootstrap" method="POST" id="newModalForm">
    @csrf
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="name"> Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required="required">
            </div>

        </div>
    </div>
    <div class="card-footer">
        @include('layouts.includes.buttons', [
        'buttonName' => 'SAVE',
        'cancelUrl' => url('master/advance-loans') ,
        'cancelName' => 'CANCEL'
        ])

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')



@endpush