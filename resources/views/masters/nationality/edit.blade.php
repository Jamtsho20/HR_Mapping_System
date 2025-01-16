@extends('layouts.app')
@section('page-title', 'Nationality')
@section('content')
<form action="{{url('master/nationalities/' .$nationality->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">

        <div class="card-body">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{$nationality->name}}" name="name">
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/nationalities') ,
            'cancelName' => 'CANCEL'
            ])

        </div>
    </div>

</form>
@include('layouts.includes.delete-modal')
@endsection