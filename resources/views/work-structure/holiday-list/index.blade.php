@extends('layouts.app')
@section('page-title', 'Holiday List')
@if ($privileges->create)
@section('buttons')
<button type="button" data-bs-toggle="modal" data-bs-target="#create-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Holiday List</button>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header card-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <select class="form-control" name="year">
                <option value="" disabled selected hidden>Select year</option>
                @foreach ($dates as $date)
                <option @if ($date==request()->get('year')) selected
                    @endif value="{{ $date}}"> {{ $date }}
                </option>
                @endforeach
            </select>
        </div>
        @endcomponent

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border table-condensed table-sm table-bordered table-hoverr">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Holiday Name</th>
                        <th>Holiday Type</th>
                        <th>Region</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                    <tr>
                        <td>{{ $holidays->firstItem() + ($loop->iteration - 1) }}</td>
                        <td>{{ $holiday->holiday_name }}</td>
                        <td>{{ $holiday->holiday_type }}</td>
                        <td>{{ implode(', ', $holiday->region_name) }}</td>
                        <td>{{ $holiday->start_date }}</td>
                        <td>{{ $holiday->end_date }}</td>


                        <td class="text-center">
                            @if ($privileges->edit)
                            <a href="{{ url('work-structure/holiday-lists/'.$holiday->id) }}" data-holiday="{{ $holiday->holiday_name }}" data-type="{{ $holiday->holiday_type }}" data-regions="{{ json_encode($holiday->region_id) }}" data-start="{{ $holiday->start_date }}" data-end="{{ $holiday->end_date }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success">
                                <i class="fa fa-edit"></i> EDIT
                            </a>
                            @endif
                            @if ($privileges->delete)
                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('work-structure/holiday-lists/'.$holiday->id) }}">
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
        </div>
    </div>
        @if ($holidays->hasPages())
        <div class="card-footer">
            {{ $holidays->links() }}
        </div>
        @endif
    </div>
    <div class="modal show" id="create-modal" tabindex="-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <form action="{{ url('work-structure/holiday-lists') }}" method="POST">
                    @csrf
                    <div class="card card-themed card-transparent mb-0">
                        <div class="modal-header">
                            <h3 class="modal-title">New Holiday</h3>
                            <div class="modal-options">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="modal-content">
                            <div class="container">
                                <div class="form-group">
                                    <label for="">Holiday Name <span class="text-danger">*</span></label>
                                    <input type="text" required="required" class="form-control" name="holiday_name" value="{{ old('holiday_name') }}">
                                </div>

                                <div class="form-group">
                                    <label for="">Holiday Type <span class="text-danger">*</span></label>
                                    <select name="holiday_type" class="form-control" required>
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach (config('global.holiday_types') as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="mas_region_id">Region <span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control" style="width: 100%;" name="mas_region_id[]" data-placeholder="Choose many.." multiple>
                                        @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->region_name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mas_region_id">Date <span class="text-danger">*</span></label>
                                    <div class="input-daterange input-group" data-date-format="yyyy-mm-dd" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                        <input type="date" class="form-control" id="example-daterange1" name="start_date" placeholder="Start Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required>
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text font-w600">to</span>
                                        </div>
                                        <input type="date" class="form-control" id="example-daterange2" name="end_date" placeholder="End Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check"></i> Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-modal" tabindex="-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card card-themed card-transparent mb-0">
                        <div class="modal-header">
                            <h3 class="card-title">Edit Holiday</h3>
                            <div class="card-options">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="container">
                                <div class="form-group">
                                    <label for="holiday_name">Holiday Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="holiday_name">
                                </div>
                                <div class="form-group">
                                    <label for="holiday_type">Holiday Type <span class="text-danger">*</span></label>
                                    <select name="holiday_type" class="form-control" required>
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach (config('global.holiday_types') as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="region">Region <span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control region-dropdown" style="width: 100%;" name="mas_region_id[]" data-placeholder="Choose many.." multiple required>
                                        @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->region_name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Date <span class="text-danger">*</span></label>
                                    <div class="input-daterange input-group" data-date-format="yyyy-mm-dd" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                        <input type="text" class="form-control" id="example-daterange1" name="start_date" placeholder="Start Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required>
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text font-w600">to</span>
                                        </div>
                                        <input type="text" class="form-control" id="example-daterange2" name="end_date" placeholder="End Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check"></i> Update
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.includes.delete-modal')
    @endsection
    @push('page_scripts')
    <script>
        $(document).ready(function() {
            $('.edit-btn').click(function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var holidayname = $(this).data('holiday');
                var holidaytype = $(this).data('type');
                var selectedRegions = $(this).data('regions');
                var start_date = $(this).data('start');
                var end_date = $(this).data('end');

                var modal = $('#edit-modal');
                modal.find('form').attr('action', url);
                modal.find('input[name=holiday_name]').val(holidayname);
                modal.find('select[name=holiday_type]').val(holidaytype);
                modal.find('.region-dropdown').val(selectedRegions).trigger('change');
                modal.find('input[name=start_date]').val(start_date);
                modal.find('input[name=end_date]').val(end_date);
                modal.modal('show');
            });
        });

        $(function() {
            $('.js-select2').select2();
        });
    </script>
    @endpush