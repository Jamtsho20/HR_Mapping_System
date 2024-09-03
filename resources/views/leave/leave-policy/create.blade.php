@extends('layouts.app')
@section('page-title', 'Create Leave Policy')
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

    <div class="card">
        <div class="card-body">
            <div id="wizard1" role="application" class="wizard clearfix">
                <div class="steps clearfix">
                    <ul role="tablist">
                        <li role="tab" class="first current" aria-disabled="false" aria-selected="true">
                            <a id="wizard1-t-0" aria-controls="wizard1-p-0" title="Personal Info">
                                <span class="current-info audible">current step: </span><span class="number">1</span> <span class="title">Leave Policy</i></span>
                            </a>
                        </li>
                        <li role="tab" class="disabled" aria-disabled="true">
                            <a id="wizard1-t-1" aria-controls="wizard1-p-1"><span class="number">2</span> <span class="title">Leave Plan</span></a>
                        </li>
                        <li role="tab" class="disabled last" aria-disabled="true">
                            <a id="wizard1-t-2" aria-controls="wizard1-p-2"><span class="number">3</span> <span class="title">Year End Processing</span></a>
                        </li>
                        <li role="tab" class="disabled last" aria-disabled="true">
                            <a id="wizard1-t-3" aria-controls="wizard1-p-3"><span class="number">4</span> <span class="title">Summary</span></a>
                        </li>

                    </ul>
                </div>

                <div class="content clearfix">
                    <form action="{{ route('leave-policy.store') }}" id="leave-form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div id="wizard1-p-0" role="tabpanel" aria-labelledby="wizard1-h-0" class="body current" aria-hidden="false">
                            @include('leave.leave-policy.forms.leave-policy')
                        </div>

                        <div id="wizard1-p-1" role="tabpanel" aria-labelledby="wizard1-h-1" class="body" aria-hidden="true">
                            @include('leave.leave-policy.forms.leave-plan')
                        </div>

                        <div id="wizard1-p-2" role="tabpanel" aria-labelledby="wizard1-h-2" class="body" aria-hidden="true">
                            @include('leave.leave-policy.forms.year-end')
                        </div>

                        <div id="wizard1-p-3" role="tabpanel" aria-labelledby="wizard1-h-3" class="body" aria-hidden="true">
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
                                <button type="submit" role="menuitem" id="submit-button" class="btn btn-md btn-primary" value="submit">Submit</button>
                                {{-- </li> --}}
                            </ul>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')

@endsection
