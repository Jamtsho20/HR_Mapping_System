@extends('layouts.app')
@section('page-title', 'Training Evaluations')

@section('buttons')
@if ($privileges->create)
<a href="{{ route('training-application.training-evaluations.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> New Evaluation Question
</a>
@endif
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fe fe-check-circle me-2 fs-5"></i>
                <strong>Success!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Evaluation Type Dropdown -->
        <div class="card">
            <div class="card-body">

                <form method="GET" id="evaluationFilterForm">
                    <div class="col-md-4 form-group">
                        <label class="form-label small fw-semibold text-muted">Evaluation Type</label>
                        <select name="evaluation_type_id" class="form-select" onchange="document.getElementById('evaluationFilterForm').submit()">
                            <option value="" disabled selected hidden>Select Evaluation Type</option>
                            @foreach($evaluationTypes as $type)
                            <option value="{{ $type->id }}" {{ request('evaluation_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>

            </div>
        </div>


        <!-- Evaluations Card -->
        @if($hasFilter)
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @forelse($evaluations as $evaluation)
                @if(is_null($evaluation->parent_id))
                @php
                $totalQuestions = $evaluation->children->count();
                $assignedCount = $evaluation->assignedEmployees->count();
                @endphp

                <div class="evaluation-management-card mb-4 border rounded-3 overflow-hidden shadow-sm">
                    <!-- Card Header -->
                    <div class="card-header-custom bg-gradient-subtle p-4">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div class="flex-grow-1">

                                <h5 class="mb-2 fw-bold">
                                    <span
                                        class="badge bg-primary rounded-circle text-white d-inline-flex align-items-center justify-content-center"
                                        style="width:25px; height:25px; font-size:14px;">
                                        {{ $evaluations->firstItem() + $loop->index }}
                                    </span>
                                    {{ $evaluation->title }}
                                </h5>


                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span>
                                        <i class="fe fe-user me-1"></i>
                                        By {{ $evaluation->creator->name ?? 'Unknown' }}
                                    </span>
                                    <span>
                                        <i class="fe fe-file-text me-1"></i>
                                        {{ $totalQuestions }} {{ Str::plural('Question', $totalQuestions) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 flex-wrap">
                                @if ($privileges->edit)
                                <a href="{{ route('training-application.training-evaluations.edit', $evaluation->id) }}"
                                    class="btn btn-sm btn-success">
                                    <i class="fe fe-edit me-1"></i>
                                    Edit
                                </a>
                                @endif
                                @if ($privileges->delete)
                                <button class="delete-btn btn btn-sm btn-danger"
                                    data-url="{{ route('training-application.training-evaluations.destroy', $evaluation->id) }}">
                                    <i class="fe fe-trash-2 me-1"></i>
                                    Delete
                                </button>
                                @endif
                                <button class="btn btn-sm btn-primary assign-btn"
                                    data-evaluation-id="{{ $evaluation->id }}"
                                    data-assigned='@json($evaluation->assignedEmployees->pluck("id"))'>
                                    <i class="fe fe-user-plus me-1"></i>
                                    Assign Staff
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <!-- Assigned Staff Section -->
                        @if($assignedCount > 0)
                        <div class="assigned-section mb-4">
                            <h6 class="fw-semibold mb-3 text-muted">
                                <i class="fe fe-users me-1"></i>
                                Assigned Staff ({{ $assignedCount }})
                            </h6>
                            <div class="assigned-users d-flex flex-wrap gap-2" data-evaluation-id="{{ $evaluation->id }}">
                                @foreach($evaluation->assignedEmployees as $emp)
                                <span class="badge bg-info-light text-info assigned-badge px-3 py-2" data-emp-id="{{ $emp->id }}">
                                    <i class="fe fe-user me-1"></i>
                                    {{ $emp->name }}
                                    <button type="button" class="remove-assigned ms-2 btn btn-sm p-0 text-danger border-0 bg-transparent"
                                        style="font-size: 1rem; line-height: 1; font-weight: bold;"
                                        aria-label="Remove">&times;</button>
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning mb-4 d-flex align-items-center gap-2">
                            <i class="fe fe-alert-circle"></i>
                            <span>No staff members assigned to this evaluation yet</span>
                        </div>
                        @endif

                        <!-- Questions Section -->
                        @if($totalQuestions > 0)
                        <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 toggle-subq"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#subq-{{ $evaluation->id }}"
                            aria-expanded="false">
                            <i class="fe fe-list"></i>
                            <span>View Questions ({{ $totalQuestions }})</span>
                            <span class="toggle-icon ms-auto">+</span>
                        </button>

                        <div class="collapse mt-3" id="subq-{{ $evaluation->id }}">
                            <div class="questions-list bg-light rounded-3 p-3">
                                @foreach($evaluation->children->sortBy('sequence') as $sub)
                                <div class="question-detail-item p-3 mb-3 bg-white rounded-2 border">
                                    <div class="d-flex align-items-start gap-3">
                                        <span class="badge bg-primary rounded-circle flex-shrink-0"
                                            style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                            {{ $sub->sequence }}
                                        </span>
                                        <div class="flex-grow-1">
                                            <p class="mb-2 fw-semibold">{{ $sub->question }}</p>


                                            <!-- Show Options/Scale Info -->
                                            @if($sub->question_type === 'option' && $sub->options->count() > 0)
                                            <div class="mt-2 ms-3">
                                                <p class="small text-muted mb-1 fw-semibold">Options:</p>
                                                <ul class="small text-muted mb-0 ps-3">
                                                    @foreach($sub->options->sortBy('sequence') as $opt)
                                                    <li>{{ $opt->option_text }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @elseif($sub->question_type === 'scale')
                                            <div class="mt-2 ms-3">
                                                <p class="small text-muted mb-0">
                                                    <i class="fe fe-sliders me-1"></i>
                                                    Scale question (1-10)
                                                </p>
                                            </div>
                                            @elseif($sub->question_type === 'short_answer')
                                            <div class="mt-2 ms-3">
                                                <p class="small text-muted mb-0">
                                                    <i class="fe fe-edit-3 me-1"></i>
                                                    Short text answer
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                        <span class="badge bg-success-light text-success text-capitalize">
                                            <i class="fe fe-tag me-1"></i>
                                            {{ str_replace('_', ' ', $sub->question_type) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info mb-0">
                            <i class="fe fe-info me-2"></i>
                            No questions added yet
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @empty
                <div class="text-center py-5">
                    <i class="fe fe-inbox text-muted mb-3" style="font-size: 4rem;"></i>
                    <h5 class="text-muted">No Training Evaluations Found</h5>
                    <p class="text-muted">Create your first evaluation to get started</p>
                </div>
                @endforelse

                <div class="mt-4">
                    {{ $evaluations->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@include('layouts.includes.delete-modal')

<!-- Alert Message Modal -->
<div class="modal fade" id="alertMessage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-opacity-10 border-0">
                <h5 class="modal-title">
                    Confirm Action
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="alert-message mb-0">Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">
                    <i class="fe fe-x"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmRemoveBtn">
                    <i class="fe fe-check"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Assign Employees Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form id="assignForm" method="POST" action="{{ route('training-application.training-evaluations.assign') }}">
                @csrf
                <input type="hidden" name="evaluation_id" id="modal_evaluation_id">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fe fe-user-plus me-2"></i>
                        Assign Staff Members
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="employeeSearch" class="form-control" placeholder="Search staff by name or username...">
                    </div>

                    <div id="employeeList" class="px-5" style="max-height:400px; overflow-y:auto;">
                        @foreach($employees as $emp)
                        <div class="form-check mb-2 p-2 employee-item" data-search="{{ strtolower($emp->name . ' ' . $emp->username) }}">
                            <input class="form-check-input employee-checkbox" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" id="emp{{ $emp->id }}">
                            <label class="form-check-label d-flex align-items-center gap-2 cursor-pointer" for="emp{{ $emp->id }}">
                                <i class="fe fe-user text-muted"></i>
                                <span>{{ $emp->name }}</span>
                                <span class="text-muted small">({{ $emp->username }})</span>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-left align-items-center gap-4 mt-3">
                        <button type="button" class="btn btn-sm btn-danger" id="clearAll">
                            <i class="fa fa-trash me-1"></i>
                            Clear All
                        </button>
                        <span class="text-muted medium">
                            <span id="selectedCount">0</span> staff member(s) selected
                        </span>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fe fe-check me-1"></i>
                        Assign
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fe fe-x me-1"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page_styles')
<style>
    .bg-gradient-admin {
        background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
    }

    .bg-gradient-subtle {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .evaluation-management-card {
        transition: all 0.3s ease;
    }

    .evaluation-management-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .card-header-custom {
        border-bottom: 2px solid #dee2e6;
    }

    .question-detail-item {
        transition: all 0.2s ease;
    }

    .question-detail-item:hover {
        background-color: #f8f9fa !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .assigned-badge {
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .assigned-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
    }

    .remove-assigned:hover {
        transform: scale(1.2);
    }

    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .bg-info-light {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .toggle-icon {
        transition: transform 0.3s ease;
        font-weight: bold;
    }

    .toggle-subq[aria-expanded="true"] .toggle-icon {
        transform: rotate(180deg);
    }

    .employee-item {
        transition: all 0.2s ease;
    }

    .employee-item:hover {
        background-color: #f8f9fa;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    #selectedCount {
        font-weight: 600;
        color: #0b62a4;
    }

    .modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
    }
</style>
@endpush

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const assignModal = new bootstrap.Modal(document.getElementById('assignModal'));
        const alertModal = new bootstrap.Modal(document.getElementById('alertMessage'));
        const employeeSearch = document.getElementById('employeeSearch');
        const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
        const modalEvaluationId = document.getElementById('modal_evaluation_id');
        const selectedCount = document.getElementById('selectedCount');
        const clearAllBtn = document.getElementById('clearAll');

        // Update selected count
        function updateSelectedCount() {
            const count = document.querySelectorAll('.employee-checkbox:checked').length;
            selectedCount.textContent = count;
        }

        // Clear all selections
        clearAllBtn.addEventListener('click', () => {
            employeeCheckboxes.forEach(cb => cb.checked = false);
            updateSelectedCount();
        });

        // Open assign modal
        document.querySelectorAll('.assign-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const evaluationId = btn.dataset.evaluationId;
                const assignedIds = JSON.parse(btn.dataset.assigned);
                modalEvaluationId.value = evaluationId;

                employeeCheckboxes.forEach(cb => {
                    cb.checked = assignedIds.includes(parseInt(cb.value));
                });

                updateSelectedCount();
                assignModal.show();
            });
        });

        // Filter employees
        employeeSearch.addEventListener('input', () => {
            const filter = employeeSearch.value.toLowerCase();
            document.querySelectorAll('.employee-item').forEach(item => {
                const searchText = item.dataset.search;
                item.style.display = searchText.includes(filter) ? 'block' : 'none';
            });
        });

        // Update count on checkbox change
        employeeCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        // Toggle questions icon
        document.querySelectorAll('.toggle-subq').forEach(btn => {
            const target = document.querySelector(btn.dataset.bsTarget);
            if (target) {
                target.addEventListener('shown.bs.collapse', () => btn.querySelector('.toggle-icon').textContent = '−');
                target.addEventListener('hidden.bs.collapse', () => btn.querySelector('.toggle-icon').textContent = '+');
            }
        });

        // Remove assigned user
        document.querySelectorAll('.assigned-users').forEach(container => {
            container.addEventListener('click', e => {
                if (e.target.classList.contains('remove-assigned')) {
                    const badge = e.target.closest('.assigned-badge');
                    const empId = badge.dataset.empId;
                    const evaluationId = container.dataset.evaluationId;

                    const alertMessage = document.querySelector('.alert-message');
                    const confirmBtn = document.getElementById('confirmRemoveBtn');

                    alertMessage.textContent = 'Are you sure you want to remove this staff member from this evaluation?';
                    alertModal.show();

                    confirmBtn.replaceWith(confirmBtn.cloneNode(true));
                    const newConfirmBtn = document.getElementById('confirmRemoveBtn');

                    newConfirmBtn.addEventListener('click', () => {
                        fetch(`{{ route('training-application.training-evaluations.unassign') }}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    evaluation_id: evaluationId,
                                    employee_id: empId
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    badge.remove();
                                    const remainingBadges = container.querySelectorAll('.assigned-badge');
                                    if (remainingBadges.length === 0) {
                                        const assignedSection = container.closest('.assigned-section');
                                        assignedSection.innerHTML = `
                                            <div class="alert alert-info d-flex align-items-center gap-2">
                                                <i class="fe fe-alert-circle"></i>
                                                <span>No staff members assigned to this evaluation yet</span>
                                            </div>`;
                                    }
                                    location.reload();
                                } else {
                                    alert('Failed to remove assignment. Please try again.');
                                }
                            })
                            .catch(() => {
                                alert('An error occurred. Please try again.');
                            })
                            .finally(() => {
                                alertModal.hide();
                            });
                    });
                }
            });
        });
    });
</script>
@endpush