@extends('layouts.app')
@section('page-title', 'Hierarchy')
@section('content')
<form action="{{url('system-setting/hierarchies/' .$hierarchy->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">
            <div class="row">
                <div class="form-group col-4">
                    <label for="hiererchy">Hierarchy Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="hierarchy_name" value="{{ $hierarchy->hierarchy_name }}" required="required">
                </div>
                <div class="form-group col-4">
                    <label for="">Level <span class="text-danger">*</span></label>
                    <select class="form-control" name="level">
                        <option value="" disabled selected hidden>Select Level</option>
                        <option value="1" {{ $hierarchy->level == 1 ? 'selected' : '' }}>Level 1</option>
                        <option value="2" {{ $hierarchy->level == 2 ? 'selected' : '' }}>Level 2</option>
                        <option value="3" {{ $hierarchy->level == 3 ? 'selected' : '' }}>Level 3</option>

                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="">Value <span class="text-danger">*</span></label>
                    <select class="form-control" name="value">
                        <option value="" disabled selected hidden>Select Level</option>
                        <option value="1" {{ $hierarchy->value == 1 ? 'selected' : '' }}>Immediate Supervisor</option>
                        <option value="2" {{ $hierarchy->value == 2 ? 'selected' : '' }}>Section Head</option>
                        <option value="3" {{ $hierarchy->value == 3 ? 'selected' : '' }}>Department Head</option>
                        <option value="4" {{ $hierarchy->value == 4 ? 'selected' : '' }}>Management</option>
                        <option value="5" {{ $hierarchy->value == 5 ? 'selected' : '' }}>Human Resource</option>
                        <option value="6" {{ $hierarchy->value == 6 ? 'selected' : '' }}>Finance Head</option>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="text" value="{{$hierarchy->start_date}}" class="js-datepicker form-control js-datepicker" name="start_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>

                <div class="form-group col-4">
                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                    <input type="text" value="{{$hierarchy->end_date}}" class="js-datepicker form-control js-datepicker" name="end_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>

                <div class="form-group col-4">
                    <label for="">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status">
                        <option value="" disabled selected hidden>Select Level</option>
                        <option value="1"  {{ $hierarchy->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $hierarchy->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> UPDATE
        </button>
        <a href="{{ url('system-setting/hierarchies') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush