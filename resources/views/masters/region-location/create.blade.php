@extends('layouts.app')
@section('page-title', 'Create Region Location')
@section('content')

<form action="{{ route('region-location.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <input type="hidden" class="form-control" name="mas_region_id" value="" required>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="region_name">Region Name<span class="text-danger">*</span></label></label>
                        <input type="text" class="form-control" name="region_name" id="region_name" value="{{ old('region_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="region">Region <span class="text-danger">*</span></label></label>
                        <input type="Text" class="form-control" name="region" id="region" value="{{ old('region') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="dzongkhag">Dzongkhag <span class="text-danger">*</span></label></label>
                        <select class="form-control" name="dzongkhag" id="dzongkhag" required>
                            <option value="">Select Dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag') == $dzongkhag->id ? 'selected' : '' }}>
                                    {{ $dzongkhag->dzongkhag }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
            <a href="" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection