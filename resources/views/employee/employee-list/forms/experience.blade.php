<div class="tab-pane">
    <div class="table-responsive">
        <table id="experiences" class="table table-condensed table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th>Organization</th>
                    <th>Country</th>
                    <th>Designation</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse(isset($employee->empExperiences) ? $employee->empExperiences : [] as $key => $value)
                    <tr>
                        <td class="text-center">
                            <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][organization]"
                                value="{{ old('experiences.AAAAA.organization', $value->organization) }}"
                                placeholder="Organization">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][place]" value="{{ old('experiences.AAAAA.place', $value->place) }}"
                                placeholder="Country">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][designation]"
                                value="{{ old('experiences.AAAAA.designation', $value->designation) }}"
                                placeholder="Designation">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][start_date]"
                                value="{{ old('experiences.AAAAA.start_date', $value->start_date) }}" placeholder="Start Date">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][end_date]"
                                value="{{ old('experiences.AAAAA.end_date', $value->end_date) }}" placeholder="End Date">
                        </td>
                        <td class="text-center">
                            <textarea class="form-control form-control-sm resetKeyForNew" name="experiences[AAAAA][description]"
                                placeholder="Description">{{ old('experiences.AAAAA.description', $value->description) }}</textarea>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center">
                            <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][organization]" value="{{ old('experiences.AAAAA.organization') }}"
                                placeholder="Organization">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][place]" value="{{ old('experiences.AAAAA.place') }}"
                                placeholder="Country">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][designation]" value="{{ old('experiences.AAAAA.designation') }}"
                                placeholder="Designation">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][start_date]" value="{{ old('experiences.AAAAA.start_date') }}"
                                placeholder="Start Date">
                        </td>
                        <td class="text-center">
                            <input type="date" class="form-control form-control-sm resetKeyForNew"
                                name="experiences[AAAAA][end_date]" value="{{ old('experiences.AAAAA.end_date') }}"
                                placeholder="End Date">
                        </td>
                        <td class="text-center">
                            <textarea class="form-control form-control-sm resetKeyForNew" name="experiences[AAAAA][description]"
                                placeholder="Description">{{ old('experiences.AAAAA.description') }}</textarea>
                        </td>
                    </tr>
                @endforelse
                <tr class="notremovefornew">
                    <td colspan="6"></td>
                    <td class="text-right">
                        <a href="#" class="add-table-row btn btn-sm btn-primary" style="font-size: 13px"><i
                                class="fa fa-plus"></i> Add New Row</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>