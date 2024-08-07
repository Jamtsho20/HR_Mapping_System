<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="tab-pane" id="education-form">
    <form action="">
        <div class="card">
            <div class="card-body">
                <div class="dataTables_scroll">
                    <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                        <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                            <table class="table table-condensed table-bordered table-striped table-sm" id="education-datatable table-responsive">
                                <thead>
                                    <tr width="20%" role="row">
                                        <th class="text-center" style="font-size: 13px; text-transform: none;">#</th>
                                        <th style="font-size: 13px; text-transform: none;">School/College City Address</th>
                                        <th style="font-size: 13px; text-transform: none;">Start Date</th>
                                        <th style="font-size: 13px; text-transform: none;">End Date</th>
                                        <th style="font-size: 13px; text-transform: none;">Country</th>
                                        <th style="font-size: 13px; text-transform: none;">Field of Study</th>
                                        <th style="font-size: 13px; text-transform: none;">Study Level</th>
                                        <th style="font-size: 13px; text-transform: none;">Marks Obtained</th>
                                        <th style="font-size: 13px; text-transform: none;">Documents Upload</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="education-row">
                                        <td class="text-center">
                                            <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control form-control-sm" id="decline-reason" name="school/collegeaddress" required></textarea>
                                        </td>
                                        <td class="text-center">
                                            <input type="date" class="form-control" name="startdate" required>
                                        </td>
                                        <td class="text-center">
                                            <input type="date" class="form-control" name="enddate" required>
                                        </td>
                                        <td class="text-center">
                                            <select name="country" class="form-control form-control-sm" required>
                                                <option value="">SELECT ONE</option>
                                                <option value="Bhutan">Bhutan</option>
                                                <option value="Canada">Canada</option>
                                                <option value="China">China</option>
                                                <option value="India">India</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" class="form-control form-control-sm" name="fieldofstudy" required>
                                        </td>
                                        <td class="text-center">
                                            <select name="studylevel" class="form-control form-control-sm" required>
                                                <option value="">SELECT ONE</option>
                                                <option value="PhD">PhD</option>
                                                <option value="Master">Master</option>
                                                <option value="Graduate">Graduate</option>
                                                <option value="Diploma">Diploma</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="number" class="form-control form-control-sm" name="number" required>
                                        </td>
                                        <td class="text-center">
                                            <input type="file" class="form-control form-control-sm" name="document" required>
                                        </td>
                                    </tr>
                                    <tr class="notremovefornew">
                                        <td colspan="8"></td>
                                        <td class="text-right">
                                            <a href="#" class="add-education-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        // Function to add a new row in the education form
        $('#education-form .add-education-row').click(function(e) {
            e.preventDefault();

            // Clone the first row
            var newRow = $('#education-form table tbody tr.education-row:first').clone();

            // Clear the input values in the new row
            newRow.find('input, textarea, select').val('');
            newRow.find('input[type="file"]').replaceWith('<input type="file" class="form-control form-control-sm" name="document" required>');

            // Insert the new row before the 'Add New Row' button row
            newRow.insertBefore('#education-form table tbody tr.notremovefornew');
        });

        // Function to delete a row
        $('#education-form').on('click', '.delete-table-row', function(e) {
            e.preventDefault();

            // Remove the row
            $(this).closest('tr').remove();
        });
    });
</script>

