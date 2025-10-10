@extends('layouts.app')
@section('page-title', 'Evaluation Answers')

@section('buttons')
@if($privileges->create)
<a href="{{ route('training-application.training-evaluations-answers.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Add Answer
</a>
@endif
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                    <thead class="thead-light">
                        <tr>
                            <th>Sl. No</th>
                            <th>Question / Sub-Questions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                        @if(is_null($evaluation->parent_id))
                        <tr>
                            <td>{{ $evaluations->firstItem() + $loop->index }}</td>

                            <!-- Main Question Title -->
                            <td>
                                <div class="d-flex align-items-center justify-content-between">
                                    <strong>{{ $evaluation->question }}</strong>
                                    @if($evaluation->children && $evaluation->children->count() > 0)
                                    <button class="btn btn-sm btn-outline-primary"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#subq-{{ $evaluation->id }}"
                                        aria-expanded="false"
                                        aria-controls="subq-{{ $evaluation->id }}">
                                        <span class="collapse-toggle-icon">+</span>
                                    </button>
                                    @endif
                                </div>

                                {{-- Collapsible Sub-Questions --}}
                                @if($evaluation->children && $evaluation->children->count() > 0)
                                <div class="collapse mt-2" id="subq-{{ $evaluation->id }}">
                                    <ul class="mb-0 ps-3 border-start border-2 border-primary">
                                        @foreach($evaluation->children->sortBy('sequence') as $sub)
                                        <li class="mb-2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>
                                                    <span class="text-muted">(Q.{{ $sub->sequence }})</span>
                                                    {{ $sub->question }}
                                                </span>
                                                @if($sub->answers && $sub->answers->count() > 0)
                                                <button class="btn btn-sm btn-outline-primary"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#answers-{{ $sub->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="answers-{{ $sub->id }}">
                                                    <span class="collapse-toggle-icon">+</span>
                                                </button>
                                                @endif
                                            </div>

                                            {{-- Collapsible Answers --}}
                                            @if($sub->answers && $sub->answers->count() > 0)
                                            <div class="collapse mt-2 ps-3" id="answers-{{ $sub->id }}">
                                                @foreach($sub->answers as $ans)
                                                <div class="d-flex align-items-start mb-2 pb-2 border-bottom">
                                                    <img src="{{ $ans->creator->profile_picture ?? asset('assets/images/no-image.png') }}"
                                                        alt="{{ $ans->creator->name ?? 'User' }}"
                                                        class="rounded-circle me-2"
                                                        width="32" height="32">
                                                    <div>
                                                        <div class="fw-semibold">{{ $ans->answer }}</div>
                                                        <div class="text-muted small">
                                                            — {{ $ans->creator->name ?? 'Unknown' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <div class="text-muted small ps-4">No answers yet</div>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-danger">No Evaluation Answers Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $evaluations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
            const icon = button.querySelector('.collapse-toggle-icon');
            const target = document.querySelector(button.dataset.bsTarget);
            if (target) {
                target.addEventListener('shown.bs.collapse', () => icon.textContent = '−');
                target.addEventListener('hidden.bs.collapse', () => icon.textContent = '+');
            }
        });
    });
</script>
@endpush