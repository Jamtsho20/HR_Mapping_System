<div class="card-body card-box">
    <label for=""><strong>SIFA Nomination</strong></label><small>(s)<i> (I hereby nominate the person(s) mentioned below, who is/are member(s) of my family, to have the conferred right to claim the retirement and SIFA benefit upon my demise, as per the percentage of shares prescribed)</i></small>
    <br><br>
    <div class="table-responsive criteria">
        <table id="sifa_nomination" class="table table-condensed table-striped table-bordered table-sm">
            <thead class="thead-light">
                <th class="text-center">#</th>
                <th width="25%">Name</th>
                <th width="25%">Relationship</th>
                <th width="25%">CID</th>
                <th width="25%">Percentage of Share</th>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center"><a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>
                    <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][name]" placeholder="Name" required></td>
                    <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][relationship]" placeholder="Father,Mother,Brother.." required></td>
                    <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][cid]" placeholder="Enter their CID" required></td>
                    <td><input type="number" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][percentage]" placeholder="Share (%)" required></td>
                </tr>
                <tr class="notremovefornew">
                    <td colspan="4"></td>
                    <td class="text-right"><a href="#" class="add-table-row btn btn-info btn-xs btn-xs-custom"><i class="fa fa-plus"></i> New Row</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>