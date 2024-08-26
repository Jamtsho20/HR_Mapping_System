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
                <div class="form-group col-md-4">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="status[is_active]" value="0">
                        <!-- Checkbox to pass '1' when checked -->
                        <input type="checkbox"
                            name="status[is_active]"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active', $region->status) == 1 ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
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

@include('masters.region-location.index')


<!-- Edit Modal-->

    <div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="editDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa;">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailLabel">Edit Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit-modal-form">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="region" name="region" value="{{ $region->name }}" disabled>
                        <input type="hidden" name="mas_region_id" value="{{ $region->id }}">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $region->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dzongkhag" class="form-label">Dzongkhag <span class="text-danger">*</span></label>
                        <select class="form-control" id="dzongkhag" name="mas_dzongkhag_id">
                            <option value="" disabled selected hidden>Select Dzongkhag</option>
                            @foreach ($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag', $region->mas_dzongkhag_id) == $dzongkhag->id ? 'selected' : '' }}>{{ $dzongkhag->dzongkhag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

    <!-- Custom backdrop style -->
    <style>
        .modal-backdrop {
            background-color: rgba(255, 255, 255, 0.7) !important;
        }
    </style>

    @include('layouts.includes.delete-modal')
    @endsection