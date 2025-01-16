<div class="tab-pane">
    <div class="table-responsive">
        <table id="trainings" class="table table-condensed table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th>Training Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration (days)</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Certificate</th>
                </tr>
            </thead>
            <tbody>
                @forelse(isset($employee) ? $employee->empTrainings : [] as $key => $value)
                    <tr class="training-row">
                        <td class="text-center">
                            <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[{{ $key }}][title]"
                                value="{{ old('trainings.' . $key . '.title', $value->title) }}" placeholder="Title">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[{{ $key }}][start_date]"
                                value="{{ old('trainings.' . $key . '.start_date', $value->start_date) }}">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[{{ $key }}][end_date]"
                                value="{{ old('trainings.' . $key . '.end_date', $value->end_date) }}">
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[{{ $key }}][duration]"
                                value="{{ old('trainings.' . $key . '.duration', $value->duration) }}"
                                placeholder="No of days">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[{{ $key }}][location]"
                                value="{{ old('trainings.' . $key . '.location', $value->location) }}"
                                placeholder="Location">
                        </td>
                        <td class="text-center">
                            <textarea class="form-control form-control-sm resetKeyForNew"
                                name="trainings[{{ $key }}][description]"
                                value="{{ old('trainings.' . $key . '.description', $value->description) }}"
                                placeholder="Description">{{ $value->description }}</textarea>
                        </td>
                        <td class="text-center">
                            <input type="file" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[{{ $key }}][certificate]">
                            @if($value->certificate)
                                <div class="mt-2">
                                    <a href="{{ asset($value->certificate) }}" target="_blank" class="btn btn-link">
                                        <i class="fas fa-file-alt"></i> View
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="training-row">
                        <td class="text-center">
                            <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[0][title]" value="{{ old('trainings.0.title') }}" placeholder="Title">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[0][start_date]" value="{{ old('trainings.0.start_date') }}">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[0][end_date]" value="{{ old('trainings.0.end_date') }}">
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[0][duration]" value="{{ old('trainings.0.duration') }}"
                                placeholder="No of days">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[0][location]" value="{{ old('trainings.0.location') }}"
                                placeholder="Location">
                        </td>
                        <td class="text-center">
                            <textarea class="form-control form-control-sm resetKeyForNew" name="trainings[0][description]"
                                placeholder="Description">{{ old('trainings.0.description') }}</textarea>
                        </td>
                        <td class="text-center">
                            <input type="file" class="form-control form-control-sm resetKeyForNew"
                                name="trainings[0][certificate]">
                        </td>
                    </tr>
                @endforelse
                <tr class="notremovefornew">
                    <td colspan="7"></td>
                    <td class="text-right">
                        <a href="#" class="add-table-row btn btn-sm btn-primary" style="font-size: 13px"><i
                                class="fa fa-plus"></i> Add New Row</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>