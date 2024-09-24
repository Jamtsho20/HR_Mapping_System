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
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> CREATE</button>
            <a href="{{ url('master/designations') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
