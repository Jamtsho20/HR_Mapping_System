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
                    <div class="form-group">
                        <label for="rm_email">RM Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="rm_email" value="{{ old('rm_email', $region->rm_email) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="rm_phone">RM Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="rm_phone" value="{{ old('rm_phone', $region->rm_phone) }}">
                    </div>
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
@include('masters.region-location.index', ['regionLocation' => $regionLocation])


@include('layouts.includes.delete-modal')
@endsection
