@extends('layouts.app')
@section('page-title', 'Hierarchy')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Notification Settings</h3>
    </div>
    <div class="block-content">
        <form action="" >
            <div class="form-group row">
                <label class="col-lg-3 col-form-label" for="title">Title</label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" id="title" name="title"
                        placeholder="Enter title">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label" for="message">Message</label>
                <div class="col-lg-7">
                    <textarea  class="form-control" id="message" name="message"
                        placeholder="Enter message"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-9 ml-auto">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <input class="btn btn-secondary" type="reset" value="Reset">
                </div>
            </div>
        </form>
    </div>

    <div class="card-footer">

    </div>

</div>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush