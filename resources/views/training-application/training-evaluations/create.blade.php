@extends('layouts.app')
@section('page-title', 'Create New Training Evaluation')
@section('content')

<form action="{{ route('training-application.training-evaluations.store') }}" method="POST" id="evaluation-form">
    @csrf
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-clipboard-list me-2"></i>Training Evaluation Details</h5>
        </div>
        <div class="card-body">
            <div class="row">

                <!-- Evaluation Type -->
                <div class="col-md-6 mb-3">
                    <label for="evaluation_type_id" class="form-label fw-semibold">
                        Evaluation Type <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('evaluation_type_id') is-invalid @enderror"
                        id="evaluation_type_id"
                        name="evaluation_type_id"
                        required>
                        <option value="" disabled selected hidden>Select Evaluation Type</option>
                        @foreach ($evaluationTypes as $type)
                        <option value="{{ $type->id }}" {{ old('evaluation_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('evaluation_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Title -->
                <div class="col-md-12 mb-4">
                    <label for="title" class="form-label fw-semibold">
                        Evaluation Title <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                        class="form-control @error('title') is-invalid @enderror"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="e.g., Post-Training Knowledge Assessment"
                        required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dynamic Sub-Questions Section -->
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-semibold mb-0">
                            Sub Questions <span class="text-danger">*</span>
                        </label>
                    </div>

                    <div id="questions-wrapper">
                        <!-- Initial Question Template -->
                        <div class="question-item card mb-3 border-start border-primary border-3" data-index="0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 text-muted">Question<span class="question-number">#1</span></h6>
                                    <button type="button" class="btn btn-danger btn-sm remove-question" title="Remove Question">
                                        <i class="fa fa-trash me-1"></i>
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <!-- Question Text -->
                                    <div class="col-md-12">
                                        <label class="form-label small">Question Title <span class="text-danger">*</span></label>
                                        <input type="text"
                                            name="questions[0][text]"
                                            class="form-control"
                                            placeholder="Enter your question here"
                                            required>
                                    </div>

                                    <!-- Question Type and Sequence -->
                                    <div class="col-md-6">
                                        <label class="form-label small">Question Type <span class="text-danger">*</span></label>
                                        <select name="questions[0][type]" class="form-select question-type" required>
                                            <option value="">Select Type</option>
                                            <option value="short_answer">Short Answer (Text)</option>
                                            <option value="scale">Scale (Rating)</option>
                                            <option value="option">Multiple Choice</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Sequence Order <span class="text-danger">*</span></label>
                                        <input type="number"
                                            name="questions[0][sequence]"
                                            class="form-control sequence-input"
                                            placeholder="e.g., 1"
                                            min="1"
                                            value="1"
                                            required>
                                    </div>

                                    <!-- Options Section (Hidden by default) -->
                                    <div class="col-md-12 option-fields d-none">
                                        <div class="alert alert-info alert-sm py-2 mb-2">
                                            <i class="fa fa-info-circle me-1"></i>
                                            <small>Add multiple choice options for this question</small>
                                        </div>
                                        <label class="form-label small fw-semibold">Answer Options</label>
                                        <div class="options-wrapper">
                                            <!-- Initial Option -->
                                            <div class="input-group mb-2" style="max-width: 400px;">
                                                <span class="input-group-text">
                                                    <i class="fa fa-circle"></i>
                                                </span>
                                                <input type="text"
                                                    name="questions[0][options][]"
                                                    class="form-control form-control-sm"
                                                    placeholder="Enter option text">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-option"
                                                    title="Remove option">
                                                    <i class="fa fa-trash me-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm add-option mt-2">
                                            <i class="fa fa-plus me-1"></i>Add Option
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Question Button -->
                    <div class="text-center mt-3">
                        <button type="button" id="add-question" class="btn btn-outline-primary">
                            <i class="fa fa-plus-circle me-2"></i>Add Another Question
                        </button>
                    </div>
                </div>

            </div>
        </div>


        <!-- Form Footer -->
        <div class="card-footer bg-light text-center py-3">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE EVALUATION',
            'cancelUrl' => route('training-application.training-evaluations.index'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('questions-wrapper');
        const addButton = document.getElementById('add-question');
        const questionCount = document.getElementById('question-count');
        let questionIndex = 1;

        // Update question count badge
        function updateQuestionCount() {
            const count = wrapper.querySelectorAll('.question-item').length;
            questionCount.textContent = `${count} Question${count !== 1 ? 's' : ''}`;
            updateQuestionNumbers();
        }

        // Update question numbers display
        function updateQuestionNumbers() {
            const items = wrapper.querySelectorAll('.question-item');
            items.forEach((item, index) => {
                const numberSpan = item.querySelector('.question-number');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
            });
        }

        // Auto-update sequence numbers
        function updateSequenceNumbers() {
            const items = wrapper.querySelectorAll('.question-item');
            items.forEach((item, index) => {
                const sequenceInput = item.querySelector('.sequence-input');
                if (sequenceInput) {
                    sequenceInput.value = index + 1;
                }

            });
        }

        // Add new question
        addButton.addEventListener('click', function() {
            const questionHTML = `
            <div class="question-item card mb-3 border-start border-primary border-3" data-index="${questionIndex}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-muted">Question #<span class="question-number">${questionIndex + 1}</span></h6>
                        <button type="button" class="btn btn-danger btn-sm remove-question" title="Remove Question">
                            <i class="fa fa-trash me-1"></i>
                        </button>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small">Question Text <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="questions[${questionIndex}][text]" 
                                   class="form-control" 
                                   placeholder="Enter your question here" 
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Question Type <span class="text-danger">*</span></label>
                            <select name="questions[${questionIndex}][type]" class="form-select question-type" required>
                                <option value="">Select Type</option>
                                <option value="short_answer">Short Answer (Text)</option>
                                <option value="scale">Scale (Rating)</option>
                                <option value="option">Multiple Choice</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Sequence Order <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="questions[${questionIndex}][sequence]" 
                                   class="form-control sequence-input" 
                                   placeholder="e.g., ${questionIndex + 1}" 
                                   min="1"
                                   value="${questionIndex + 1}"
                                   required>
                        </div>

                        <div class="col-md-12 option-fields d-none">
                            <div class="alert alert-info alert-sm py-2 mb-2">
                                <i class="fa fa-info-circle me-1"></i>
                                <small>Add multiple choice options for this question</small>
                            </div>
                            <label class="form-label small fw-semibold">Answer Options</label>
                            <div class="options-wrapper">
                                <div class="input-group mb-2" style="max-width: 400px;">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-check-circle text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           name="questions[${questionIndex}][options][]" 
                                           class="form-control form-control-sm" 
                                           placeholder="Enter option text">
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm remove-option" 
                                            title="Remove option">
                                        <i class="fa fa-trash me-1"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm add-option mt-2">
                                <i class="fa fa-plus me-1"></i>Add Option
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;

            wrapper.insertAdjacentHTML('beforeend', questionHTML);
            questionIndex++;
            updateQuestionCount();
            updateSequenceNumbers();
        });

        // Remove question
        wrapper.addEventListener('click', function(e) {
            if (e.target.closest('.remove-question')) {
                const items = wrapper.querySelectorAll('.question-item');
                if (items.length > 1) {
                    e.target.closest('.question-item').remove();
                    updateQuestionCount();
                    updateSequenceNumbers();
                } else {
                    alert('You must have at least one question.');
                }
            }
        });

        // Toggle options visibility based on question type
        wrapper.addEventListener('change', function(e) {
            if (e.target.classList.contains('question-type')) {
                const container = e.target.closest('.question-item');
                const optionFields = container.querySelector('.option-fields');

                if (e.target.value === 'option') {
                    optionFields.classList.remove('d-none');
                } else {
                    optionFields.classList.add('d-none');
                }
            }
        });

        // Add option
        wrapper.addEventListener('click', function(e) {
            if (e.target.closest('.add-option')) {
                const container = e.target.closest('.question-item');
                const optionsWrapper = container.querySelector('.options-wrapper');
                const index = container.dataset.index;

                const optionHTML = `
                <div class="input-group mb-2" style="max-width: 400px;">
                    <span class="input-group-text bg-light">
                        <i class="fa fa-check-circle text-muted"></i>
                    </span>
                    <input type="text" 
                           name="questions[${index}][options][]" 
                           class="form-control form-control-sm" 
                           placeholder="Enter option text">
                    <button type="button" 
                            class="btn btn-outline-danger btn-sm remove-option" 
                            title="Remove option">
                        <i class="fa fa-trash me-1"></i>
                    </button>
                </div>`;

                optionsWrapper.insertAdjacentHTML('beforeend', optionHTML);
            }
        });

        // Remove option
        wrapper.addEventListener('click', function(e) {
            if (e.target.closest('.remove-option')) {
                const container = e.target.closest('.question-item');
                const optionsWrapper = container.querySelector('.options-wrapper');
                const options = optionsWrapper.querySelectorAll('.input-group');

                if (options.length > 1) {
                    e.target.closest('.input-group').remove();
                } else {
                    alert('You must have at least one option.');
                }
            }
        });

        // Form validation enhancement
        const form = document.getElementById('evaluation-form');
        form.addEventListener('submit', function(e) {
            const optionQuestions = wrapper.querySelectorAll('.question-type');
            let isValid = true;

            optionQuestions.forEach(select => {
                if (select.value === 'option') {
                    const container = select.closest('.question-item');
                    const options = container.querySelectorAll('.options-wrapper input[type="text"]');
                    const filledOptions = Array.from(options).filter(opt => opt.value.trim() !== '');

                    if (filledOptions.length < 2) {
                        alert('Multiple choice questions must have at least 2 options.');
                        isValid = false;
                        return false;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>

<style>
    .question-item {
        transition: all 0.3s ease;
    }

    .question-item:hover {
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .alert-sm {
        font-size: 0.875rem;
    }

    .form-label.small {
        font-size: 0.875rem;
        margin-bottom: 0.375rem;
    }
</style>
@endpush