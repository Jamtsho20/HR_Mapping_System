@extends('layouts.app')
@section('page-title', 'Qualification')
@section('content')

<form action="{{url('master/qualifications/' .$qualification->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="name">Qualification <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{$qualification->name}}" name="name">
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' =>url('master/qualifications') ,
            'cancelName' => 'CANCEL'
            ])

       
        </div>
    </div>

</form>
@include('layouts.includes.delete-modal')
@endsection