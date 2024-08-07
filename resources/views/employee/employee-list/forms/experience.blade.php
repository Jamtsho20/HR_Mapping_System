<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<div class="tab-pane" id="experience-form">
        <form action="">
            <div class="card">
                <div class="card-body">
                    <div class="dataTables_scroll">
                        <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                            <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                                <table class="table table-condensed table-bordered table-striped table-sm" id="experience-datatable table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="font-size: 14px; text-transform: none;">#</th>
                                            <th style="font-size: 14px; text-transform: none;">Organization</th>
                                            <th style="font-size: 14px; text-transform: none;">Country</th>
                                            <th style="font-size: 14px; text-transform: none;">Designation</th>
                                            <th style="font-size: 14px; text-transform: none;">Start Date</th>
                                            <th style="font-size: 14px; text-transform: none;">End Date</th>
                                            <th style="font-size: 14px; text-transform: none;">Description</th>
                                        </tr>
                                    </thead>    
                                    <tbody>
                                        <tr class="experience-row">
                                            <td class="text-center">
                                                <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control form-control-sm" name="organization" placeholder="Organization" required="required" style="font-size: 14px;">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control form-control-sm" name="country" placeholder="Country " required="required" style="font-size: 14px;">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control form-control-sm" name="designation" placeholder="Designation" required="required" style="font-size: 14px;">
                                            </td>
                                            <td class="text-center">
                                                <input type="date" class="form-control form-control-sm" name="start_date" placeholder="Start date" required="required" style="font-size: 14px;">
                                            </td>
                                            <td class="text-center">
                                                <input type="date" class="form-control form-control-sm" name="end_date" placeholder="End date" required="required" style="font-size: 14px;">
                                            </td>
                                            <td class="text-center">
                                                <textarea class="form-control form-control-sm" name="description" placeholder="description" required="required" style="font-size: 14px;"></textarea>
                                            </td>
                                        </tr>
                                        <tr class="notremovefornew">
                                            <td colspan="7" class="text-right">
                                                <a href="#" class="add-experience-row btn btn-sm btn-info" style="font-size: 12px;"><i class="fa fa-plus"></i> Add New Row</a>
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
            // Function to add a new row in the experience form
            $('#experience-form .add-experience-row').click(function(e) {
                e.preventDefault();

                // Clone the first row
                var newRow = $('#experience-form table tbody tr.experience-row:first').clone();

                // Clear the input values in the new row
                newRow.find('input, textarea').val('');

                // Insert the new row before the 'Add New Row' button row
                newRow.insertBefore('#experience-form table tbody tr.notremovefornew');
            });

            // Function to delete a row
            $('#experience-form').on('click', '.delete-table-row', function(e) {
                e.preventDefault();

                // Remove the row
                $(this).closest('tr').remove();
            });
        });
    </script>
