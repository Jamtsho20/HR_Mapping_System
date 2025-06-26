@extends('layouts.app')
@section('page-title', 'Create New Offices')
@section('content')

<form action="{{ route('offices.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dzongkhag">Dzongkhag <span class="text-danger">*</span></label>
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="{{ old('longitude') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="{{ old('latitude') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="radius">Radius</label>
                        <input type="number" class="form-control" name="radius" value="{{ old('radius') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="status[is_active]" value="0">
                        <!-- Checkbox to pass '1' when checked, and retain old value -->
                        <input type="checkbox"
                            name="status[is_active]"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active') == '1' ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>

            <div class="card-footer text-center">
                @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => url('master/offices') ,
                'cancelName' => 'CANCEL'
                ])
            
            </div>
        </div>
    </div>
</form>




@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush