@extends('layouts.app')
@section('page-title', 'Edit Office')
@section('content')
<form action="{{ url('master/offices/' . $office->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $office->name) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                        <select name="dzongkhag_id" class="form-control" required>
                            <option value="" disabled>Select Dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag_id', $office->dzongkhag_id) == $dzongkhag->id ? 'selected' : '' }}>
                                {{ $dzongkhag->dzongkhag }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-64">
                        <div class="form-group "></div>
                        <label class="custom-switch">
                            <!-- Hidden input to pass '0' when checkbox is unchecked -->
                            <input type="hidden" name="status[is_active]" value="0">
                            <!-- Checkbox to pass '1' when checked -->
                            <input type="checkbox"
                                name="status[is_active]"
                                class="custom-switch-input form-control form-control-sm"
                                value="1"
                                {{ old('status.is_active', $office->status) == 1 ? 'checked' : '' }} />
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">is Active</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/offices') ,
            'cancelName' => 'CANCEL'
            ])
              
        </div>
    </div>
</form>
@endsection