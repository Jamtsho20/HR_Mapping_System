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

<div class="row">
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <strong class="font-bold">Whoops!</strong>
        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
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
                        <form action="{{ route('employee-lists.store') }}" id="emp-form" method="post" enctype="multipart/form-data">
                            @csrf
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
<script>
    $(document).ready(function() {
        function updateNavigationButtons() {
            var $currentTab = $('.steps .current');
            var $nextTab = $currentTab.next();

            // Show or hide the Previous button based on the current tab
            if ($currentTab.hasClass('first')) {
                $('#previous-button').hide();
            } else {
                $('#previous-button').show();
            }

            // Show or hide the Next button based on whether there's a next tab
            if ($nextTab.length) {
                $('#next-button').show();
                $('#submit-button').hide();
            } else {
                $('#next-button').hide();
                $('#submit-button').show();
            }
        }

        function validateCurrentForm() {
            var isValid = true;

            // Check all required fields in the current and previous tabs
            $('.content .body:visible').each(function() {
                $(this).find(':input[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid'); // Add a class to highlight the input
                    } else {
                        $(this).removeClass('is-invalid'); // Remove the class if the input is filled
                    }
                });
            });

            return isValid;
        }

        $('#next-button').on('click', function(e) {
            e.preventDefault();

            // Validate the current form before proceeding
            if (!validateCurrentForm()) {
                alert('Please fill all required fields.');
                return;
            }

            // Find the current tab and its content panel
            var $currentTab = $('.steps .current');
            var $currentPanel = $('#' + $currentTab.find('a').attr('aria-controls'));

            // Find the next tab and its content panel
            var $nextTab = $currentTab.next();
            if ($nextTab.length) {
                var $nextPanel = $('#' + $nextTab.find('a').attr('aria-controls'));

                // Update tabs
                $currentTab.removeClass('current').attr('aria-selected', 'false').addClass('disabled').attr('aria-disabled', 'true');
                $nextTab.addClass('current').attr('aria-selected', 'true').removeClass('disabled').attr('aria-disabled', 'false');

                // Update panels
                $currentPanel.hide().attr('aria-hidden', 'true');
                $nextPanel.show().attr('aria-hidden', 'false');

                // Update navigation buttons
                updateNavigationButtons();
            }
        });

        $('#previous-button').on('click', function(e) {
            e.preventDefault();

            // Find the current tab and its content panel
            var $currentTab = $('.steps .current');
            var $currentPanel = $('#' + $currentTab.find('a').attr('aria-controls'));

            // Find the previous tab and its content panel
            var $prevTab = $currentTab.prev();
            if ($prevTab.length) {
                var $prevPanel = $('#' + $prevTab.find('a').attr('aria-controls'));

                // Update tabs
                $currentTab.removeClass('current').attr('aria-selected', 'false').addClass('disabled').attr('aria-disabled', 'true');
                $prevTab.addClass('current').attr('aria-selected', 'true').removeClass('disabled').attr('aria-disabled', 'false');

                // Update panels
                $currentPanel.hide().attr('aria-hidden', 'true');
                $prevPanel.show().attr('aria-hidden', 'false');

                // Update navigation buttons
                updateNavigationButtons();
            }
        });

        $('#submit-button').on('click', function(e) {
            if (!validateCurrentForm()) {
                e.preventDefault();
                alert('Please fill all required fields.');
            } else {
                $('#emp-form').submit();
            }
        });

        // Initialize navigation buttons
        updateNavigationButtons();
    });
</script>
@endpush