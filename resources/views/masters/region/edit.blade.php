@extends('layouts.app')
@section('page-title', 'Region')
@section('content')

<form action="{{url('master/regions/' .$region->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="region">Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="region" value="{{ old('region', $region->name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                <label for="mas_employee_id">Regional manager</label>
                    <select class="form-control" name="mas_employee_id" required>
                        <option value="" hidden selected disabled>Select your option</option>
                        @foreach(concateEmpNameUserName() as $employee)
                        <option value="{{ $employee->id }}" {{ $region->mas_employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('master/regions') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
<!--Region Location Details -->

@include('masters.region-location.index', ['regionLocations' => $regionLocations])
@include('layouts.includes.delete-modal')
@endsection
