@extends('layouts.app')
@section('page-title', 'Add Evaluation Answers')

@section('content')
<form action="{{ route('training-application.training-evaluations-answers.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-body">
            <!-- Step 1: Select Main Question -->
            <div class="form-group">
                <label for="main_question">Select Evaluation Title <span class="text-danger">*</span></label>
                <select name="main_question" id="main_question" class="form-control" required>
                    <option value="">-- Select Title Question --</option>
                    @foreach($evaluations as $eval)
                        @if(is_null($eval->parent_id) && $eval->children->isNotEmpty())
                            <option value="{{ $eval->id }}">{{ $eval->question }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Step 2: Display Sub-questions dynamically -->
            <div id="subQuestionsContainer" class="mt-4" style="display:none;">
                 <label for="sub-questions">Sub Question <span class="text-danger">*</span></label>
                <div id="subQuestionsList"></div>
            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => route('training-application.training-evaluations-answers.index'),
                'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>
@endsection

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainSelect = document.getElementById('main_question');
    const subContainer = document.getElementById('subQuestionsContainer');
    const subList = document.getElementById('subQuestionsList');

    const allEvaluations = @json($evaluations);

    mainSelect.addEventListener('change', function () {
        const selectedId = parseInt(this.value);
        subList.innerHTML = ''; // clear previous

        if (!selectedId) {
            subContainer.style.display = 'none';
            return;
        }

        // Find selected main question and its children
        const mainQuestion = allEvaluations.find(q => q.id === selectedId);
        if (mainQuestion && mainQuestion.children && mainQuestion.children.length > 0) {
            subContainer.style.display = 'block';

            mainQuestion.children
                .sort((a, b) => a.sequence - b.sequence)
                .forEach(sub => {
                    const item = document.createElement('div');
                    item.classList.add('form-group', 'mb-3');
                    item.innerHTML = `
                        <label class="fw-semibold">
                            ${sub.sequence ? sub.sequence + '. ' : ''}${sub.question}
                            <span class="text-danger">*</span>
                        </label>
                        <textarea name="answers[${sub.id}]" rows="3" class="form-control" placeholder="Enter your answer..." required></textarea>
                    `;
                    subList.appendChild(item);
                });
        } else {
            subContainer.style.display = 'none';
        }
    });
});
</script>
@endpush
