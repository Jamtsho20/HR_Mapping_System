<div class="card ">
    <label for=""><strong>SIFA Nominations</strong></label><small>(s)<i> (I hereby nominate the person(s) mentioned below, who is/are member(s) of my family, to have the conferred right to claim the retirement and SIFA benefit upon my demise, as per the percentage of shares prescribed)</i></small>
    <br><br>
    <div class="table-responsive criteria">
        <table id="sifa_nomination" class="table table-condensed table-striped table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center">#</th>
                    <th width="25%">Name</th>
                    <th width="25%">Relationship</th>
                    <th width="25%">CID</th>
                    <th width="25%">Percentage of Share</th>
                </tr>
            </thead>
            <tbody id="sifa_nomination_table">
                <tr>
                    <td class="text-center">
                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="sifa_nomination[0][nominee_name]" placeholder="Name" required></td>
                    <td><input type="text" class="form-control form-control-sm" name="sifa_nomination[0][relation_with_employee]" placeholder="Father, Mother, Brother..." required></td>
                    <td><input type="text" class="form-control form-control-sm" name="sifa_nomination[0][cid_number]" placeholder="Enter their CID" required></td>
                    <td><input type="number" class="form-control form-control-sm" name="sifa_nomination[0][percentage_of_share]" placeholder="Share (%)" required></td>
                </tr>

                <tr class="notremovefornew">
                    <td colspan="4"></td>
                    <td class="text-right">
                        <a href="#" class="add-table-row btn btn-info btn-xs btn-xs-custom"><i class="fa fa-plus"></i> New Row</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        let rowIndex = 1; // Start from 1 since 0 is already used

        // Add new row on button click
        document.querySelector('.add-table-row').addEventListener('click', function(e) {
            e.preventDefault();

            // Create a new table row with incremented index
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td class="text-center">
                <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
            </td>
            <td><input type="text" class="form-control form-control-sm" name="sifa_nomination[${rowIndex}][nominee_name]" placeholder="Name" required></td>
            <td><input type="text" class="form-control form-control-sm" name="sifa_nomination[${rowIndex}][relation_with_employee]" placeholder="Father, Mother, Brother..." required></td>
            <td><input type="text" class="form-control form-control-sm" name="sifa_nomination[${rowIndex}][cid_number]" placeholder="Enter their CID" required></td>
            <td><input type="number" class="form-control form-control-sm" name="sifa_nomination[${rowIndex}][percentage_of_share]" placeholder="Share (%)" required></td>
        `;

            // Append the new row to the table
            document.getElementById('sifa_nomination_table').insertBefore(newRow, document.querySelector('.notremovefornew'));

            // Increment the rowIndex for the next new row
            rowIndex++;
        });

        // Delete row functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-table-row')) {
                e.target.closest('tr').remove();
            }
        });
    </script>