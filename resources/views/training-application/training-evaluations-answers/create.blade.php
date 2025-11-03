@extends('layouts.app')
@section('page-title', 'Add Evaluation Answers')

@section('content')
<form action="{{ route('training-application.training-evaluations-answers.store') }}" method="POST">
    @csrf

    <!-- Outer Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-gradient-primary text-white border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Add Evaluation Answers</h4>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Step 1: Select Main Question -->
            <div class="form-group mb-4">
                <label for="main_question" class="fw-semibold">Select Evaluation Title <span class="text-danger">*</span></label>
                <select name="main_question" id="main_question" class="form-select border-2" required>
                    <option value="">-- Select Title Question --</option>
                    @foreach($evaluations as $eval)
                        @if(is_null($eval->parent_id) && $eval->children->isNotEmpty())
                            <option value="{{ $eval->id }}">{{ $eval->title }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Step 2: Main Title -->
            <div id="mainQuestionTitle" class="alert alert-info border-0 fw-semibold small mb-4" style="display:none;"></div>

            <!-- Step 3: Sub Questions -->
            <div id="subQuestionsContainer" style="display:none;">
                <h6 class="fw-semibold mb-3">Sub Questions <span class="text-danger">*</span></h6>
                <div id="subQuestionsList" class="d-flex flex-column gap-4"></div>
            </div>
        </div>

        <div class="card-footer bg-white border-top p-4 text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => route('training-application.training-evaluations-answers.index'),
                'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>
@endsection

@push('page_styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #0b62a4 100%);
    }

    .question-card {
        transition: all 0.2s ease;
    }

    .question-card:hover {
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.075);
        border-color: #667eea !important;
    }

    .scale-container {
        transition: all 0.2s ease;
    }

    .scale-container:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .option-label {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .option-label:hover,
    .option-label:has(input:checked) {
        background-color: #f0f4ff !important;
        border-color: #667eea !important;
        box-shadow: 0 0.125rem 0.25rem rgba(102, 126, 234, 0.1);
    }

  .option-label .form-check-input {
        position: static !important;

    }
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .custom-range {
        height: 8px;
        border-radius: 1rem;
    }
</style>
@endpush

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainSelect = document.getElementById('main_question');
    const subContainer = document.getElementById('subQuestionsContainer');
    const subList = document.getElementById('subQuestionsList');
    const mainTitle = document.getElementById('mainQuestionTitle');
    const allEvaluations = @json($evaluations);

    mainSelect.addEventListener('change', function () {
        const selectedId = parseInt(this.value);
        subList.innerHTML = '';
        mainTitle.style.display = 'none';
        mainTitle.textContent = '';

        if (!selectedId) {
            subContainer.style.display = 'none';
            return;
        }

        const mainQuestion = allEvaluations.find(q => q.id === selectedId);

        if (mainQuestion) {
            mainTitle.textContent = mainQuestion.title ?? mainQuestion.question;
            mainTitle.style.display = 'block';

            if (mainQuestion.children && mainQuestion.children.length > 0) {
                subContainer.style.display = 'block';

                mainQuestion.children
                    .sort((a, b) => a.sequence - b.sequence)
                    .forEach(sub => {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('question-card', 'p-4', 'bg-light', 'rounded-3', 'border');

                        let inputField = '';

                        if (sub.question_type === 'short_answer') {
                            inputField = `
                                <textarea name="answers[${sub.id}]" rows="4"
                                    class="form-control border-2 mt-2"
                                    placeholder="Type your answer here..." required></textarea>`;
                        }

                        else if (sub.question_type === 'scale') {
                            inputField = `
                                <div class="scale-container bg-white p-4 rounded-2 border mt-2">
                                    <div class="d-flex justify-content-between mb-3 small text-muted">
                                        <span>Not Confident</span><span>Very Confident</span>
                                    </div>
                                    <input type="range" name="answers[${sub.id}]" min="1" max="10" step="1" value="5"
                                        class="form-range custom-range" required
                                        oninput="this.nextElementSibling.querySelector('.scale-value').textContent = this.value">
                                    <div class="text-center mt-3">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <span class="text-muted small">Selected:</span>
                                            <span class="scale-value fw-bold fs-5 text-primary">5</span>
                                        </div>
                                    </div>
                                       <div class="d-flex justify-content-between text-muted small mt-2">
                                            @for($i = 1; $i <= 10; $i++)
                                                <span>{{ $i }}</span>
                                                @endfor
                                        </div>
                                </div>`;
                        }

                        else if (sub.question_type === 'option' && sub.options && sub.options.length > 0) {
                            inputField = `
                                <div class="options-container d-flex flex-column gap-2 mt-2">
                                    ${sub.options.map((o, i) => `
                                        <label class="option-label d-flex align-items-center p-3 bg-white rounded-2 border">
                                            <input class="form-check-input m-0" type="radio"
                                                name="answers[${sub.id}]"
                                                id="opt-${sub.id}-${i}"
                                                value="${o.option_text}" required>
                                            <span class="flex-grow-1 ms-2">${o.option_text}</span>
                                        </label>
                                    `).join('')}
                                </div>`;
                        }

                        else {
                            inputField = `
                                <textarea name="answers[${sub.id}]" rows="4"
                                    class="form-control border-2 mt-2"
                                    placeholder="Type your answer here..." required></textarea>`;
                        }

                        wrapper.innerHTML = `
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width: 36px; height: 36px; font-size: 1rem;">
                                    ${sub.sequence}
                                </span>
                                <div class="flex-grow-1">
                                    <p class="mb-2 fw-semibold fs-6">${sub.question}</p>
                                </div>
                                   <span class="badge bg-success-light text-success text-capitalize">
                                        <i class="fe fe-tag me-1"></i> ${sub.question_type.replace('_', ' ')}
                                    </span>
                            </div>
                            <div class="answer-section ms-5 ps-3">${inputField}</div>
                        `;

                        subList.appendChild(wrapper);
                    });
            } else {
                subContainer.style.display = 'none';
            }
        }
    });
});
</script>
@endpush
