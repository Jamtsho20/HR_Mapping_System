<div class="tab-pane">
    <div class="table-responsive">
        <table id="qualifications" class="table table-condensed table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th>Qualification</th>
                    <th>School/College/university</th>
                    <th>Completed On</th>
                    <th>Subject</th>
                    <th>Aggregate Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse(isset($employee) ? $employee->empQualifications : [] as $key => $value)
                    <tr>
                        <td class="text-center">
                            <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td class="text-center">
                            <select name="qualifications[{{ $key }}][mas_qualification_id]"
                                class="form-control form-control-sm resetKeyForNew" required>
                                <option value="" disabled selected hidden>SELECT ONE</option>
                                @foreach($qualifications as $qualification)
                                    <option value="{{ $qualification->id }}" {{ old('qualifications.' . $key . '.mas_qualification_id', $value->mas_qualification_id) == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[{{ $key }}][school]"
                                value="{{ old('qualifications.' . $key . '.school', $value->school) }}" required>
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[{{ $key }}][completion_year]"
                                value="{{ old('qualifications.' . $key . '.completion_year', $value->completion_year) }}"
                                required>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[{{ $key }}][subject]"
                                value="{{ old('qualifications.' . $key . '.subject', $value->subject) }}" required>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[{{ $key }}][aggregate_score]"
                                value="{{ old('qualifications.' . $key . '.aggregate_score', $value->aggregate_score) }}"
                                required>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center">
                            <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td class="text-center">
                            <select name="qualifications[AAAAA][mas_qualification_id]"
                                class="form-control form-control-sm resetKeyForNew" required>
                                <option value="" disabled selected hidden>SELECT ONE</option>
                                @foreach($qualifications as $qualification)
                                    <option value="{{ $qualification->id }}" {{ old('qualifications.AAAAA.mas_qualification_id') == $qualification->id ? 'selected' : '' }}>
                                        {{ $qualification->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[AAAAA][school]" value="{{ old('qualifications.AAAAA.school') }}" required>
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[AAAAA][completion_year]"
                                value="{{ old('qualifications.AAAAA.completion_year') }}" required>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[AAAAA][subject]" value="{{ old('qualifications.AAAAA.subject') }}"
                                required>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm resetKeyForNew"
                                name="qualifications[AAAAA][aggregate_score]"
                                value="{{ old('qualifications.AAAAA.aggregate_score') }}" required>
                        </td>
                    </tr>
                @endforelse
                <tr class="notremovefornew">
                    <td colspan="5"></td>
                    <td class="text-right">
                        <a href="#" class="add-table-row btn btn-sm btn-primary" style="font-size: 13px"><i
                                class="fa fa-plus"></i> Add New Row</a>
                    </td>
                </tr>
            </tbody>
    
        </table>
    </div>
</div>