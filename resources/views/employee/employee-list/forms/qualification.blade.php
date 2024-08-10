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
                            <th>Start Date</th>
                            <th>End Date</th>
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
                                <select name="qualifications[AAAAA][AAAAA][mas_qualification_id]" class="form-control form-control-sm resetKeyForNew" required>
                                    <option value="">SELECT ONE</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Canada">Canada</option>
                                    <option value="China">China</option>
                                    <option value="India">India</option>
                                    <option value="Other">Other</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control form-control-sm resetKeyForNew" id="decline-reason" name="qualifications[AAAAA][AAAAA][school]" required></textarea>
                            </td>
                            <td class="text-center">
                                <input type="date" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][AAAAA][startdate]" required>
                            </td>
                            <td class="text-center">
                                <input type="date" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][AAAAA][enddate]" required>
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][AAAAA][subject]" required>
                            </td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm resetKeyForNew" name="qualifications[AAAAA][AAAAA][aggregate_score]" required>
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


