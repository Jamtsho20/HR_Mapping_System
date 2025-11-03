@extends('layouts.app')
@section('page-title', 'Edit Training Evaluation')

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('training-application.training-evaluations.update', $evaluation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <!-- Basic Information Section -->
                    <div class="section-header mb-4">
                        <h5 class="fw-semibold mb-3">
                            <i class="fe fe-info me-2 text-primary"></i>
                            Basic Information
                        </h5>
                    </div>

                    <div class="row">
                        <!-- Training List -->
                        <div class="col-md-6 mb-4">
                            <label for="training_list_id" class="form-label fw-semibold">
                                Training List <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="training_list_id" name="training_list_id" required>
                                <option value="" disabled hidden>Select Training</option>
                                @foreach ($trainingLists as $list)
                                <option value="{{ $list->id }}" {{ $evaluation->training_list_id == $list->id ? 'selected' : '' }}>
                                    {{ $list->title }}
                                </option>
                                @endforeach
                            </select>
                            @error('training_list_id')
                            <small class="text-danger">
                                <i class="fe fe-alert-circle me-1"></i>{{ $message }}
                            </small>
                            @enderror
                        </div>

                        <!-- Evaluation Type -->
                        <div class="col-md-6 mb-4">
                            <label for="evaluation_type_id" class="form-label fw-semibold">
                                Evaluation Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="evaluation_type_id" name="evaluation_type_id" required>
                                <option value="" disabled hidden>Select Evaluation Type</option>
                                @foreach ($evaluationTypes as $type)
                                <option value="{{ $type->id }}" {{ $evaluation->evaluation_type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('evaluation_type_id')
                            <small class="text-danger">
                                <i class="fe fe-alert-circle me-1"></i>{{ $message }}
                            </small>
                            @enderror
                        </div>

                        <!-- Evaluation Title -->
                        <div class="col-md-12 mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Evaluation Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', $evaluation->title) }}"
                                placeholder="e.g., Customer Service Training Assessment" required>
                            @error('title')
                            <small class="text-danger">
                                <i class="fe fe-alert-circle me-1"></i>{{ $message }}
                            </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fe fe-list text-primary"></i>
                            <h5 class="mb-0 fw-semibold">Sub Questions</h5>
                            <span class="badge bg-primary-light text-primary" id="question-count">
                                {{ $evaluation->children->count() }} {{ Str::plural('Question', $evaluation->children->count()) }}
                            </span>
                        </div>
                      
                    </div>

                </div>

                <div class="card-body p-4">
                    <!-- <div class="alert alert-info border-0 d-flex align-items-start gap-3 mb-4">
                        <i class="fe fe-info fs-5"></i>
                        <div>
                            <h6 class="mb-2 fw-semibold">Question Guidelines:</h6>
                            <ul class="mb-0 small ps-3">
                                <li><strong>Short Answer:</strong> Open text response field</li>
                                <li><strong>Scale:</strong> Rating scale from 1-10</li>
                                <li><strong>Options:</strong> Multiple choice with custom options</li>
                                <li>Set sequence numbers to control the order of questions</li>
                            </ul>
                        </div>
                    </div> -->

                    <div id="questions-wrapper" class="d-flex flex-column gap-3">
                        @forelse($evaluation->children->sortBy('sequence') as $index => $sub)
                        <div class="question-item border rounded-3 p-4 bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <h6 class="mb-0 fw-semibold">Question {{ $index + 1 }}</h6>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-question">
                                    <i class="fe fe-trash-2 me-1"></i>
                                </button>
                            </div>

                            <div class="row g-3">
                                <!-- Question Text -->
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold text-muted">Question Text <span class="text-danger">*</span></label>
                                    <input type="text" name="questions[{{ $index }}][text]" class="form-control"
                                        placeholder="Enter your question here"
                                        value="{{ old('questions.'.$index.'.text', $sub->question) }}" required>
                                </div>

                                <!-- Question Type -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Question Type <span class="text-danger">*</span></label>
                                    <select name="questions[{{ $index }}][type]" class="form-select question-type" required>
                                        <option value="">Select Type</option>
                                        <option value="short_answer" {{ $sub->question_type === 'short_answer' ? 'selected' : '' }}>
                                            <i class="fe fe-edit-3"></i> Short Answer
                                        </option>
                                        <option value="scale" {{ $sub->question_type === 'scale' ? 'selected' : '' }}>
                                            <i class="fe fe-sliders"></i> Scale (1–10)
                                        </option>
                                        <option value="option" {{ $sub->question_type === 'option' ? 'selected' : '' }}>
                                            <i class="fe fe-check-square"></i> Multiple Choice
                                        </option>
                                    </select>
                                </div>

                                <!-- Sequence -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Sequence Order <span class="text-danger">*</span></label>
                                    <input type="number" name="questions[{{ $index }}][sequence]" class="form-control"
                                        placeholder="1, 2, 3..."
                                        value="{{ old('questions.'.$index.'.sequence', $sub->sequence) }}"
                                        min="1" required>
                                </div>

                                <!-- Options Section (for multiple choice) -->
                                <div class="col-md-12 option-fields {{ $sub->question_type === 'option' ? '' : 'd-none' }}">
                                    <label class="form-label small fw-semibold text-muted">
                                        <i class="fe fe-check-square me-1"></i>
                                        Answer Options
                                    </label>
                                    <div class="options-wrapper bg-white rounded-2 p-3 border">
                                        @foreach($sub->options->sortBy('sequence') as $optIndex => $opt)
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-light">
                                                <i class="fe fe-circle"></i>
                                            </span>
                                            <input type="text"
                                                name="questions[{{ $index }}][options][]"
                                                class="form-control"
                                                value="{{ old('questions.'.$index.'.options.'.$optIndex, $opt->option_text) }}"
                                                placeholder="Enter option text">
                                            <button type="button" class="btn-sm btn-danger remove-option">
                                                <i class="fe fe-trash-2 me-1"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-option">
                                        <i class="fe fe-plus me-1"></i> Add Option
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="question-item border rounded-3 p-4 bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        1
                                    </span>
                                    <h6 class="mb-0 fw-semibold">Question 1</h6>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-question">
                                    <i class="fe fe-trash-2 me-1"></i>
                                </button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold text-muted">Question Text <span class="text-danger">*</span></label>
                                    <input type="text" name="questions[0][text]" class="form-control" placeholder="Enter your question here" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Question Type <span class="text-danger">*</span></label>
                                    <select name="questions[0][type]" class="form-select question-type" required>
                                        <option value="">Select Type</option>
                                        <option value="short_answer">Short Answer</option>
                                        <option value="scale">Scale (1–10)</option>
                                        <option value="option">Multiple Choice</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Sequence Order <span class="text-danger">*</span></label>
                                    <input type="number" name="questions[0][sequence]" class="form-control" placeholder="1, 2, 3..." min="1" required>
                                </div>
                                <div class="col-md-12 option-fields d-none">
                                    <label class="form-label small fw-semibold text-muted">Answer Options</label>
                                    <div class="options-wrapper bg-white rounded-2 p-3 border"></div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-option">
                                        <i class="fe fe-plus me-1"></i> Add Option
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforelse
                  
                    </div>
                </div>
                             <div class="text-center mt-3 mb-4">
                        <button type="button" id="add-question" class="btn btn-outline-primary">
                            <i class="fa fa-plus-circle me-2"></i>Add Another Question
                        </button>
                    </div>

                <div class="card-footer bg-white border-top p-4">
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fe fe-check me-2"></i>
                            Update
                        </button>
                        <a href="{{ route('training-application.training-evaluations.index') }}" class="btn btn-danger px-4">
                            <i class="fe fe-x me-2"></i>
                            Cancel
                        </a>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection

@push('page_styles')
<style>
    .question-item {
        transition: all 0.2s ease;
    }

    .question-item:hover {
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .options-wrapper {
        max-height: 300px;
        overflow-y: auto;
    }

    .input-group-text {
        border-right: 0;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #0066aa;
    }

    .section-header {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.75rem;
    }

    .badge {
        font-weight: 600;
    }
</style>
@endpush

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('questions-wrapper');
        const questionCount = document.getElementById('question-count');
        let questionIndex = wrapper.children.length;

        function updateQuestionCount() {
            const count = wrapper.children.length;
            questionCount.textContent = `${count} ${count === 1 ? 'Question' : 'Questions'}`;
            updateQuestionNumbers();
        }

        function updateQuestionNumbers() {
            const questions = wrapper.querySelectorAll('.question-item');
            questions.forEach((item, idx) => {
                const badge = item.querySelector('.badge');
                const heading = item.querySelector('h6');
                if (badge) badge.textContent = idx + 1;
                if (heading) heading.textContent = `Question ${idx + 1}`;
            });
        }

        // Add new question
        document.getElementById('add-question').addEventListener('click', function() {
            const html = `
        <div class="question-item border rounded-3 p-4 bg-light">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        ${questionIndex + 1}
                    </span>
                    <h6 class="mb-0 fw-semibold">Question ${questionIndex + 1}</h6>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-question">
                    <i class="fe fe-trash-2 me-1"></i>
                </button>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label small fw-semibold text-muted">Question Text <span class="text-danger">*</span></label>
                    <input type="text" name="questions[${questionIndex}][text]" class="form-control" placeholder="Enter your question here" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Question Type <span class="text-danger">*</span></label>
                    <select name="questions[${questionIndex}][type]" class="form-select question-type" required>
                        <option value="">Select Type</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="scale">Scale (1–10)</option>
                        <option value="option">Multiple Choice</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Sequence Order <span class="text-danger">*</span></label>
                    <input type="number" name="questions[${questionIndex}][sequence]" class="form-control" placeholder="1, 2, 3..." min="1" required>
                </div>
                <div class="col-md-12 option-fields d-none">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fe fe-check-square me-1"></i>
                        Answer Options
                    </label>
                    <div class="options-wrapper bg-white rounded-2 p-3 border"></div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-option">
                        <i class="fe fe-plus me-1"></i> Add Option
                    </button>
                </div>
            </div>
        </div>`;
            wrapper.insertAdjacentHTML('beforeend', html);
            questionIndex++;
            updateQuestionCount();

            // Scroll to new question
            const newQuestion = wrapper.lastElementChild;
            newQuestion.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });

        // Event delegation for dynamic elements
        wrapper.addEventListener('click', function(e) {
            // Remove question
            if (e.target.closest('.remove-question')) {
                if (wrapper.children.length > 1) {
                    e.target.closest('.question-item').remove();
                    updateQuestionCount();
                } else {
                    alert('You must have at least one question.');
                }
            }

            // Add option
            if (e.target.closest('.add-option')) {
                const container = e.target.closest('.question-item');
                const optionsWrapper = container.querySelector('.options-wrapper');
                const index = Array.from(wrapper.children).indexOf(container);

                const optionHtml = `
                <div class="input-group mb-2">
                    <span class="input-group-text bg-light">
                        <i class="fe fe-circle"></i>
                    </span>
                    <input type="text" name="questions[${index}][options][]" class="form-control" placeholder="Enter option text">
                    <button type="button" class="btn btn-outline-danger remove-option">
                        <i class="fe fe-x"></i>
                    </button>
                </div>
            `;
                optionsWrapper.insertAdjacentHTML('beforeend', optionHtml);
            }

            // Remove option
            if (e.target.closest('.remove-option')) {
                const optionsWrapper = e.target.closest('.options-wrapper');
                if (optionsWrapper.children.length > 1) {
                    e.target.closest('.input-group').remove();
                } else {
                    alert('You must have at least one option for multiple choice questions.');
                }
            }
        });

        // Toggle option fields based on type
        wrapper.addEventListener('change', function(e) {
            if (e.target.classList.contains('question-type')) {
                const container = e.target.closest('.question-item');
                const optionFields = container.querySelector('.option-fields');
                const optionsWrapper = container.querySelector('.options-wrapper');

                if (e.target.value === 'option') {
                    optionFields.classList.remove('d-none');

                    // Add default option if empty
                    if (optionsWrapper.children.length === 0) {
                        const index = Array.from(wrapper.children).indexOf(container);
                        const optionHtml = `
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light">
                                <i class="fe fe-circle"></i>
                            </span>
                            <input type="text" name="questions[${index}][options][]" class="form-control" placeholder="Enter option text">
                            <button type="button" class="btn btn-outline-danger remove-option">
                                <i class="fe fe-x"></i>
                            </button>
                        </div>
                    `;
                        optionsWrapper.insertAdjacentHTML('beforeend', optionHtml);
                    }
                } else {
                    optionFields.classList.add('d-none');
                }
            }
        });

        // Initial question count
        updateQuestionCount();
    });
</script>
@endpush