<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Pay Group Details<span class="text-danger">*</span></th>
                </h3>
                <form action="{{ route('pay-group-details.create') }}" method="GET">
                    <input type="hidden" value="{{ $payGroup->id }}" name="payGroupId">
                    <form action="{{ route('pay-group-details.create') }}" method="GET">
                        <button type="button" class="add-btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add-modal">
                            <i class="fa fa-plus"></i> Add New Pay Group Detail
                        </button>
                    </form>

                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                <thead>
                                                    <tr role="row" class="thead-light">
                                                        @if($payGroup->applicable_on == 1) <!-- Applicable to Employee Group -->
                                                        <th>Employee Category</th>
                                                        @elseif($payGroup->applicable_on == 2) <!-- Applicable to Grade -->
                                                        <th>Grade</th>
                                                        @endif
                                                        <th>Calculation Method</th>
                                                        <th>Amount</th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($payGroupDetails as $detail)
                                                    <tr>
                                                        @if($payGroup->applicable_on == 1) <!-- Display Employee Category -->
                                                        <td>{{ $detail->employeeGroup->name ?? config('global.null_value') }}</td>
                                                        @elseif($payGroup->applicable_on == 2) <!-- Display Grade -->
                                                        <td>{{ $detail->grade->name ?? config('global.null_value') }}</td>
                                                        @endif
                                                        <td>{{ config('global.calculation_method')[$detail->calculation_method] }}</td>
                                                        <td>{{ $detail->amount }}</td>
                                                        <td>{{ $detail->created_at ? $detail->created_at->format('Y-m-d') : '' }}</td>
                                                        <td>{{ $detail->updated_at ? $detail->updated_at->format('Y-m-d') : '' }}</td>
                                                        <td class="text-center">
                                                            <a href="#" class="edit-btn btn btn-sm btn-rounded btn-outline-success"
                                                                data-url="{{ url('getpaygroupdetail/' . $detail->id) }}"
                                                                data-update-url="{{ url('paymaster/pay-group-details/' . $detail->id) }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>
                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('paymaster/pay-group-details/' . $detail->id) }}">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                        <!-- Pagination Links -->
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div>{{ $payGroupDetails->links() }}</div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Add new pay group -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa;">
            <div class="modal-header">
                <h5 class="modal-title" id="addDetailLabel">Add New Pay Group Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('pay-group-details.store') }}" method="POST" id="add-modal-form">
                    @csrf
                    <input type="hidden" name="mas_pay_group_id" value="{{ $payGroup->id }}">

                    <div id="grade-section" class="mb-3" style="display: none;">
                        <label for="mas_grade_id" class="form-label">Grade <span class="text-danger">*</span></label>
                        <select name="mas_grade_id" id="mas_grade_id" class="form-control">
                            <option value="" disabled selected hidden>Select an option</option>
                            @foreach ($grades as $grade)
                            <option value="{{ $grade->id }}">
                                {{ $grade->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="employee-group-section" class="mb-3" style="display: none;">
                        <label for="mas_employee_group_id" class="form-label">Employee Group <span class="text-danger">*</span></label>
                        <select name="mas_employee_group_id" id="mas_employee_group_id" class="form-control">
                            <option value="" disabled selected hidden>Select an option</option>
                            @foreach ($employeeGroups as $group)
                            <option value="{{ $group->id }}">
                                {{ $group->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="calculation_method" class="form-label">Calculation Method <span class="text-danger">*</span></label>
                        <select name="calculation_method" id="calculation_method" class="form-control">
                            <option value="" disabled selected hidden>Select an option</option>
                            @foreach(config('global.calculation_method') as $key => $value)
                            <option value="{{ $key }}" {{ old('calculation_method') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Add Detail</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Modal -->
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

                    <div class="mb-3 dynamic-field" id="field_employee_category">
                        <label for="employee_category" class="form-label">Employee Category <span class="text-danger">*</span></label>
                        <select class="form-control" id="mas_employee_group_id" name="mas_employee_group_id">
                            <option value="" disabled selected>Select an option</option>
                            @foreach($empCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 dynamic-field" id="field_grade">
                        <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                        <select class="form-control" id="mas_grade_id" name="mas_grade_id">
                            <option value="" disabled selected hidden>Select Grade</option>
                            @foreach ($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 dynamic-field" id="field_calculation_method">
                        <label for="calculation_method" class="form-label">Calculation Method <span class="text-danger">*</span></label>
                        <select class="form-control" id="calculation_method" name="calculation_method">
                            <option value="" disabled selected hidden>Select Calculation Method</option>
                            @foreach (config('global.calculation_method') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 dynamic-field" id="field_amount">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount">
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

    .dynamic-field {
        display: none;
    }
</style>

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applicableOnSelect = document.getElementById('applicable_on');
        const dynamicFields = document.querySelectorAll('.dynamic-field');
        const gradeField = document.getElementById('field_grade');
        const employeeCategoryField = document.getElementById('field_employee_category');
        const calculationMethodField = document.getElementById('field_calculation_method');
        const amountField = document.getElementById('field_amount');

        const toggleFields = (applicableOn) => {
            dynamicFields.forEach(field => {
                field.style.display = 'none'; // Hide all dynamic fields
            });

            // Show fields based on the applicableOn value
            if (applicableOn === '2') { // Grade
                gradeField.style.display = 'block';
                calculationMethodField.style.display = 'block';
                amountField.style.display = 'block';
            } else if (applicableOn === '1') { // Employee Group
                employeeCategoryField.style.display = 'block';
                calculationMethodField.style.display = 'block';
                amountField.style.display = 'block';
            }
        };

        // Trigger field visibility when the select value changes
        applicableOnSelect.addEventListener('change', function() {
            toggleFields(this.value); // Use the value of the select element
        });

        // Trigger field visibility on page load based on the existing value
        toggleFields(applicableOnSelect.value); // Use the value of the select element
    });
</script>

@endpush