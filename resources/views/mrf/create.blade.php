@extends('layouts.app')

@section('page-title', 'Create Manpower Requisition (MRF)')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ url('mrf/lists') }}">
                        @csrf

                        <div class="row">
                            {{-- Requisition Number (auto-generated or readonly) --}}
                            <div class="col-md-6 form-group">
                                <label>Requisition Number</label>
                                <input type="text" class="form-control" value="{{ $nextRequisitionNumber ?? 'AUTO-GENERATED' }}" readonly>
                            </div>

                            {{-- Date of Requisition --}}
                            <div class="col-md-6 form-group">
                                <label>Date of Requisition <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_requisition"
                                    class="form-control"
                                    value="{{ old('date_of_requisition', date('Y-m-d')) }}"
                                    required>
                                @error('date_of_requisition')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- MAS Function --}}
                            <div class="col-md-6 form-group">
                                <label>Function <span class="text-danger">*</span></label>
                                <select name="mas_function_id" id="function_id" class="form-control" required>
                                    <option value="">-- Select Function --</option>
                                    @foreach($functions as $function)
                                    <option value="{{ $function->id }}"
                                        data-approved="{{ $function->approved_strength }}"
                                        data-current="{{ $function->current_strength }}">
                                        {{ $function->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('mas_function_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Department --}}
                            <div class="col-md-6 form-group">
                                <label>Department</label>
                                <select name="mas_department_id" class="form-control">
                                    <option value="">-- Select Department --</option>
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('mas_department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('mas_department_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Section --}}
                            <div class="col-md-6 form-group">
                                <label>Section</label>
                                <select name="mas_section_id" class="form-control">
                                    <option value="">-- Select Section --</option>
                                    @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ old('mas_section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('mas_section_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Designation --}}
                            <div class="col-md-6 form-group">
                                <label>Designation <span class="text-danger">*</span></label>
                                <select name="designation_id" id="designation_id" class="form-control" required>
                                    <option value="">-- Select Designation --</option>
                                    {{-- Options will be populated dynamically --}}
                                </select>
                                @error('designation_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Employment Type --}}
                            <div class="col-md-6 form-group">
                                <label>Employment Type <span class="text-danger">*</span></label>
                                <select name="employment_type_id" class="form-control" required>
                                    <option value="">-- Select Employment Type --</option>
                                    @foreach ($employmentTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('employment_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('employment_type_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Location --}}
                            <div class="col-md-6 form-group">
                                <label>Location <span class="text-danger">*</span></label>
                                <input type="text" name="location"
                                    class="form-control"
                                    value="{{ old('location') }}"
                                    placeholder="Enter work location"
                                    required>
                                @error('location')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Experience --}}
                            <div class="col-md-6 form-group">
                                <label>Experience</label>
                                <input type="text" name="experience"
                                    class="form-control"
                                    value="{{ old('experience') }}"
                                    placeholder="e.g., 3-5 years">
                                @error('experience')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Number of Vacancies --}}
                            <div class="col-md-6 form-group">
                                <label>Number of Vacancies <span class="text-danger">*</span></label>
                                <input type="number" name="vacancies" id="vacancies"
                                    class="form-control"
                                    value="{{ old('vacancies') }}"
                                    min="1"
                                    max="0"
                                    required>
                                @error('vacancies')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- MAS Grade --}}
                            <div class="col-md-4 form-group">
                                <label for="grade_id">Grade <span class="text-danger">*</span></label>
                                <select name="mas_grade_id" id="grade_id" class="form-control" required>
                                    <option value="" disabled selected hidden>Select Grade</option>
                                    @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                                @error('mas_grade_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- MAS Grade Step --}}
                            <div class="col-md-4 form-group">
                                <label for="grade_step_id">Grade Step <span class="text-danger">*</span></label>
                                <select name="mas_grade_step_id" id="grade_step_id" class="form-control" required>
                                    <option value="" disabled selected hidden>Select Grade Step</option>
                                    {{-- Steps will be populated dynamically --}}
                                </select>
                                @error('mas_grade_step_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Pay Scale --}}
                            <div class="col-md-4 form-group">
                                <label for="pay_scale">Pay Scale</label>
                                <input type="text" id="pay_scale" class="form-control" value="" disabled>
                            </div>

                            {{-- Basic Pay --}}
                            <div class="col-md-4 form-group">
                                <label for="basic_pay">Basic Pay <span class="text-danger">*</span></label>
                                <input type="text" id="basic_pay" name="basic_pay" class="form-control" readonly>
                            </div>


                            {{-- MRF Type --}}
                            <div class="col-md-6 form-group">
                                <label>MRF Type <span class="text-danger">*</span></label>
                                <select name="mrf_type" class="form-control" required>
                                    <option value="">-- Select MRF Type --</option>
                                    <option value="new" {{ old('mrf_type') == 'new' ? 'selected' : '' }}>New Position</option>
                                    <option value="replacement" {{ old('mrf_type') == 'replacement' ? 'selected' : '' }}>Replacement</option>
                                </select>
                                @error('mrf_type')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Job Description --}}
                            <div class="col-md-12 form-group">
                                <label>Job Description <span class="text-danger">*</span></label>
                                <textarea name="job_description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter detailed job description"
                                    required>{{ old('job_description') }}</textarea>
                                @error('job_description')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Reason --}}
                            <div class="col-md-12 form-group">
                                <label>Reason <span class="text-danger">*</span></label>
                                <textarea name="reason"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter reason for manpower requisition"
                                    required>{{ old('reason') }}</textarea>
                                @error('reason')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Remarks --}}
                            <div class="col-md-12 form-group">
                                <label>Remarks</label>
                                <textarea name="remarks"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Any additional remarks">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Status (readonly) --}}
                            <div class="col-md-6 form-group">
                                <label>Status</label>
                                <input type="text" class="form-control" value="Pending" readonly>
                                <input type="hidden" name="status" value="pending">
                            </div>

                            {{-- Requested By (auto-filled) --}}
                            <div class="col-md-6 form-group">
                                <label>Requested By</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                <input type="hidden" name="requested_by" value="{{ auth()->id() }}">
                            </div>

                        </div>

                        <div class="text-right mt-4">
                            <a href="{{ url('mrf/lists') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Submit MRF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection