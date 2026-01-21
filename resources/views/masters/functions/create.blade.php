@extends('layouts.app')

@section('content')
@php
    $pageTitle = 'Create Function';
@endphp

<div class="container-fluid">
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('functions.store') }}">
                @csrf

                {{-- COMPANY SELECTION --}}
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Company *</label>
                        <select name="mas_company_id" class="form-control" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ old('mas_company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mas_company_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- FUNCTION DETAILS --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Function Name *</label>
                        <input type="text" name="name"
                               class="form-control"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Description</label>
                        <input type="text" name="description"
                               class="form-control"
                               value="{{ old('description') }}">
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label>Approved Strength</label>
                        <input type="number" name="approved_strength"
                               class="form-control"
                               value="{{ old('approved_strength', 0) }}" min="0">
                        @error('approved_strength')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label>Current Strength</label>
                        <input type="number" name="current_strength"
                               class="form-control"
                               value="{{ old('current_strength', 0) }}" min="0">
                        @error('current_strength')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- DESIGNATIONS TABLE --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="designations">
                        <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th>Designation Name *</th>
                            <th>Status *</th>
                        </tr>
                        </thead>

                        <tbody id="designation-tbody">
                        @php $index = 0; @endphp

                        {{-- OLD INPUT --}}
                        @if(old('designations'))
                            @foreach(old('designations') as $designation)
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-row btn btn-danger btn-sm">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="designations[{{ $index }}][name]"
                                               class="form-control {{ $errors->has('designations.'.$index.'.name') ? 'is-invalid' : '' }}"
                                               value="{{ $designation['name'] }}" required>
                                        @if($errors->has('designations.'.$index.'.name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('designations.'.$index.'.name') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <select name="designations[{{ $index }}][status]"
                                                class="form-control {{ $errors->has('designations.'.$index.'.status') ? 'is-invalid' : '' }}" required>
                                            <option value="active" {{ $designation['status']=='active'?'selected':'' }}>Active</option>
                                            <option value="inactive" {{ $designation['status']=='inactive'?'selected':'' }}>Inactive</option>
                                        </select>
                                        @if($errors->has('designations.'.$index.'.status'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('designations.'.$index.'.status') }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @php $index++; @endphp
                            @endforeach
                        @else
                            {{-- DEFAULT ROW --}}
                            <tr>
                                <td class="text-center">
                                    <a href="#" class="delete-row btn btn-danger btn-sm">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                                <td>
                                    <input type="text"
                                           name="designations[0][name]"
                                           class="form-control" required>
                                </td>
                                <td>
                                    <select name="designations[0][status]"
                                            class="form-control" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </td>
                            </tr>
                            @php $index = 1; @endphp
                        @endif

                        {{-- ADD BUTTON --}}
                        <tr class="notremove">
                            <td colspan="2"></td>
                            <td class="text-end">
                                <a href="#" class="add-row btn btn-info btn-sm">
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

                {{-- ACTION BUTTONS --}}
                <div class="text-end mt-3">
                    <a href="{{ route('functions.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button class="btn btn-primary">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- JAVASCRIPT --}}
@push('page_scripts')
<script>
let rowIndex = {{ $index }};

document.addEventListener('click', function (e) {

    // ADD ROW
    if (e.target.closest('.add-row')) {
        e.preventDefault();

        const tbody = document.getElementById('designation-tbody');

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center">
                <a href="#" class="delete-row btn btn-danger btn-sm">
                    <i class="fa fa-times"></i>
                </a>
            </td>
            <td>
                <input type="text"
                       name="designations[${rowIndex}][name]"
                       class="form-control" required>
            </td>
            <td>
                <select name="designations[${rowIndex}][status]"
                        class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </td>
        `;

        tbody.querySelector('.notremove').before(tr);
        rowIndex++;
    }

    // DELETE ROW
    if (e.target.closest('.delete-row')) {
        e.preventDefault();
        e.target.closest('tr').remove();
    }
});
</script>
@endpush
@endsection