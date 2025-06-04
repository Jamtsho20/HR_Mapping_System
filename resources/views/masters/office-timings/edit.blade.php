@extends('layouts.app')
@section('page-title', 'Edit Office Timing')
@section('content')
<form action="{{ url('master/office-timings/' . $timing->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="season">Season <span class="text-danger">*</span></label>
                        <select class="form-control" name="season" id="season" required>
                            <option value="" disabled>Select Season</option>
                            @foreach (config('global.seasons') as $key => $label)
                                <option value="{{ $key }}" {{ old('season', $timing->season) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_month">Start Month <span class="text-danger">*</span></label>
                        <select class="form-control" name="start_month" id="start_month" required>
                            <option value="" disabled>Select Start Month</option>
                            @foreach (config('global.months') as $key => $label)
                                <option value="{{ $key }}" {{ old('start_month', $timing->start_month) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_month">End Month <span class="text-danger">*</span></label>
                        <select class="form-control" name="end_month" id="end_month" required>
                            <option value="" disabled>Select End Month</option>
                            @foreach (config('global.months') as $key => $label)
                                <option value="{{ $key }}" {{ old('end_month', $timing->end_month) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_time">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="start_time" id="start_time" value="{{ old('start_time', $timing->start_time) }}" required>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="lunch_time_from">Lunch Time From <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="lunch_time_from" id="lunch_time_from" value="{{ old('lunch_time_from', $timing->lunch_time_from) }}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="lunch_time_to">Lunch Time To <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="lunch_time_to" id="lunch_time_to" value="{{ old('lunch_time_to', $timing->lunch_time_to) }}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_time">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="end_time" id="end_time" value="{{ old('end_time', $timing->end_time) }}" required>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'UPDATE',
                'cancelUrl' => url('master/office-timings'),
                'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>
@endsection
