@extends('layouts.app')
@section('page-title', 'My Assigned Evaluations')
@section('content')
<div class="row">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">&times;</button>
    </div>
    @endif
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-end">
                    <div class="badge bg-primary-subtle text-primary fs-6 px-3 py-4">
                        Total: {{ $evaluations->total() }} Evaluation{{ $evaluations->total() !== 1 ? 's' : '' }}
                    </div>
                </div>
                @forelse($evaluations as $evaluation)
                @if(is_null($evaluation->parent_id))
                @php
                $user = auth()->user();
                $totalQuestions = $evaluation->children->count();
                $answeredQuestions = $evaluation->children->filter(fn($sub) => $sub->answers->where('created_by', $user->id)->count() > 0)->count();
                $isCompleted = ($answeredQuestions > 0 && $answeredQuestions == $totalQuestions);
                $statusClass = $isCompleted ? 'success' : 'warning';
                $statusText = $isCompleted ? 'Completed' : 'Pending';
                @endphp

                <div class="training-card border mb-4 shadow-sm transition">
                    <div class="card-gradient-header bg-gradient-primary p-4 text-white">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="flex-grow-2">
                                <h5 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                    <i class="fe fe-book-open" style="font-size: 1.5rem;"></i>
                                    {{ $evaluation->title }}
                                </h5>
                                <span class="badge bg-white bg-opacity-25 text-white small">
                                    {{ $evaluation->evaluationType->name ?? 'General Training' }}
                                </span>
                                <span class="badge bg-white bg-opacity-25 text-white small">
                                    {{ $evaluation->trainingList->title ?? 'General Training' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <i class="fe fe-clock"></i>
                                <span class="small">Created: {{ $evaluation->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <i class="fe fe-file-text"></i>
                                <span class="small">Questions: {{ $totalQuestions }}</span>
                            </div>
                            <!-- @if($totalQuestions > 0)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fe fe-check-circle text-{{ $statusClass }}"></i>
                                    <span class="small">Progress: {{ $answeredQuestions }} / {{ $totalQuestions }} answered</span>
                                </div>
                                @endif -->
                        </div>

                        @if($evaluation->children && $evaluation->children->count() > 0)
                        <div class="border-top pt-3 mb-3">
                            <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#subq-{{ $evaluation->id }}"
                                aria-expanded="false"
                                aria-controls="subq-{{ $evaluation->id }}">
                                <i class="fe fe-list"></i>
                                <span>View Questions</span>
                                <span class="collapse-toggle-icon ms-auto">+</span>
                            </button>

                            <div class="collapse mt-3" id="subq-{{ $evaluation->id }}">
                                <div class="bg-light p-3">
                                    @foreach($evaluation->children->sortBy('sequence') as $sub)
                                    <div class="question-item p-3 mb-3 bg-white rounded-2 border">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="badge bg-primary rounded-circle flex-shrink-0"
                                                        style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                                        {{ $sub->sequence }}
                                                    </span>
                                                    <p class="mb-0 fw-semibold">{{ $sub->question }}</p>
                                                </div>

                                            </div>

                                            @php
                                            $userAnswerCount = $sub->answers->where('created_by', auth()->id())->count();
                                            @endphp
                                            @if($userAnswerCount > 0)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-info-light">Answered ({{ $userAnswerCount }})</span>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#answers-{{ $sub->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="answers-{{ $sub->id }}">
                                                    <span class="collapse-toggle-icon-ans">+</span>
                                                </button>
                                            </div>
                                            @endif
                                        </div>

                                        @php
                                        $userAnswers = $sub->answers->where('created_by', auth()->id());
                                        @endphp
                                        @if($userAnswers->count() > 0)
                                        <div class="collapse mt-2" id="answers-{{ $sub->id }}">
                                            <div class="border-top pt-2">
                                                @foreach($userAnswers as $ans)
                                                <div class="d-flex gap-2 mb-2 p-2 bg-light">
                                                    <img src="{{ $ans->creator->profile_picture ?? asset('assets/images/no-image.png') }}"
                                                        alt="{{ $ans->creator->name ?? 'User' }}"
                                                        class="rounded-circle flex-shrink-0"
                                                        width="32" height="32">
                                                    <div class="flex-grow-1">
                                                        <div class="text-wrap" style="word-break: break-word;">{{ $ans->answer }}</div>
                                                        <div class="text-muted small">
                                                            — {{ $ans->creator->name ?? 'Unknown' }} ({{ $ans->created_at->diffForHumans() }})
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge bg-{{ $statusClass }}-light text-{{ $statusClass }} px-3 py-4 flex-grow-1">
                                <i class="fe fe-{{ $isCompleted ? 'check-circle' : 'clock' }} me-1"></i>
                                {{ $statusText }}
                            </span>

                            <a href="{{ route('training-application.my-evaluations.create', $evaluation->id) }}"
                                class="btn btn-primary btn-sm ">
                                <i class="fe fe-{{ $answeredQuestions > 0 ? 'edit' : 'plus' }} me-1"></i>
                                {{ $answeredQuestions > 0 ? 'Edit Answers' : 'Start Assessment' }}
                            </a>

                            @if($answeredQuestions > 0)
                            <button type="button"
                                class="btn btn-danger btn-sm delete-btn"
                                data-url="{{ route('training-application.my-evaluations.destroy', $evaluation->id) }}">
                                <i class="fe fe-trash-2 me-1"></i>
                                Delete
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @empty
                <div class="text-center py-5">
                    <i class="fe fe-inbox text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Assigned Evaluations Found</h5>
                    <p class="text-muted">You don't have any training evaluations assigned yet.</p>
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

@include('layouts.includes.delete-modal')

@push('page_styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #0b62a4 100%);
    }

    .card-gradient-header {
        background: linear-gradient(135deg, #667eea 0%, #0b62a4 100%);
    }

    .training-card {
        transition: all 0.3s ease;
    }

    .training-card:hover {
        transform: translateY(-2px);
    }

    .hover-shadow-lg:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }

    .transition {
        transition: all 0.3s ease;
    }

    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .bg-info-light {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }

    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .question-item {
        transition: all 0.2s ease;
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
                    target.addEventListener('shown.bs.collapse', () => icon.textContent = shownIcon);
                    target.addEventListener('hidden.bs.collapse', () => icon.textContent = initialIcon);
                }
            });
        };
        setupCollapseIcons('[data-bs-toggle="collapse"][data-bs-target^="#subq"]', '+', '−');
        setupCollapseIcons('[data-bs-toggle="collapse"][data-bs-target^="#answers"]', '+', '−');
    });
</script>
@endpush