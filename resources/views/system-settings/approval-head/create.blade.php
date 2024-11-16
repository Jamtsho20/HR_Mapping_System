@extends('layouts.app')
@section('page-title', 'Create Approval Head')
@section('content')

<form action="{{ route('approval-head.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="description" value="{{ old('description') }}" required>
                    </div>
                </div>
            </div>
            

            <div class="card-footer text-center">
                @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => url('system-setting/approval-head') ,
                'cancelName' => 'CANCEL'
                ])

            </div>
        </div>
    </div>
</form>




@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush