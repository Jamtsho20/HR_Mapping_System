@extends('layouts.app')
@section('page-title', 'Dzongkhag')
@section('content')
<form action="{{ url('master/dzongkhags') }}" method="POST">
    @csrf
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="dzongkhag">Dzongkhag <span class="text-danger">*</span></label>
                <input type="text" required="required" class="form-control" name="dzongkhag" value="{{ old('dzongkhag') }}">
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/dzongkhags'),
            'cancelName' => 'CANCEL'
            ])


        </div>
    </div>

</form>

@include('layouts.includes.delete-modal')
@endsection