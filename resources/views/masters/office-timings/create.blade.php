@extends('layouts.app')
@section('page-title', 'Create New Office Timing')
@section('content')

<form action="{{ route('office-timings.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="season" class="form-label">Season <span class="text-danger">*</span></label>
                    <select name="season" id="season" class="form-select" required>
                        <option value="" disabled selected>-- Select Season --</option>
                        @foreach(config('global.seasons') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="start_month" class="form-label">Start Month <span class="text-danger">*</span></label>
                    <select name="start_month" id="start_month" class="form-select" required>
                        <option value="" disabled selected>-- Select --</option>
                        @foreach(['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'] as $month)
                        <option value="{{ $month }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="end_month" class="form-label">End Month <span class="text-danger">*</span></label>
                    <select name="end_month" id="end_month" class="form-select" required>
                        <option value="" disabled selected>-- Select --</option>
                        @foreach(['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'] as $month)
                        <option value="{{ $month }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label for="lunch_time_from" class="form-label">Lunch Time From <span class="text-danger">*</span></label>
                    <input type="time" name="lunch_time_from" id="lunch_time_from" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label for="lunch_time_to" class="form-label">Lunch Time To <span class="text-danger">*</span></label>
                    <input type="time" name="lunch_time_to" id="lunch_time_to" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" id="end_time" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/budget-code'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush