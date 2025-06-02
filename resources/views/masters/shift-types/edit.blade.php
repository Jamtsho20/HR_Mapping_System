@extends('layouts.app')
@section('page-title', 'Edit Shft Types')
@section('content')
<form action="{{ url('master/shift-types/' . $types->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $types->name) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_time">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="start_time" id="start_time" value="{{ old('start_time', $types->start_time) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_time">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="end_time" id="end_time" value="{{ old('end_time', $types->end_time) }}" required>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/shift-types'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>

    </div>
</form>
@endsection