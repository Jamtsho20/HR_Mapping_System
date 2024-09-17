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

        .tab-menu-heading {

            border: none !important;
            padding-left: 0 !important;

        }

        .tabs-menu-body {
            border: none !important;
        }
    </style>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Employee Details</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('employee-lists.update', $employee->id) }}" id="emp-form" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Include personal form here, always visible -->
                    @include('employee.employee-list.forms.personal')

                    <div class="panel panel-primary">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu1">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li><a href="#address" class="{{ request('current_tab') == 'address' ? 'active' : '' }}"
                                            data-tab="address">Address</a></li>
                                    <li><a href="#job" class="{{ request('current_tab') == 'job' ? 'active' : '' }}"
                                            data-tab="job">Job Details</a></li>
                                    <li><a href="#qualification"
                                            class="{{ request('current_tab') == 'qualification' ? 'active' : '' }}"
                                            data-tab="qualification">Qualification (s)</a></li>
                                    <li><a href="#training"
                                            class="{{ request('current_tab') == 'training' ? 'active' : '' }}"
                                            data-tab="training">Training (s)</a></li>
                                    <li><a href="#experience"
                                            class="{{ request('current_tab') == 'experience' ? 'active' : '' }}"
                                            data-tab="experience">Experience (s)</a></li>
                                    <li><a href="#document"
                                            class="{{ request('current_tab') == 'document' ? 'active' : '' }}"
                                            data-tab="document">Document (s)</a></li>
                                    <li><a href="#role" class="{{ request('current_tab') == 'role' ? 'active' : '' }}"
                                            data-tab="role">Assign Roles</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">

                            <input type="hidden" name="status" id="is_complete" value="0">
                            <input type="hidden" name="current_tab" id="current_tab" value="{{ request('tab') }}">
                            {{-- <input type="hidden" name="employee_id" id="employee_id" value="{{ $employeeId ?? '' }}">
                        --}}

                            <div class="tab-content">
                                <!-- Tab panes -->
                                <div class="tab-pane {{ request('current_tab') == 'address' ? 'active' : '' }}"
                                    id="address">
                                    @include('employee.employee-list.forms.address')
                                </div>
                                <div class="tab-pane {{ request('current_tab') == 'job' ? 'active' : '' }}" id="job">
                                    @include('employee.employee-list.forms.job')
                                </div>
                                <div class="tab-pane {{ request('current_tab') == 'qualification' ? 'active' : '' }}"
                                    id="qualification">
                                    @include('employee.employee-list.forms.qualification')
                                </div>
                                <div class="tab-pane {{ request('current_tab') == 'training' ? 'active' : '' }}"
                                    id="training">
                                    @include('employee.employee-list.forms.training')
                                </div>
                                <div class="tab-pane {{ request('current_tab') == 'experience' ? 'active' : '' }}"
                                    id="experience">
                                    @include('employee.employee-list.forms.experience')
                                </div>
                                <div class="tab-pane {{ request('current_tab') == 'document' ? 'active' : '' }}"
                                    id="document">
                                    @include('employee.employee-list.forms.document')
                                </div>
                                <div class="tab-pane {{ request('current_tab') == 'role' ? 'active' : '' }}"
                                    id="role">
                                    @include('employee.employee-list.forms.role')
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <button type="button" onclick="saveTabData()" class="btn btn-secondary ">
                                Save & Progress
                            </button>
                            <a href="{{ route('employee-lists.index') }}" id="cancel" name="cancel"
                                class="btn btn-primary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.includes.delete-modal')

@endsection

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav.panel-tabs a');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(event) {
                    event.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const tabName = this.getAttribute('data-tab');
                    const newUrl = new URL(window.location);
                    newUrl.searchParams.set('tab', tabName);
                    window.history.pushState({}, '', newUrl);

                    // Activate the clicked tab and corresponding pane
                    tabs.forEach(tab => tab.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove(
                        'active'));

                    this.classList.add('active');
                    document.getElementById(targetId).classList.add('active');

                    // Update hidden input for the current tab
                    document.getElementById('current_tab').value = tabName;
                });
            });

            // Set initial active tab based on query string
            const queryTab = new URLSearchParams(window.location.search).get('tab');
            if (queryTab) {
                document.querySelector(`a[data-tab="${queryTab}"]`).click();
            }
        });

        function saveTabData() {
            const currentTab = document.getElementById('current_tab').value;
            document.getElementById('emp-form').submit();
        }
    </script>
@endpush
