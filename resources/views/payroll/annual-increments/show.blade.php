@extends('layouts.app')
@section('page-title', 'Showing Annual Increment Details')
@section('buttons')
    <a href="{{ route('annual-increment.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Annual
        Increment
        List</a>
@endsection
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="form-group col-md-6">
                    <label for="for_month">For Month <span class="text-danger">*</span></label>
                    <input type="month" class="form-control" name="for_month"
                        value="{{ substr($annualIncrement->for_month, 0, 7) }}" required="required">
                </div>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail</h3>
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
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Employee</th>
                                                            <th>Amount</th>
                                                            <th>Approved</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($details as $record)
                                                            <tr>
                                                                <td>{{ $record->employee->name }} ({{ $record->employee->employee_id }})</td>
                                                                <td>{{ $record->amount }}</td>
                                                                <td>
                                                                    <label class="custom-switch">
                                                                        <input type="hidden" name="status" value="0">
                                                                        <input type="checkbox" name="status"
                                                                            class="custom-switch-input"
                                                                            value="{{ $record->status }}"
                                                                            data-id="{{ $record->id }}"
                                                                            {{ $record->status ? 'checked' : '' }}
                                                                            onchange="toggleStatus(this)">
                                                                        <span class="custom-switch-indicator"></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control remarks-input"
                                                                        value="{{ $record->remarks }}"
                                                                        data-id="{{ $record->id }}">
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center text-danger">No
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
@endsection
@push('page_scripts')
    <script>
        function toggleStatus(element) {
            var recordId = element.getAttribute('data-id');
            var status = element.checked ? 1 : 0;

            $.ajax({
                url: '{{ route('annual-increment.toggles-status') }}',
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
                url: '{{ route('annual-increment.update-remarks') }}',
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
