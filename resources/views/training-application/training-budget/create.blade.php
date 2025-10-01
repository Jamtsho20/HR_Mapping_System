@extends('layouts.app')
@section('page-title', 'Create New Training Budget Allocations')
@section('content')

<form action="{{ route('training-budget.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="training_list_id">Training List <span class="text-danger">*</span></label>
                    <select class="form-control" id="training_list_id" name="training_list_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($trainingLists as $list)
                        <option value="{{ $list->id }}" {{ old('training_list_id') == $list->id ? 'selected' : '' }}>
                            {{ $list->id }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('training/training-budget'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush