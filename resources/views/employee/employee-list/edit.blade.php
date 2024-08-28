@extends('layouts.app')
@section('page-title', 'Edit Employee List')
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="wizard1" role="application" class="wizard clearfix">
                    <div class="steps clearfix">
                        <ul role="tablist">
                            <li role="tab" class="first current" aria-disabled="false" aria-selected="true">
                                <a id="wizard1-t-0" aria-controls="wizard1-p-0" title="Personal Info">
                                    <span class="current-info audible">current step: </span><span class="number">1</span> <span class="title">Basic Info</i></span>
                                </a>
                            </li>
                            <li role="tab" class="disabled" aria-disabled="true">
                                <a id="wizard1-t-1" aria-controls="wizard1-p-1"><span class="number">2</span> <span class="title">Address</span></a>
                            </li>
                            <li role="tab" class="disabled last" aria-disabled="true">
                                <a id="wizard1-t-2" aria-controls="wizard1-p-2"><span class="number">3</span> <span class="title">Job Details</span></a>
                            </li>
                            <li role="tab" class="disabled last" aria-disabled="true">
                                <a id="wizard1-t-3" aria-controls="wizard1-p-3"><span class="number">4</span> <span class="title">Qualifications</span></a>
                            </li>
                            <li role="tab" class="disabled last" aria-disabled="true">
                                <a id="wizard1-t-4" aria-controls="wizard1-p-4"><span class="number">5</span> <span class="title">Training (s)</span></a>
                            </li>
                            <li role="tab" class="disabled last" aria-disabled="true">
                                <a id="wizard1-t-5" aria-controls="wizard1-p-5"><span class="number">6</span> <span class="title">Experience (s)</span></a>
                            </li>
                            {{-- <li role="tab" class="disabled last" aria-disabled="true">
                                <a id="wizard1-t-6" href="#wizard1-h-6" aria-controls="wizard1-p-6"><span class="number">7</span> <span class="title">Employment</span></a>
                            </li> --}}
                            <li role="tab" class="disabled last" aria-disabled="true">
                                <a id="wizard1-t-7" aria-controls="wizard1-p-7"><span class="number">7</span> <span class="title">Documents</span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="content clearfix">
                        <form action="{{ route('employee-lists.update', $employee->id) }}" id="emp-form" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="type" value="2" />
                            <div id="wizard1-p-0" role="tabpanel" aria-labelledby="wizard1-h-0" class="body current" aria-hidden="false">
                                @include('employee.employee-list.forms.personal')
                            </div>

                            <div id="wizard1-p-1" role="tabpanel" aria-labelledby="wizard1-h-1" class="body" aria-hidden="true">
                                @include('employee.employee-list.forms.address')
                            </div>

                            <div id="wizard1-p-2" role="tabpanel" aria-labelledby="wizard1-h-2" class="body" aria-hidden="true">
                                @include('employee.employee-list.forms.job')
                            </div>

                            <div id="wizard1-p-3" role="tabpanel" aria-labelledby="wizard1-h-3" class="body" aria-hidden="true">
                                @include('employee.employee-list.forms.qualification')
                            </div>

                            <div id="wizard1-p-4" role="tabpanel" aria-labelledby="wizard1-h-4" class="body" aria-hidden="true">
                                @include('employee.employee-list.forms.training')
                            </div>
                            <div id="wizard1-p-5" role="tabpanel" aria-labelledby="wizard1-h-5" class="body" aria-hidden="true">
                                @include('employee.employee-list.forms.experience')
                            </div>
                            {{-- <div id="wizard1-p-6" role="tabpanel" aria-labelledby="wizard1-h-6" class="body" aria-hidden="true">
                                @include('employee.employee-list.forms.employment')
                            </div> --}}
                            <div id="wizard1-p-7" role="tabpanel" aria-labelledby="wizard1-h-7" class="body" aria-hidden="true">
                                @include('employee.employee-list.forms.document')
                            </div>
                            <div class="actions clearfix" style="display: flex; justify-content: space-between; padding: 0; margin: 0;">
                                <ul class="pagination" role="menu" aria-label="Pagination" style="list-style: none; padding: 0; margin: 0; display: flex; gap: 5px;">
                                    <li aria-hidden="false" aria-disabled="false" id="previous-container">
                                        <a href="#" role="menuitem" id="previous-button" class="btn btn-md btn-secondary">Previous</a>
                                    </li>
                                    <li aria-hidden="false" aria-disabled="false">
                                        <a href="#" role="menuitem" id="next-button" class="btn btn-md btn-primary">Next</a>
                                    </li>
                                    {{-- <li aria-hidden="false" aria-disabled="false"> --}}
                                    <button type="submit" role="menuitem" id="submit-button" class="btn btn-md btn-primary">Submit</button>
                                    {{-- </li> --}}
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush