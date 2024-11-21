<label for=""><strong>SIFA Dependents</strong></label>
<small>(s)<i>(I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and responsible for any legal and financial damages arising thereafter)</small></i>
<br><br>

<div class="table-responsive criteria">
    <table id="sifa_dependent" class="table table-condensed table-striped table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th class="text-center">#</th>
                <th width="25%">Dependent Name</th>
                <th width="50%">Relationship with Employee</th>
                <th width="25%">CID</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">
                    <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][dependent_name]" placeholder="Name" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][relation_with_employee]" placeholder="Father,Mother,Wife,.." required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][cid_number]" placeholder="Enter their CID" required>
                </td>
            </tr>
            <tr class="notremovefornew">
                <td colspan="3"></td>
                <td class="text-right">
                    <a href="#" class="add-table-row btn btn-info btn-xs btn-xs-custom"><i class="fa fa-plus"></i> New Row</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    // Keep track of the row count
    let rowCount = 1;

    // Add a new row
    $('.add-table-row').click(function(e) {
        e.preventDefault();

        // Create a new row with incremented index for name attributes
        let newRow = `<tr>
                        <td class="text-center">
                            <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[${rowCount}][dependent_name]" placeholder="Name" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[${rowCount}][relation_wth_employee]" placeholder="Father,Mother,Wife,.." required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[${rowCount}][cid_number]" placeholder="Enter their CID" required>
                        </td>
                    </tr>`;

        // Append the new row to the table
        $('#sifa_dependent tbody').append(newRow);

        // Increment the row count
        rowCount++;
    });

    // Delete a row
    $('#sifa_dependent').on('click', '.delete-table-row', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
    });
</script>