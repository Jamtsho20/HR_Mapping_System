@extends('layouts.app')
@section('page-title', 'Designation')
@section('content')

<form action="{{ url('master/designations') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- First Column -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Designation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/designations') ,
            'cancelName' => 'CANCEL'
            ])
         
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection