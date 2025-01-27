@extends('layouts.app')
@section('page-title', 'Gewog')
@section('content')

<form action="{{ url('master/gewogs/' . $gewog->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dzongkhag1">Dzongkhag <span class="text-danger">*</span></label>
                        <select name="mas_dzongkhag_id" class="form-control" id="dzongkhag1" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}"
                                {{ $gewog->mas_dzongkhag_id == $dzongkhag->id ? 'selected' : '' }}>
                                {{ $dzongkhag->dzongkhag }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $gewog->name }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/gewogs') ,
            'cancelName' => 'CANCEL'
            ])
   
            </a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection