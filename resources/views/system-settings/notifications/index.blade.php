@extends('layouts.app')
@section('page-title', 'Hierarchy')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Notification Settings</h3>
    </div>
    <div class="card-body">
        <form action="{{ url('system-setting/notifications') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="col-lg-3 col-form-label" for="title">Title</label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 col-form-label" for="message">Message</label>
                <div class="col-lg-7">
                    <textarea class="form-control" id="message" name="message" placeholder="Enter message"></textarea>
                </div>
            </div>
            <div class="form-group ">
                <div class="col-lg-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <input class="btn btn-secondary" type="reset" value="Reset">
                </div>
            </div>
        </form>
    </div>

</div>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush