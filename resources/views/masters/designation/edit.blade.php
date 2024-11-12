@extends('layouts.app')
@section('page-title', 'Designation')
@section('content')
<form action="{{ url('master/designations/' . $designation->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="name">Designation <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{$designation->name}}" name="name">
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/designations') ,
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>

</form>

@include('layouts.includes.delete-modal')
@endsection