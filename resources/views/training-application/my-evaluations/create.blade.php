@extends('layouts.app')
@section('page-title', 'Training Assessment')


@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('training-application.my-evaluations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">

            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $evaluation->title ?? 'Training Assessment' }}</h4>
                            <p class="mb-0 small">{{ $evaluation->evaluationType->name ?? 'General Training' }}</p>
                        </div>
                    </div>

                    <!-- <div class="card-body p-4">
                    <div class="alert alert-info border-0 d-flex align-items-end gap-3">
                        <i class="fe fe-info fs-4"></i>
                        <div>
                            <h6 class="mb-2 fw-semibold">Instructions:</h6>
                            <ul class="mb-0 small ps-3">
                                <li>Answer all questions to submit the assessment</li>
                                <li>For multiple choice, select one answer</li>
                                <li>For scale questions, slide to select your rating</li>
                                <li>All fields marked with <span class="text-danger">*</span> are required</li>
                            </ul>
                        </div>
                    </div>
                </div> -->
                </div>

                <!-- Questions Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div id="subQuestionsList" class="d-flex flex-column gap-4">
                            @foreach($evaluation->children->sortBy('sequence') as $sub)
                            @php
                            $existingAnswer = $sub->answers->first()?->answer;
                            @endphp

                            <div class="question-card p-4 bg-light rounded-3 border">
                                <!-- Question Header -->
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 25px; height: 25px; font-size: 1rem;">
                                        {{ $sub->sequence }}
                                    </span>
                                    <div class="flex-grow-1">
                                        <p class="mb-2 fw-semibold">{{ $sub->question }}</p>

                                    </div>
                                    <span class="badge bg-success-light text-success text-capitalize">
                                        <i class="fe fe-tag me-1"></i>
                                        {{ str_replace('_', ' ', $sub->question_type) }}
                                    </span>
                                </div>

                                <!-- Answer Section -->
                                <div class="answer-section ms-5 ps-3">
                                    @if($sub->question_type === 'short_answer')
                                    <textarea name="answers[{{ $sub->id }}]"
                                        rows="4"
                                        class="form-control border-2"
                                        placeholder="Type your answer here..."
                                        required>{{ old("answers.$sub->id", $existingAnswer) }}</textarea>

                                    @elseif($sub->question_type === 'scale')
                                    <div class="scale-container bg-white p-4 rounded-2 border">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted small">Not Confident</span>
                                            <span class="text-muted small">Very Confident</span>
                                        </div>
                                        <input type="range"
                                            name="answers[{{ $sub->id }}]"
                                            min="1"
                                            max="10"
                                            step="1"
                                            value="{{ old("answers.$sub->id", $existingAnswer ?? 5) }}"
                                            class="form-range custom-range"
                                            required
                                            oninput="this.nextElementSibling.querySelector('.scale-value').textContent = this.value">
                                        <div class="text-center mt-3">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                <span class="text-muted small">Selected:</span>
                                                <span class="scale-value fw-bold fs-5 text-primary">{{ old("answers.$sub->id", $existingAnswer ?? 5) }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between text-muted small mt-2">
                                            @for($i = 1; $i <= 10; $i++)
                                                <span>{{ $i }}</span>
                                                @endfor
                                        </div>
                                    </div>

                                    @elseif($sub->question_type === 'option' && $sub->options->isNotEmpty())
                                    <div class="options-container d-flex flex-column gap-2">
                                        @foreach($sub->options as $i => $opt)
                                        <label class="option-label d-flex align-items-center p-3 bg-white rounded-2 border cursor-pointer hover-option">
                                            <input class="form-check-input m-0"
                                                type="radio"
                                                name="answers[{{ $sub->id }}]"
                                                id="opt-{{ $sub->id }}-{{ $i }}"
                                                value="{{ $opt->option_text }}"
                                                @checked(old("answers.$sub->id", $existingAnswer) === $opt->option_text)
                                            required>
                                            <span class="flex-grow-1">{{ $opt->option_text }}</span>
                                        </label>

                                        @endforeach
                                    </div>

                                    @else
                                    <textarea name="answers[{{ $sub->id }}]"
                                        rows="4"
                                        class="form-control border-2"
                                        placeholder="Type your answer here..."
                                        required>{{ old("answers.$sub->id", $existingAnswer) }}</textarea>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top p-4">
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            @include('layouts.includes.buttons', [
                            'cancelName' => 'Cancel',
                            'buttonName' => 'Submit Assessment',
                            'cancelUrl' => route('training-application.my-evaluations.index')
                            ])
                        </div>
                    </div>

                </div>
        </form>
    </div>
</div>
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

    .option-label .form-check-input {
        position: static !important;

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

    .option-label input[type="radio"]:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .custom-range {
        height: 8px;
        border-radius: 1rem;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .scale-container {
        transition: all 0.2s ease;
    }

    .scale-container:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .option-label {
        gap: 20px;
    }
</style>
@endpush

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scroll to validation errors
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // Add form submission confirmation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let allFilled = true;

                requiredFields.forEach(field => {
                    if (!field.value || (field.type === 'radio' && !form.querySelector(`[name="${field.name}"]:checked`))) {
                        allFilled = false;
                    }
                });

                if (!allFilled) {
                    e.preventDefault();
                    alert('Please answer all required questions before submitting.');
                    return false;
                }
            });
        }
    });
</script>
@endpush