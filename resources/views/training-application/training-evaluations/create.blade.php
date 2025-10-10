@extends('layouts.app')
@section('page-title', 'Create New Training Evaluation')
@section('content')

<form action="{{ route('training-application.training-evaluations.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">

                <!-- Training List -->
                <div class="col-md-6 mb-3">
                    <label for="training_list_id">Training List <span class="text-danger">*</span></label>
                    <select class="form-control" id="training_list_id" name="training_list_id" required>
                        <option value="" disabled selected hidden>Select Training</option>
                        @foreach ($trainingLists as $list)
                            <option value="{{ $list->id }}" {{ old('training_list_id') == $list->id ? 'selected' : '' }}>
                                {{ $list->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('training_list_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Evaluation Type -->
                <div class="col-md-6 mb-3">
                    <label for="evaluation_type_id">Evaluation Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="evaluation_type_id" name="evaluation_type_id" required>
                        <option value="" disabled selected hidden>Select Evaluation Type</option>
                        @foreach ($evaluationTypes as $type)
                            <option value="{{ $type->id }}" {{ old('evaluation_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('evaluation_type_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Question Title -->
                <div class="col-md-12 mb-4">
                    <label for="title">Question Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="{{ old('title') }}" placeholder="e.g., Trainer Evaluation" required>
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Dynamic Sub-Questions -->
                <div class="col-md-12">
                    <label>Sub Questions <span class="text-danger">*</span></label>

                    <div id="questions-wrapper">
                        <div class="question-item mb-3 border p-3">
                            <div class="row align-items-center">
                                <div class="col-md-10">
                                    <input type="text-box" name="questions[]" class="form-control" placeholder="Enter sub question" required>
                                </div>
                                <div class="col-md-1">
                                    <input type="number" name="sequences[]" class="form-control" placeholder="Sequence" required>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-question"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-question" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fa fa-plus"></i> Add Another Question
                    </button>
                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
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
    document.addEventListener('DOMContentLoaded', function () {
        const addButton = document.getElementById('add-question');
        const wrapper = document.getElementById('questions-wrapper');

        addButton.addEventListener('click', function () {
            const newQuestion = document.createElement('div');
            newQuestion.classList.add('question-item', 'mb-3', 'border', 'p-3');
            newQuestion.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <input type="text" name="questions[]" class="form-control" placeholder="Enter sub question" required>
                    </div>
                    <div class="col-md-1">
                        <input type="number" name="sequences[]" class="form-control" placeholder="Sequence" required>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-danger btn-sm remove-question"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            wrapper.appendChild(newQuestion);
        });

        // Remove question item
        wrapper.addEventListener('click', function (e) {
            if (e.target.closest('.remove-question')) {
                e.target.closest('.question-item').remove();
            }
        });
    });
</script>
@endpush
