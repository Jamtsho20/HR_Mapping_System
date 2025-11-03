@extends('layouts.app')
@section('page-title', 'Training Evaluation Answers')

@section('buttons')
@if($privileges->create)
<a href="{{ route('training-application.training-evaluations-answers.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Add Answer
</a>
@endif
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">

            <div class="card-body p-4">
                @forelse($evaluations as $evaluation)
                @if(is_null($evaluation->parent_id))
                @php
                $totalQuestions = $evaluation->children->count();
                $answeredQuestions = $evaluation->children->filter(fn($sub) => $sub->answers->count() > 0)->count();
                $totalResponses = $evaluation->children->sum(fn($sub) => $sub->answers->count());
                $completionRate = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
                @endphp

                <div class="evaluation-card mb-4 border rounded-3 overflow-hidden shadow-sm">
                    <!-- Evaluation Header -->
                    <div class="card-gradient-header bg-gradient-admin p-4 text-white">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fe fe-file-text" style="font-size: 1.5rem;"></i>
                                    <h5 class="mb-0 fw-bold">{{ $evaluation->title }}</h5>
                                </div>
                                <span class="badge bg-white bg-opacity-25 text-white">
                                    {{ $evaluation->evaluationType->name ?? 'General' }}
                                </span>
                                <span class="badge bg-white bg-opacity-25 text-white">
                                    {{ $evaluation->trainingList->title ?? 'General' }}
                                </span>

                            </div>

                            <div class="d-flex gap-3">
                                <div class="text-center">
                                    <div class="fs-4 fw-bold">{{ $totalQuestions }}</div>
                                    <div class="small opacity-75">Questions</div>
                                </div>
                                <div class="text-center">
                                    <div class="fs-4 fw-bold">{{ $totalResponses }}</div>
                                    <div class="small opacity-75">Responses</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Body -->
                    <div class="card-body p-4">
                        @if($evaluation->children && $evaluation->children->count() > 0)
                        <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-3"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#subq-{{ $evaluation->id }}"
                            aria-expanded="false"
                            aria-controls="subq-{{ $evaluation->id }}">
                            <i class="fe fe-list"></i>
                            <span>View Questions & Answers</span>
                            <span class="collapse-toggle-icon ms-auto">+</span>
                        </button>

                        <div class="collapse" id="subq-{{ $evaluation->id }}">
                            <div class="questions-container d-flex flex-column gap-3">
                                @foreach($evaluation->children->sortBy('sequence') as $sub)
                                @php
                                $answerCount = $sub->answers->count();
                                @endphp

                                <div class="question-item p-4 bg-light rounded-3 border">
                                    <!-- Question Header -->
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 25px; height: 25px; font-size: 1.1rem;">
                                            {{ $sub->sequence }}
                                        </span>
                                        <div class="flex-grow-1">
                                            <p class="mb-2 fw-semibold">{{ $sub->question }}</p>

                                            <span class="badge bg-info-light text-info">
                                                <i class="fe fe-users me-1"></i>
                                                {{ $answerCount }} {{ Str::plural('Response', $answerCount) }}
                                            </span>

                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-2">
                                            <span class="badge bg-success-light text-success text-capitalize">
                                                <i class="fe fe-tag me-1"></i>
                                                {{ str_replace('_', ' ', $sub->question_type) }}
                                            </span>
                                            @if($answerCount > 0)
                                            <button class="btn btn-sm btn-outline-primary"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#answers-{{ $sub->id }}"
                                                aria-expanded="false"
                                                aria-controls="answers-{{ $sub->id }}">
                                                View Answers
                                                <span class="collapse-toggle-icon-ans ms-1">+</span>
                                            </button>
                                            @endif
                                        </div>

                                    </div>

                                    <!-- Answers Section -->
                                    @if($sub->answers && $sub->answers->count() > 0)
                                    <div class="collapse mt-3" id="answers-{{ $sub->id }}">
                                        <div class="answers-container bg-white rounded-2 border p-3">
                                            <h6 class="mb-3 fw-semibold text-muted">
                                                <i class="fe fe-message-square me-1"></i>
                                                Staff Responses ({{ $answerCount }})
                                            </h6>
                                            <div class="d-flex flex-column gap-3">
                                                @foreach($sub->answers as $ans)
                                                <div class="answer-item d-flex gap-3 p-3 bg-light rounded-2 border-start border-4 border-primary">
                                                    <img src="{{ $ans->creator->profile_picture ?? asset('assets/images/no-image.png') }}"
                                                        alt="{{ $ans->creator->name ?? 'User' }}"
                                                        class="rounded-circle flex-shrink-0"
                                                        width="48" height="48"
                                                        style="object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div>
                                                                <div class="fw-semibold text-dark">{{ $ans->creator->name ?? 'Unknown User' }}</div>
                                                                <div class="text-muted small">
                                                                    <i class="fe fe-clock me-1"></i>
                                                                    {{ $ans->created_at->format('M d, Y h:i A') }}
                                                                    <span class="text-muted">• {{ $ans->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                            @if($sub->question_type === 'scale')
                                                            <div class="badge bg-primary-light text-primary px-3 py-2 fs-6">
                                                                <i class="fe fe-star me-1"></i>
                                                                {{ $ans->answer }}/10
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="answer-text p-3 bg-white rounded-2 border">
                                                            <p class="mb-0" style="word-break: break-word; white-space: pre-wrap;">{{ $ans->answer }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="alert alert-primary mb-0 d-flex align-items-center gap-2">
                                        <i class="fe fe-alert-circle"></i>
                                        <span class="text-muted">No responses yet for this question</span>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info mb-0">
                            <i class="fe fe-info me-2"></i>
                            No questions available for this evaluation
                        </div>
                        @endif
                    </div>

                    <!-- Evaluation Footer with Stats -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted small">
                                <i class="fe fe-calendar me-1"></i>
                                Created: {{ $evaluation->created_at->format('M d, Y') }}
                            </div>
                            @php
                            $totalUniqueUsers = $evaluation->children
                            ->flatMap(fn($sub) => $sub->answers)
                            ->pluck('created_by') // get all user IDs who answered
                            ->unique()
                            ->count();
                            @endphp
                            <span class="badge bg-info-light text-info">
                                <i class="fe fe-users me-1"></i>
                                {{ $totalUniqueUsers }} {{ Str::plural('User', $totalUniqueUsers) }} responded
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                @empty
                <div class="text-center py-5">
                    <i class="fe fe-inbox text-muted mb-3" style="font-size: 4rem;"></i>
                    <h5 class="text-muted">No Evaluation Answers Found</h5>
                    <p class="text-muted">No staff members have submitted their training evaluations yet.</p>
                </div>
                @endforelse

                <div class="mt-4">
                    {{ $evaluations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_styles')
<style>
    .bg-gradient-admin {
        background: linear-gradient(135deg, #667eea 0%, #0b62a4 100%);
    }

    .card-gradient-header {
        background: linear-gradient(135deg, #667eea 0%, #0b62a4 100%);
    }

    .evaluation-card {
        transition: all 0.3s ease;
    }

    .evaluation-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .question-item {
        transition: all 0.2s ease;
    }

    .question-item:hover {
        background-color: #f8f9fa !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .answer-item {
        transition: all 0.2s ease;
    }

    .answer-item:hover {
        background-color: #ffffff !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .bg-info-light {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .border-4 {
        border-width: 4px !important;
    }

    .collapse-toggle-icon,
    .collapse-toggle-icon-ans {
        transition: transform 0.2s ease;
        display: inline-block;
        font-weight: bold;
    }

    .answer-text {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .progress {
        border-radius: 1rem;
    }

    .progress-bar {
        border-radius: 1rem;
        transition: width 0.6s ease;
    }

    @media (max-width: 768px) {
        .card-gradient-header {
            padding: 1rem !important;
        }

        .question-item {
            padding: 1rem !important;
        }

        .answer-item {
            flex-direction: column;
        }

        .answer-item img {
            width: 40px !important;
            height: 40px !important;
        }
    }
</style>
@endpush

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const setupCollapseIcons = (selector, initialIcon, shownIcon) => {
            document.querySelectorAll(selector).forEach(button => {
                const icon = button.querySelector('.collapse-toggle-icon, .collapse-toggle-icon-ans');
                const target = document.querySelector(button.dataset.bsTarget);
                if (target && icon) {
                    icon.textContent = target.classList.contains('show') ? shownIcon : initialIcon;
                    target.addEventListener('shown.bs.collapse', () => {
                        icon.textContent = shownIcon;
                        icon.style.transform = 'rotate(180deg)';
                    });
                    target.addEventListener('hidden.bs.collapse', () => {
                        icon.textContent = initialIcon;
                        icon.style.transform = 'rotate(0deg)';
                    });
                }
            });
        };

        setupCollapseIcons('[data-bs-toggle="collapse"][data-bs-target^="#subq"]', '+', '−');
        setupCollapseIcons('[data-bs-toggle="collapse"][data-bs-target^="#answers"]', '+', '−');

        // Add smooth scroll animation for collapse
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(() => {
                    const target = document.querySelector(this.dataset.bsTarget);
                    if (target && target.classList.contains('show')) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                }, 350);
            });
        });
    });
</script>
@endpush