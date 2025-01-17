@extends('layouts.app')
@section('page-title', 'Advance/Loan')
@section('content')

<form action="{{url('master/advance-loans/'.$advance->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="short_name">Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{$advance->name}}" required="required">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/advance-loans') ,
            'cancelName' => 'CANCEL'
            ])

        </div>
</form>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')



@endpush