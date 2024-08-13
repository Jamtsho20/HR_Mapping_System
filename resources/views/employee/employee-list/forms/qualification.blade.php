<div class="tab-pane">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="qualifications" class="table table-condensed table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th width="3%" class="text-center">#</th>
                            <th>Qualification</th>
                            <th>School/College</th>
                            <th>Completion Year</th>
                            {{-- <th>Start Date</th>
                            <th>End Date</th> --}}
                            <th>Subject/Course</th>
                            <th>Aggregate Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                            </td>
                            <td class="text-center">
                                <select name="qualifications[AAAAA][mas_qualification_id]" class="form-control form-control-sm resetKeyForNew" required>
                                    <option value="" disabled selected hidden>SELECT ONE</option>
                                    @foreach($qualifications as $qualification)
                                        <option value="{{ $qualification->id }} {{ old('qualifications.AAAAA.mas_qualification_id') == $qualification->id ? 'selected' : '' }}">{{ $qualification->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control form-control-sm resetKeyForNew" id="decline-reason" name="qualifications[AAAAA][school]" value="{{ old('qualifications.AAAAA.school') }}" required></textarea>
                            </td>
                            <td class="text-center">
                                <input type="date" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][completion_year]" value="{{ old('qualifications.AAAAA.completion_year') }}" required>
                            </td>
                            {{-- <td class="text-center">
                                <input type="date" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][start_date]" value="{{ old('qualifications.AAAAA.start_date') }}" required>
                            </td>
                            <td class="text-center">
                                <input type="date" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][end_date]" value="{{ old('qualifications.AAAAA.end_date') }}" required>
                            </td> --}}
                            <td class="text-center">
                                <input type="text" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][subject]" value="{{ old('qualifications.AAAAA.subject') }}" required>
                            </td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][aggregate_score]" value="{{ old('qualifications.AAAAA.aggregate_score') }}" required>
                            </td>
                        </tr>
                        <tr class="notremovefornew">
                            <td colspan="6"></td>
                            <td class="text-right">
                                <a href="#" class="add-table-row btn btn-sm btn-primary" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


