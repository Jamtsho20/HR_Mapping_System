@extends('layouts.app')
@section('page-title', 'Employee To Be Regularized')
@section('content')
<style>
    #loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color:white;
        opacity: 0.5;
        /* Semi-transparent background */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        /* Ensures it's in front of all other content */
    }
</style>
<div class="dimmer active" id="loader" style="display:none">
    <div class="lds-ring">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-4 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}"
            placeholder="Name">
    </div>
    <div class="col-4 form-group">
        <input type="text" name="username" class="form-control" value="{{ request()->get('username') }}"
            placeholder="Employee Id">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row" class="thead-light">
                                                            <th>
                                                                SL no
                                                            </th>
                                                            <th>
                                                                Employee Id
                                                            </th>
                                                            <th>
                                                                Name
                                                            </th>
                                                            <th>
                                                                DOJ
                                                            </th>

                                                            <th>
                                                                Contact No
                                                            </th>
                                                            <th>
                                                                Email
                                                            </th>
                                                            <th>
                                                                Appointment Order(P)
                                                            </th>
                                                            <th>
                                                                Employee Status
                                                            </th>
                                                            <th>
                                                                Action </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($employees as $employee)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{$employee->username}}</td>
                                                            <td>{{$employee->name}}</td>
                                                            <td>{{$employee->date_of_appointment}}</td>
                                                            <td>{{$employee->contact_number}}</td>
                                                            <td>{{$employee->email}}</td>
                                                            <td class="text-center">
                                                                @if($employee->appointment_order)
                                                                <a href="{{ Storage::url($employee->appointment_order) }}" class="btn-sm btn-primary" target="_blank">
                                                                    <i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>&nbsp; View
                                                                </a>
                                                                @else
                                                                -
                                                                @endif

                                                            </td>

                                                            <td>
                                                                <span class="badge rounded-pill  me-1 mb-1 mt-1 bg-{{ $employee->is_active == 'Active' ? 'primary' : 'danger' }}">
                                                                    {{ $employee->is_active }}
                                                                </span>
                                                            </td>

                                                            <td class="text-center">
                                                                <label class="custom-switch">
                                                                    <input type="hidden" name="is_regularized" value="{{$employee->is_regularized}}">
                                                                    <input type="checkbox" name="is_regularized"
                                                                        class="custom-switch-input"
                                                                        value="{{ $employee->is_regularized }}"
                                                                        data-id="{{ $employee->id }}"
                                                                        {{ $employee->is_regularized ? 'checked' : '' }}
                                                                        onchange="toggleStatus(this)">
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                                @if ($employee->is_regularized == 1 && !$employee->regular_appointment_order)
                                                                <a href="#"
                                                                    class="btn btn-sm btn-rounded btn-outline-info"
                                                                    data-url="{{ route('employee.generate-regular-ao', ['id' => $employee->id]) }}"
                                                                    data-id="{{ $employee->id }}"
                                                                    data-regularized="{{ $employee->is_regularized }}">
                                                                    <i class="fa fa-spinner"></i> Generate Appointment Order
                                                                </a>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="9" class="text-danger text-center">No Employee to be Regularized</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div>{{ $employees->links() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function toggleStatus(element) {
        var recordId = element.getAttribute('data-id');
        var is_regularized = element.checked ? 1 : 0;

        // Display confirmation popup
        var confirmation = confirm(
            is_regularized ?
            "Are you sure you want to regularize this employee?" :
            "Are you sure you want to remove regularization for this employee?"
        );

        if (!confirmation) {
            // Revert the toggle to its previous state
            element.checked = !is_regularized;
            return;
        }

        // AJAX request to update the status
        $.ajax({
            url: '{{route('employee-regularize.toggles-status')}}',
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                id: recordId,
                is_regularized: is_regularized
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 419) {
                    alert('Session expired. Please refresh the page and try again.');
                    location.reload();
                } else {
                    alert('Error: ' + xhr.responseText);
                }
            }
        });
    }


    // Function to generate the appointment order via AJAX
    function generateAppointmentOrder(url, recordId, isRegularized) {
        $('#loader').show();
        $.ajax({
            url: '{{route('employee.generate-regular-ao')}}',
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token for security
                id: recordId, // Send the employee ID
                is_regularized: isRegularized // Send the regularization status
            },
            success: function(response) {
                $('#loader').hide();
                alert(response.message); // Show the response message
                location.reload(); // Reload the page to reflect the changes
            },
            error: function(xhr) {
                $('#loader').hide();
                if (xhr.status === 419) {
                    alert('Session expired. Please refresh the page and try again.');
                    location.reload(); // Reload the page in case of session expiry
                } else {
                    alert('Error: ' + xhr.responseText); // Show an error message
                }
            }
        });
    }

    // Event listener for the button click
    $(document).on('click', '.btn-outline-info', function(e) {
        e.preventDefault(); // Prevent the default action (such as navigating to a link)

        // Get the URL, ID, and regularization status from the button's data attributes
        var url = $(this).data('url');
        var recordId = $(this).data('id');
        var isRegularized = $(this).data('regularized');

        // Debugging: Log the data
        console.log(url, recordId, isRegularized);

        // Call the function to send the AJAX request
        generateAppointmentOrder(url, recordId, isRegularized);
    });
</script>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
