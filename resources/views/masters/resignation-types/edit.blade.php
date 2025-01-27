@extends('layouts.app')
@section('page-title', 'Resignation Types')
@section('content')
<form action="{{url('master/resignation-types/' .$resignation->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">
        <div class="card-body">
            <div class="row">
                <div class="card-content">
                    <div class="form-group">
                        <label for="name">Resignation Type *</label>
                        <input type="text" class="form-control" value="{{$resignation->name}}" name="resignation_type" required>
                    </div>
                    <div class="form-group">
                        <label for="">Remarks </label>
                        <textarea name="remarks" class="form-control" rows="4">{{$resignation->remarks}}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('layouts.includes.buttons', [
                'buttonName' => 'UPDATE',
                'cancelUrl' =>url('master/resignation-types'),
                'cancelName' => 'CANCEL'
                ])
             
            </div>
        </div>
    </div>

</form>
@include('layouts.includes.delete-modal')
@endsection