@extends('layouts.app')
@section('page-title', 'Gewog')
@section('content')

<form action="{{ url('master/gewogs') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mas_dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                        <select class="form-control" name="mas_dzongkhag_id" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/gewogs') ,
            'cancelName' => 'CANCEL'
            ])

        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection