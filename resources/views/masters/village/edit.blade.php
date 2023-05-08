@extends('layouts.app')
@section('page-title', 'Village')
@section('content')

<form action="{{ url('master/villages/' . $village->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">
            <div class="form-group">
                <label for="name">Gewog <span class="text-danger">*</span> </label>
                <select name="mas_gewog_id" class="form-control ">
                    @foreach ($gewogs as $gewog)
                    <option value="{{ $gewog->id }}" {{ $village->mas_gewog_id == $gewog->id ? 'selected' : '' }}>{{ $gewog->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="village" value="{{$village->village}}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i>UPDATE
        </button>
        <a href="{{ url('master/villages') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection