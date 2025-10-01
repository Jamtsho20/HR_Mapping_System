@extends('layouts.app')
@section('page-title', 'Create New Training Evaluation Types')
@section('content')

<form action="{{ route('training-evaluation-types.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                
                <div class="col-md-4">
                    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('training/training-evaluation-types'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush