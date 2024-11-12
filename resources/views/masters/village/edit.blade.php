@extends('layouts.app')
@section('page-title', 'Village')
@section('content')

<form action="{{ url('master/villages/' . $village->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mas_gewog_id">Gewog <span class="text-danger">*</span></label>
                        <select name="mas_gewog_id" class="form-control">
                            @foreach ($gewogs as $gewog)
                            <option value="{{ $gewog->id }}" {{ $village->mas_gewog_id == $gewog->id ? 'selected' : '' }}>
                                {{ $gewog->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="village" value="{{ $village->village }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' =>url('master/villages'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection