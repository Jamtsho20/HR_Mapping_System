@extends('layouts.app')

@section('content')
@php
    $pageTitle = 'Edit Function';
@endphp

<div class="container-fluid">
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('functions.update', $function->id) }}">
                @csrf
                @method('PUT')

                <!-- Hidden field to track deleted designations -->
                <input type="hidden" name="deleted_designations" id="deleted_designations" value="">

                <!-- COMPANY SELECTION -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Company *</label>
                        <select name="mas_company_id" class="form-control" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ old('mas_company_id', $function->mas_company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mas_company_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Function Details -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Function Name *</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $function->name) }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description', $function->description) }}">
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Approved Strength</label>
                        <input type="number" name="approved_strength"
                               class="form-control @error('approved_strength') is-invalid @enderror"
                               value="{{ old('approved_strength', $function->approved_strength) }}" min="0">
                        @error('approved_strength')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Current Strength</label>
                        <input type="number" name="current_strength"
                               class="form-control @error('current_strength') is-invalid @enderror"
                               value="{{ old('current_strength', $function->current_strength) }}" min="0">
                        @error('current_strength')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $function->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $function->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Designations Table -->
                <div class="table-responsive mt-4">
                    <table id="designations" class="table table-border table-striped table-sm">
                        <thead>
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th>Designation Name *</th>
                                <th>Status *</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $oldDesignations = old('designations', $function->designations->toArray());
                            @endphp

                            @forelse($oldDesignations as $key => $designation)
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @if(isset($designation['id']))
                                            <input type="hidden" name="designations[{{ $key }}][id]" value="{{ $designation['id'] }}">
                                        @endif
                                        <input type="text" name="designations[{{ $key }}][name]"
                                               class="form-control @error('designations.'.$key.'.name') is-invalid @enderror"
                                               value="{{ old('designations.'.$key.'.name', $designation['name'] ?? '') }}" required>
                                        @error('designations.'.$key.'.name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <select name="designations[{{ $key }}][status]" 
                                                class="form-control @error('designations.'.$key.'.status') is-invalid @enderror" required>
                                            <option value="active" {{ old('designations.'.$key.'.status', $designation['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('designations.'.$key.'.status', $designation['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('designations.'.$key.'.status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <input type="text" name="designations[new_0][name]" 
                                               class="form-control" required>
                                    </td>
                                    <td>
                                        <select name="designations[new_0][status]" class="form-control" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Add new designation button -->
                            <tr class="notremovefornew">
                                <td colspan="2"></td>
                                <td class="text-right">
                                    <a href="#" class="add-table-row btn btn-sm btn-info">
                                        <i class="fa fa-plus"></i> Add Designation
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @error('designations')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Form buttons -->
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('functions.index') }}" class="btn btn-secondary me-2">
                        Cancel
                    </a>
                    <button class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('page_scripts')
<script>
    let deletedIds = [];
    let newRowIndex = 0;
    
    // Initialize newRowIndex based on existing rows
    document.addEventListener('DOMContentLoaded', function() {
        const existingRows = document.querySelectorAll('input[name^="designations["]');
        const newRowsCount = Array.from(existingRows).filter(input => 
            input.name.includes('[new_')
        ).length;
        newRowIndex = newRowsCount;
    });

    // Delete row
    document.addEventListener('click', function(e) {
        if(e.target.closest('.delete-table-row')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            const idInput = row.querySelector('input[name*="[id]"]');
            if(idInput && idInput.value) {
                deletedIds.push(idInput.value);
                document.getElementById('deleted_designations').value = deletedIds.join(',');
            }
            row.remove();
        }
    });

    // Add new row
    document.addEventListener('click', function(e) {
        if(e.target.closest('.add-table-row')) {
            e.preventDefault();
            newRowIndex++;
            const tbody = document.querySelector('#designations tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="text-center">
                    <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="designations[new_${newRowIndex}][name]" class="form-control" required>
                </td>
                <td>
                    <select name="designations[new_${newRowIndex}][status]" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </td>
            `;
            tbody.querySelector('.notremovefornew').before(newRow);
        }
    });
</script>
@endpush

@endsection