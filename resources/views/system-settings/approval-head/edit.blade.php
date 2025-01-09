@extends('layouts.app')
@section('page-title', 'Edit Approval Head')
@section('content')
<form action="{{ url('system-setting/approval-head/' . $approval->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $approval->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="description" value="{{ old('description', $approval->description) }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('system-setting/approval-head'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>
@endsection