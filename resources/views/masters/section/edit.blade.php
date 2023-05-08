@extends('layouts.app')
@section('page-title', 'Section')
@section('content')

<form action="{{ url('master/section/' . $section->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">
            <div class="form-group">
                <label for="name">Department <span class="text-danger">*</span> </label>
                <select name="mas_department_id" class="form-control">
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{$section->name}}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
        <a href="{{ url('master/section') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection