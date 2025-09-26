@extends('layouts.app')
@section('page-title', 'Edit Training Evaluation Types')
@section('content')
<form action="{{ url('training/training-evaluation-types/' . $types->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $types->name) }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('training/training-evaluation-types'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>

    </div>
</form>
@endsection