@extends('layouts.app')
@section('page-title', 'Dzongkhag')
@section('content')
<form action="{{url('master/dzongkhags/'.$dzongkhag->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="dzongkhag">Dzongkhang <span class="text-danger">*</span></label>
                <input type="text" value="{{$dzongkhag->dzongkhag}}" class="form-control" name="dzongkhag">
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/dzongkhags') ,
            'cancelName' => 'CANCEL'
            ])

        </div>
    </div>

</form>
@include('layouts.includes.delete-modal')
@endsection