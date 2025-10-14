@extends('layouts.app')
@section('page-title', 'Holiday List')

@if ($privileges->create)
@section('buttons')
<button type="button" data-bs-toggle="modal" data-bs-target="#create-modal" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> New Holiday List
</button>
@endsection
@endif

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <select class="form-control" name="year">
                <option value="" disabled selected hidden>Select year</option>
                @foreach ($dates as $date)
                <option @if (request()->get('year') == $date) selected @endif value="{{ $date }}">
                    {{ $date }}
                </option>
                @endforeach
            </select>
        </div>
        @endcomponent
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                            id="basic-datatable table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>HOLIDAY NAME</th>
                                    <th>HOLIDAY TYPE</th>
                                    <th>REGION</th>
                                    <th>START DATE</th>
                                    <th>END DATE</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($holidays as $holiday)
                                <tr>
                                    <td>{{ $holidays->firstItem() + ($loop->iteration - 1) }}</td>
                                    <td>{{ $holiday->holiday_name }}</td>
                                    <td>{{ $holiday->holiday_type }}</td>
                                    <td>{{ implode(', ', $holiday->region_name) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($holiday->start_date)->format('d-M-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($holiday->end_date)->format('d-M-Y') }}</td>
                                    <td>{{ $holiday->status ? 'Active' : 'Inactive' }}</td>
                                    <td class="text-center">
                                        @if ($privileges->edit)
                                        <a href="{{ url('work-structure/holiday-lists/' . $holiday->id) }}"
                                            class="edit-btn btn btn-sm btn-rounded btn-outline-success"
                                            data-holiday="{{ $holiday->holiday_name }}"
                                            data-type="{{ $holiday->holiday_type }}"
                                            data-regions="{{ json_encode($holiday->region_id) }}"
                                            data-start="{{ $holiday->start_date }}"
                                            data-end="{{ $holiday->end_date }}"
                                            data-status="{{ $holiday->status }}">
                                            <i class="fa fa-edit"></i> EDIT
                                        </a>
                                        @endif
                                        @if ($privileges->delete)
                                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                            data-url="{{ url('work-structure/holiday-lists/' . $holiday->id) }}">
                                            <i class="fa fa-trash"></i> DELETE
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-danger">No Holiday found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($holidays->hasPages())
                        <div class="card-footer">
                            {{ $holidays->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal fade" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ url('work-structure/holiday-lists') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" name="holiday_name" class="form-control" required>
                    </div>

                    <div class="form-group mt-2">
                        <label>Holiday Type <span class="text-danger">*</span></label>
                        <select name="holiday_type" class="form-control" required>
                            <option value="" disabled selected hidden>Select option</option>
                            @foreach (config('global.holiday_types') as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label>Region <span class="text-danger">*</span></label>
                        <select class="js-select2 form-control" name="mas_region_id[]" multiple required>
                            @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label>Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>

                    <div class="form-group mt-2">
                        <label>End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>

                    <div class="form-group mt-2">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="" disabled selected hidden>Select option</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="edit-modal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" name="holiday_name" class="form-control" required>
                    </div>

                    <div class="form-group mt-2">
                        <label>Holiday Type <span class="text-danger">*</span></label>
                        <select name="holiday_type" class="form-control" required>
                            <option value="" disabled hidden>Select option</option>
                            @foreach (config('global.holiday_types') as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label>Region <span class="text-danger">*</span></label>
                        <select class="js-select2 form-control region-dropdown" name="mas_region_id[]" multiple required>
                            @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label>Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>

                    <div class="form-group mt-2">
                        <label>End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>

                    <div class="form-group mt-2">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="" disabled hidden>Select option</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Update
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
    $('.js-select2').select2();

    // Handle edit button click
    $('.edit-btn').on('click', function(e) {
        e.preventDefault();
        var modal = $('#edit-modal');
        var url = $(this).attr('href');

        modal.find('form').attr('action', url);
        modal.find('input[name=holiday_name]').val($(this).data('holiday'));
        modal.find('select[name=holiday_type]').val($(this).data('type')).trigger('change');
        modal.find('input[name=start_date]').val($(this).data('start'));
        modal.find('input[name=end_date]').val($(this).data('end'));
        modal.find('select[name=status]').val($(this).data('status'));

        var regions = $(this).data('regions');
        if (regions) {
            modal.find('.region-dropdown').val(regions).trigger('change');
        }

        var myModal = new bootstrap.Modal(modal[0]);
        myModal.show();
    });
});
</script>
@endpush
