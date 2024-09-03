@extends('layouts.app')
@section('page-title', 'Employee List')
@section('content')

<style>
    /* Initially hide all content panels */
    .content .body {
        display: none;
    }

    /* Show the current panel */
    .content .body.current {
        display: block;
    }

    /* Hide previous button on the first tab */
    .previous-hidden {
        display: none;
    }

    /* Scrollable tabs container */
    .steps {
        overflow-x: auto;
        /* Enable horizontal scrolling */
        white-space: nowrap;
        /* Prevent wrapping of tabs */
    }

    .steps ul {
        display: flex;
        /* Arrange tabs in a row */
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .steps li {
        display: inline-block;
        /* Keep tabs in a row */
    }
</style>

<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Personal Details</h3>
        </div>
        <div class="card-body p-6">
            <form action="{{ route('employee-lists.store') }}" id="emp-form" method="post" enctype="multipart/form-data">
                @csrf
                @include('employee.employee-list.forms.personal')

                <div class="panel panel-primary">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li><a href="#address" class="active" data-bs-toggle="tab">Address</a></li>
                                <li><a href="#job" data-bs-toggle="tab">Job Details</a></li>
                                <li><a href="#qualification" data-bs-toggle="tab">Qualification (s)</a></li>
                                <li><a href="#training" data-bs-toggle="tab">Training (s)</a></li>
                                <li><a href="#experience" data-bs-toggle="tab">Experience (s)</a></li>
                                <li><a href="#document" data-bs-toggle="tab">Document (s)</a></li>`
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">

                        <input type="hidden" name="status" id="is_complete" value="0">

                        <div class="tab-content">
                            <div class="tab-pane active" id="address">
                                @include('employee.employee-list.forms.address')
                            </div>
                            <div class="tab-pane" id="job">
                                @include('employee.employee-list.forms.job')
                            </div>
                            <div class="tab-pane" id="qualification">
                                @include('employee.employee-list.forms.qualification')
                            </div>
                            <div class="tab-pane" id="training">
                                @include('employee.employee-list.forms.training')
                            </div>
                            <div class="tab-pane" id="experience">
                                @include('employee.employee-list.forms.experience')
                            </div>
                            <div class="tab-pane" id="document">
                                @include('employee.employee-list.forms.document')
                            </div>
                        </div>
                        <button type="button" onclick="submitForm(false)" class="btn btn-secondary float-start">Save Progress</button>
                        <button type="button" onclick="submitForm(true)" class="btn btn-primary float-end">Submit</button>
            </form>
        </div>
    </div>
</div>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    function submitForm(isComplete) {
        document.getElementById('is_complete').value = isComplete ? 1 : 0;
        document.getElementById('emp-form').submit();
    }
</script>
@endpush