@extends('layouts.app')
@section('page-title', 'Grade & Steps')
@section('content')
<form action="{{ url('master/grade-steps') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Create Grade & Steps</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Grade Name --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="grade_name">Grade Name *</label>
                        <input type="text" class="form-control" name="grade_name" value="{{ old('grade_name') }}" required>
                    </div>
                </div>

                {{-- Grade Steps Table --}}
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table id="grade-steps" class="table table-bordered table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="20%">Step Name *</th>
                                    <th width="15%">Starting Salary</th>
                                    <th width="12%">Increment</th>
                                    <th width="15%">Mid Salary</th>
                                    <th width="12%">Increment 2</th>
                                    <th width="15%">Ending Salary</th>
                                    <th width="6%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $stepIndex = 0;
                                @endphp
                                
                                @if(old('grade_steps') && count(old('grade_steps')) > 0)
                                    @foreach(old('grade_steps') as $key => $step)
                                    <tr>
                                        <td class="text-center align-middle">{{ $stepIndex + 1 }}</td>
                                        <td>
                                            <input type="text" 
                                                   name="grade_steps[{{ $stepIndex }}][step_name]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $step['step_name'] }}" 
                                                   required>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[{{ $stepIndex }}][starting_salary]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $step['starting_salary'] ?? 0 }}" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[{{ $stepIndex }}][increment]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $step['increment'] ?? 0 }}" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[{{ $stepIndex }}][mid_salary]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $step['mid_salary'] ?? 0 }}" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[{{ $stepIndex }}][increment2]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $step['increment2'] ?? 0 }}" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[{{ $stepIndex }}][ending_salary]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $step['ending_salary'] ?? 0 }}" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="delete-row btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php $stepIndex++; @endphp
                                    @endforeach
                                @else
                                    {{-- Default row --}}
                                    <tr>
                                        <td class="text-center align-middle">1</td>
                                        <td>
                                            <input type="text" 
                                                   name="grade_steps[0][step_name]" 
                                                   class="form-control form-control-sm" 
                                                   required>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[0][starting_salary]" 
                                                   class="form-control form-control-sm" 
                                                   value="0" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[0][increment]" 
                                                   class="form-control form-control-sm" 
                                                   value="0" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[0][mid_salary]" 
                                                   class="form-control form-control-sm" 
                                                   value="0" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[0][increment2]" 
                                                   class="form-control form-control-sm" 
                                                   value="0" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="grade_steps[0][ending_salary]" 
                                                   class="form-control form-control-sm" 
                                                   value="0" 
                                                   min="0" 
                                                   step="0.01">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="delete-row btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php $stepIndex = 1; @endphp
                                @endif

                                {{-- Add new row button --}}
                                <tr id="add-row-container">
                                    <td colspan="8" class="text-end p-2">
                                        <button type="button" id="add-row" class="btn btn-sm btn-info">
                                            <i class="fa fa-plus"></i> Add New Row
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="card-footer text-end">
            <a href="{{ url('master/grade-steps') }}" class="btn btn-secondary">CANCEL</a>
            <button type="submit" class="btn btn-primary">SAVE</button>
        </div>
    </div>
</form>

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowCount = {{ $stepIndex }};
        
        // Add new row
        document.getElementById('add-row').addEventListener('click', function() {
            const tbody = document.querySelector('#grade-steps tbody');
            const addRowContainer = document.getElementById('add-row-container');
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="text-center align-middle">${rowCount + 1}</td>
                <td>
                    <input type="text" 
                           name="grade_steps[${rowCount}][step_name]" 
                           class="form-control form-control-sm" 
                           required>
                </td>
                <td>
                    <input type="number" 
                           name="grade_steps[${rowCount}][starting_salary]" 
                           class="form-control form-control-sm" 
                           value="0" 
                           min="0" 
                           step="0.01">
                </td>
                <td>
                    <input type="number" 
                           name="grade_steps[${rowCount}][increment]" 
                           class="form-control form-control-sm" 
                           value="0" 
                           min="0" 
                           step="0.01">
                </td>
                <td>
                    <input type="number" 
                           name="grade_steps[${rowCount}][mid_salary]" 
                           class="form-control form-control-sm" 
                           value="0" 
                           min="0" 
                           step="0.01">
                </td>
                <td>
                    <input type="number" 
                           name="grade_steps[${rowCount}][increment2]" 
                           class="form-control form-control-sm" 
                           value="0" 
                           min="0" 
                           step="0.01">
                </td>
                <td>
                    <input type="number" 
                           name="grade_steps[${rowCount}][ending_salary]" 
                           class="form-control form-control-sm" 
                           value="0" 
                           min="0" 
                           step="0.01">
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="delete-row btn btn-danger btn-sm">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            `;
            
            tbody.insertBefore(newRow, addRowContainer);
            rowCount++;
            updateRowNumbers();
        });
        
        // Delete row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-row')) {
                const row = e.target.closest('tr');
                if (document.querySelectorAll('#grade-steps tbody tr').length > 2) {
                    row.remove();
                    updateRowNumbers();
                } else {
                    alert('At least one step is required.');
                }
            }
        });
        
        // Update row numbers
        function updateRowNumbers() {
            const rows = document.querySelectorAll('#grade-steps tbody tr');
            rows.forEach((row, index) => {
                if (!row.id.includes('add-row-container')) {
                    const numberCell = row.querySelector('td:first-child');
                    if (numberCell) {
                        numberCell.textContent = index + 1;
                    }
                }
            });
        }
        
        // Auto-calculate salaries if needed
        document.addEventListener('input', function(e) {
            if (e.target.name.includes('[starting_salary]') || 
                e.target.name.includes('[increment]') ||
                e.target.name.includes('[mid_salary]') ||
                e.target.name.includes('[increment2]')) {
                autoCalculateSalary(e.target);
            }
        });
        
        function autoCalculateSalary(input) {
            const row = input.closest('tr');
            const startingSalary = parseFloat(row.querySelector('input[name$="[starting_salary]"]').value) || 0;
            const increment = parseFloat(row.querySelector('input[name$="[increment]"]').value) || 0;
            const midSalary = parseFloat(row.querySelector('input[name$="[mid_salary]"]').value) || 0;
            const increment2 = parseFloat(row.querySelector('input[name$="[increment2]"]').value) || 0;
            
            // Auto-calculate ending salary if needed
            if (midSalary > 0) {
                row.querySelector('input[name$="[ending_salary]"]').value = midSalary + increment2;
            } else if (startingSalary > 0) {
                row.querySelector('input[name$="[mid_salary]"]').value = startingSalary + increment;
                row.querySelector('input[name$="[ending_salary]"]').value = startingSalary + increment + increment2;
            }
        }
    });
</script>
@endpush
@endsection