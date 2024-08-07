<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="tab-pane" id="training-form">
    <form action="">
        <div class="card">
            <div class="card-body">
                <div class="dataTables_scroll">
                    <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                        <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                            <table class="table table-condensed table-bordered table-striped table-sm" id="training-datatable table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="font-size: 13px; text-transform: none;">#</th>
                                        <th style="font-size: 13px; text-transform: none;">Training Title</th>
                                        <th style="font-size: 13px; text-transform: none;">Training Description</th>
                                        <th style="font-size: 13px; text-transform: none;">Training Date</th>
                                        <th style="font-size: 13px; text-transform: none;">Training Duration (days)</th>
                                        <th style="font-size: 13px; text-transform: none;">Training Location</th>
                                        <th style="font-size: 13px; text-transform: none;">Training Certificate</th>
                                    </tr>
                                </thead>    
                                <tbody>
                                    <tr class="training-row">
                                        <td class="text-center">
                                            <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" class="form-control form-control-sm" name="training_title" placeholder="Title" required="required" style="font-size: 13px;">
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control form-control-sm" name="training_description" placeholder="Description" required="required" style="font-size: 13px;"></textarea>
                                        </td>
                                        <td class="text-center">
                                            <input type="date" class="form-control form-control-sm" name="training_date" required="required" style="font-size: 13px;">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" class="form-control form-control-sm" name="training_duration" placeholder="No of days" required="required" style="font-size: 13px;">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" class="form-control form-control-sm" name="training_location" placeholder="Location" required="required" style="font-size: 13px;">
                                        </td>
                                        <td class="text-center">
                                            <input type="file" class="form-control form-control-sm" name="training_certificate" required="required" style="font-size: 13px;">
                                        </td>
                                    </tr>
                                    <tr class="notremovefornew">
                                        <td colspan="6"></td>
                                        <td class="text-right">
                                            <a href="#" class="add-training-row btn btn-sm btn-info" style="font-size: 12px;"><i class="fa fa-plus"></i> Add New Row</a>
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
        // Function to add a new row in the training form
        $('#training-form .add-training-row').click(function(e) {
            e.preventDefault();

            // Clone the first row
            var newRow = $('#training-form table tbody tr.training-row:first').clone();

            // Clear the input values in the new row
            newRow.find('input, textarea').val('');

            // Insert the new row before the 'Add New Row' button row
            newRow.insertBefore('#training-form table tbody tr.notremovefornew');
        });

        // Function to delete a row
        $('#training-form').on('click', '.delete-table-row', function(e) {
            e.preventDefault();

            // Remove the row
            $(this).closest('tr').remove();
        });
    });
</script>

