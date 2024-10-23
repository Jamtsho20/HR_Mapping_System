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
                                <span class="current-info audible">current step: </span><span class="number">1</span>
                                <span class="title">Expense Policy</i></span>
                            </a>
                        </li>
                        <li role="tab" class="disabled" aria-disabled="true">
                            <a id="wizard1-t-1" aria-controls="wizard1-p-1"><span class="number">2</span> <span
                                    class="title">Rate Definition</span></a>
                        </li>
                        <li role="tab" class="disabled last" aria-disabled="true">
                            <a id="wizard1-t-2" aria-controls="wizard1-p-2"><span class="number">3</span> <span
                                    class="title">Policy Enforcement</span></a>
                        </li>
                        <li role="tab" class="disabled last" aria-disabled="true">
                            <a id="wizard1-t-3" aria-controls="wizard1-p-3"><span class="number">4</span> <span
                                    class="title">Complete</span></a>
                        </li>

                    </ul>
                </div>

                <div class="content clearfix">
                    <form action="{{ route('expense-policy.store') }}" id="expense-form" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="wizard1-p-0" role="tabpanel" aria-labelledby="wizard1-h-0" class="body current"
                            aria-hidden="false">
                            @include('expense.expense-policy.forms.expense-policy')
                        </div>

                        <div id="wizard1-p-1" role="tabpanel" aria-labelledby="wizard1-h-1" class="body"
                            aria-hidden="true">
                            @include('expense.expense-policy.forms.rate-definition')
                        </div>

                        <div id="wizard1-p-2" role="tabpanel" aria-labelledby="wizard1-h-2" class="body"
                            aria-hidden="true">
                            @include('expense.expense-policy.forms.policy-enforcement')
                        </div>

                        <div id="wizard1-p-3" role="tabpanel" aria-labelledby="wizard1-h-3" class="body"
                            aria-hidden="true">
                            @include('expense.expense-policy.forms.complete')
                        </div>

                        <div class="actions clearfix"
                            style="display: flex; justify-content: space-between; padding: 0; margin: 0;">
                            <ul class="pagination" role="menu" aria-label="Pagination"
                                style="list-style: none; padding: 0; margin: 0; display: flex; gap: 5px;">
                                <li aria-hidden="false" aria-disabled="false" id="previous-container">
                                    <a href="#" role="menuitem" id="previous-button"
                                        class="btn btn-md btn-secondary">Previous</a>
                                </li>
                                <li aria-hidden="false" aria-disabled="false">
                                    <a href="#" role="menuitem" id="next-button" class="btn btn-md btn-primary">Next</a>
                                </li>
                                {{-- <li aria-hidden="false" aria-disabled="false"> --}}
                                <button type="submit" role="menuitem" id="submit-button"
                                    class="btn btn-md btn-primary" value="submit">Submit</button>
                                {{--
                                </li> --}}
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

<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        updateSummary();
        setupRealTimeSaving();
    });

    const summaryTab = document.getElementById('wizard1-t-3');
    if (summaryTab) {
        summaryTab.addEventListener('click', updateSummary);
    }

    const nextButton = document.getElementById('next-button');
    const previousButton = document.getElementById('previous-button');

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            saveFormData(); // Save data when navigating to the next tab
            updateSummary(); // Update summary on tab change
        });
    }

    if (previousButton) {
        previousButton.addEventListener('click', () => {
            saveFormData(); // Save data when navigating to the previous tab
            updateSummary(); // Update summary on tab change
        });
    }

    function saveFormData() {
        const form = document.getElementById('expense-form');
        const formData = new FormData(form);
        const data = {};

        formData.forEach((value, key) => {
            // If key already exists in data object, convert to array or append new values
            if (data[key]) {
                // If not already an array, convert it to an array
                if (!Array.isArray(data[key])) {
                    data[key] = [data[key]];
                }
                data[key].push(value); // Add new value to the array
            } else {
                // Handle checkboxes
                if (form.elements[key] && form.elements[key].type === 'checkbox') {
                    data[key] = form.elements[key].checked ? '1' : '0';
                } else {
                    data[key] = value; // Set initial value
                }
            }
        });

        // Save the entire data object as a JSON string in localStorage
        localStorage.setItem('formData', JSON.stringify(data));
        console.log('this is test');
        
        console.log(data)

        updateSummary(); // Ensure summary is updated right away
    }


    function setupRealTimeSaving() {
        const form = document.getElementById('expense-form');
        if (form) {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('change', saveFormData);
                // For text inputs, also listen for keyup events
                if (input.type === 'text' || input.type === 'textarea') {
                    input.addEventListener('keyup', saveFormData);
                }
            });
        }
    }

    // Pass the $expense array as a JSON object to JavaScript
    const expenses = @json($expenses);

    // Create a mapping from IDs to names
    const expensesMap = expenses.reduce((map, expense) => {
        map[expense.id] = expense.name;
        return map;
    }, {});
</script> -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupRealTimeSaving();
        updateSummary();

        const summaryTab = document.getElementById('wizard1-t-3');
        if (summaryTab) {
            summaryTab.addEventListener('click', updateSummary);
        }

        const nextButton = document.getElementById('next-button');
        const previousButton = document.getElementById('previous-button');

        if (nextButton) {
            nextButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission on Next
                saveFormData(); // Save data when navigating to the next tab
                updateSummary(); // Update summary on tab change
                moveToNextStep(); // Move to the next tab
            });
        }

        if (previousButton) {
            previousButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission on Previous
                saveFormData(); // Save data when navigating to the previous tab
                updateSummary(); // Update summary on tab change
                moveToPreviousStep(); // Move to the previous tab
            });
        }
    });

    function saveFormData() {
        const form = document.getElementById('expense-form');
        const inputs = form.querySelectorAll('input, select, textarea');
        const data = {};

        inputs.forEach(input => {
            // Handle checkboxes
            if (input.type === 'checkbox') {
                data[input.name] = input.checked ? '1' : '0';
            }
            // Handle radio buttons
            else if (input.type === 'radio') {
                if (input.checked) {
                    data[input.name] = input.value;
                }
            }
            // Handle other input types
            else {
                data[input.name] = input.value;
            }
        });

        // Save the entire data object as a JSON string in localStorage
        localStorage.setItem('formData', JSON.stringify(data));
        console.log('Form data saved to localStorage:', data);

        updateSummary(); // Ensure summary is updated right away
    }

    // Setup real-time saving for all form inputs
    function setupRealTimeSaving() {
        const form = document.getElementById('expense-form');
        if (form) {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('change', saveFormData);
                if (input.type === 'text' || input.tagName.toLowerCase() === 'textarea') {
                    input.addEventListener('keyup', saveFormData);
                }
            });
        }
    }

    const expenses = @json($expenses);

    // Create a mapping from IDs to names
    const expensesMap = expenses.reduce((map, expense) => {
        map[expense.id] = expense.name;
        return map;
    }, {});

    const gradeSteps = @json($gradeSteps);

    // Create a mapping from IDs to names
    const gradeStepMap = gradeSteps.reduce((map, step) => {
        map[step.id] = step.name;
        return map;
    }, {});
</script>