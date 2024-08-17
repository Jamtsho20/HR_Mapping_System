<div class="tab-pane">
    <div class="card">
        <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="trainings" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Training Title</th>
                                    <th>Start Date</th>
                                    <th>End Date Date</th>
                                    <th>Duration (days)</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>    
                            <tbody>
                                <tr class="training-row">
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm resetKeyForNew" name="trainings[AAAAA][title]" value="{{ old('trainings.AAAAA.title') }}" placeholder="Title">
                                    </td>
                                    <td class="text-center">
                                        <input type="date" class="form-control form-control-sm resetKeyForNew" name="trainings[AAAAA][start_date]" value="{{ old('trainings.AAAAA.start_date') }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="date" class="form-control form-control-sm resetKeyForNew" name="trainings[AAAAA][end_date]" value="{{ old('trainings.AAAAA.end_date') }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm resetKeyForNew" name="trainings[AAAAA][duration]" value="{{ old('trainings.AAAAA.duration') }}" placeholder="No of days">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm resetKeyForNew" name="trainings[AAAAA][location]" value="{{ old('trainings.AAAAA.location') }}" placeholder="Location">
                                    </td>
                                    <td class="text-center">
                                        <textarea class="form-control form-control-sm resetKeyForNew" name="trainings[AAAAA][description]" value="{{ old('trainings.AAAAA.description') }}" placeholder="Description"></textarea>
                                    </td>
                                    <td class="text-center">
                                        <input type="file" class="form-control form-control-sm resetKeyForNew" name="trainings[AAAAA][certificate]">
                                    </td>
                                </tr>
                                <tr class="notremovefornew">
                                    <td colspan="7"></td>
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



