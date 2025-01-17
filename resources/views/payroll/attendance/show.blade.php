@extends('layouts.app')
@section('page-title', 'Showing Attendance Details')
@section('buttons')
    <a href="{{ route('annual-increment.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')
    <div class="block-header block-header-default">
        <form action="{{ route('attendance.upload', $attendance->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4>Upload Attendance</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- For Month -->
                        <div class="form-group col-md-6">
                            <label for="for_month">For Month</label>
                            <input type="month" class="form-control" name="for_month"
                                value="{{ substr(\Carbon\Carbon::createFromFormat('m-Y', $attendance->for_month), 0, 7) }}"
                                disabled>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="attendance_sheet">
                                Attendance Sheet
                                <span class="text-danger">*</span>
                                <a href="{{ asset('assets/samples/attendance_sheet.xlsx') }}" class="btn btn-link" download>
                                    Download Sample File
                                </a>
                            </label>
                            <input type="file" class="form-control" name="attendance_sheet" required="required"
                                accept=".csv, .xlsx, .xls">
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload"></i> UPLOAD
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Details</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Working Days</th>
                                <th>Physical Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($details as $record)
                                <tr>
                                    <td>{{ $record->employee->name }} ({{ $record->employee->employee_id }})</td>
                                    <td>{{ $record->working_days }}</td>
                                    <td> <input type="number" class="form-control physical_day" name="physical_days" id="physical_days"
                                            value="{{ $record->physical_days }}" data-id = {{ $record->id }}></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-danger">No records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $details->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_scripts')
    <script>
        $(document).on('blur', '.physical_day', function() {
            var recordId = $(this).data('id');
            var physicalDay = $(this).val();

            $.ajax({
                url: '{{ route('attendance.updateattendance') }}',
                type: 'PATCH',
                data: {
                    id: recordId,
                    physical_days: physicalDay,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // alert(response.message);
                    } else {
                        alert('Failed to update physical day.');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    </script>
@endpush
