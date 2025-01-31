@extends('layouts.app')
@section('page-title', 'Showing Othe Pay Changes')
@section('buttons')
    <a href="{{ route('other-pay-changes.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')
    <form action="{{ route('other-pay-changes.update', $payChange->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body d-flex justify-content-between">
                <div class="form-group col-md-6">
                    <label for="for_month">For Month <span class="text-danger">*</span></label>
                    <input type="month" class="form-control" name="for_month" required="required">
                </div>
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fa fa-upload"></i> Save
                    </button>
                    &nbsp;
                    <a href="{{ url('payroll/other-pay-changes') }}" class="btn btn-danger">
                        <i class="fa fa-undo"></i> CANCEL
                    </a>
                </div>
            </div>
        </div>
    </form>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail</h3>
                    @if ($payChange->status['key'] == 1)
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add-other-pay-change-detail-modal">
                            <i class="fa fa-plus"></i> New
                            Detail
                        </button>
                    @endif
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
                                                <table
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Employee</th>
                                                            <th>Grade Step</th>
                                                            <th>No of increments</th>
                                                            <th>New basic pay</th>
                                                            <th>Approved</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($details as $record)
                                                            <tr>
                                                                <td>{{ $record->employee->name }}</td>
                                                                <td>{{ $record->gradeStep->name }}</td>
                                                                <td>{{ $record->no_of_increments }}</td>
                                                                <td>{{ $record->new_basic_pay }}</td>
                                                                <td>
                                                                    <label class="custom-switch">
                                                                        <input type="checkbox" name="status"
                                                                            class="custom-switch-input" value="1"
                                                                            data-id="{{ $record->id }}"
                                                                            {{ $record->status ? 'checked' : '' }}
                                                                            onchange="toggleStatus(this)"
                                                                            {{ $payChange->status['key'] == 4 ? 'disabled' : '' }}>
                                                                        <span class="custom-switch-indicator"></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control remarks-input"
                                                                        value="{{ $record->remarks }}"
                                                                        data-id="{{ $record->id }}"
                                                                        {{ $payChange->status['key'] == 4 ? 'readonly' : '' }}>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center text-danger">No
                                                                    records found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>

                                                <div>{{ $details->links() }}</div>
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

    <!-- Add Detail -->
    <div class="modal fade" id="add-other-pay-change-detail-modal" tabindex="-1"
        aria-labelledby="add-other-pay-change-detail-modal" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('other-pay-change-detail.add', $payChange->id) }}" method="post"
                        id="other-pay-change-form">
                        @csrf
                        <input type="hidden" class="form-control" name="other_pay_change_id" value="{{ $payChange->id }}"
                            required="required">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                                <select class="form-control" name="mas_employee_id" id="mas_employee_id" required>
                                    <option value="">Select</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                            ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="mas_grade_step_id">Grade step <span class="text-danger">*</span></label>
                                <select class="form-control" name="mas_grade_step_id" id="mas_grade_step_id" required>
                                    <option value="">Select</option>
                                    @foreach ($gradeSteps as $gradeStep)
                                        <option value="{{ $gradeStep->id }}">{{ $gradeStep->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="no_of_increments">No of increments </label>
                                <input type="number" class="form-control" name="no_of_increments" id="no_of_increments"
                                    required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="new_basic_pay">New basic pay <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="new_basic_pay" id="new_basic_pay"
                                    required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="amount">Remarks </label>
                                <textarea class="form-control" name="remarks" id="remarks"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_scripts')
    <script>
        function calculateNewBasicPay() {
            var employeeId = $('#mas_employee_id').val();
            var gradeStepId = $('#mas_grade_step_id').val();
            var noOfIncrements = $('#no_of_increments').val();

            if (employeeId && gradeStepId) {
                $.ajax({
                    url: '{{ route('new-basic-pay.calculate') }}',
                    method: 'GET',
                    data: {
                        employee_id: employeeId,
                        grade_step_id: gradeStepId,
                        no_of_increments: noOfIncrements
                    },
                    success: function(data) {
                        $('#new_basic_pay').val(data.new_basic_pay);
                        $('#no_of_increments').attr('max', data.point);
                    },
                    error: function(error) {
                        console.error('Error calculating new basic pay:', error);
                    }
                });
            }
        }

        $('#mas_employee_id').change(function() {
            calculateNewBasicPay();
        });

        $('#mas_grade_step_id').change(function() {
            calculateNewBasicPay();
        });

        $(document).on('input', '#no_of_increments', function() {
            calculateNewBasicPay();
        });

        function toggleStatus(element) {
            var recordId = element.getAttribute('data-id');
            var status = element.checked ? 1 : 0;

            $.ajax({
                url: '{{ route('other-pay-changes.toggles-status') }}',
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: recordId,
                    status: status
                },
                success: function(response) {
                    alert(response.message);
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

        $(document).on('blur', '.remarks-input', function() {
            var recordId = $(this).data('id');
            var remarks = $(this).val();

            $.ajax({
                url: '{{ route('other-pay-changes.update-remarks') }}',
                type: 'PATCH',
                data: {
                    id: recordId,
                    remarks: remarks,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // alert(response.message);
                    } else {
                        alert('Failed to update remarks.');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    </script>
@endpush
