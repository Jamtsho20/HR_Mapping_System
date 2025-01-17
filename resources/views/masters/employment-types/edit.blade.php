@extends('layouts.app')
@section('page-title', 'Employment Types')
@section('content')

<form action="{{ url('master/employment-types/' . $employmentType->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Employment Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{$employmentType->name}}" name="name">
            </div>
            <div class="form-group">
                <label for="">Remarks </label>
                <textarea name="remarks" class="form-control" rows="4">{{$employmentType->remarks}}</textarea>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/employment-types'),
            'cancelName' => 'CANCEL'
            ])
           
        </div>
    </div>

</form>

@endsection