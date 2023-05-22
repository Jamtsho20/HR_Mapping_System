@extends('layouts.app')
@section('page-title', 'Gewog')
@section('content')


<form action="{{ url('master/gewogs/' . $gewog->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Dzongkhag <span class="text-danger">*</span></label>
                <select name="mas_dzongkhag_id" class="form-control" id="dzongkhag1">
                    @foreach ($dzongkhags as $dzongkhag)
                    <option value="{{ $dzongkhag->id }}" {{ $gewog->mas_dzongkhag_id == $dzongkhag->id ? 'selected' : '' }}>{{ $dzongkhag->dzongkhag }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{$gewog->name}}">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-alt-primary">
            <i class="fa fa-check"></i> Update
        </button>
        <button type="button" class="btn btn-alt-danger" data-dismiss="modal">Close</button>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection