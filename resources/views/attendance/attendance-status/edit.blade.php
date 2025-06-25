@extends('layouts.app')
@section('page-title', 'Edit Attendance Status')
@section('content')

<form action="{{ route('attendance-status.update', $attendanceStatus->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            <div class="row">

                {{-- Code --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code', $attendanceStatus->code) }}" required>
                        @error('code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <input type="text" name="description" id="description"
                            class="form-control @error('description') is-invalid @enderror"
                            value="{{ old('description', $attendanceStatus->description) }}" required>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Color --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="color">Color <span class="text-danger">*</span></label>
                        <input type="color" name="color" id="color"
                            class="form-control form-control-color @error('color') is-invalid @enderror"
                            value="{{ old('color', $attendanceStatus->color ?? '#2ec158') }}" title="Choose color">
                        @error('color')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div> {{-- .row --}}
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'UPDATE',
                'cancelUrl' => url('attendance/attendance-status'),
                'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush
