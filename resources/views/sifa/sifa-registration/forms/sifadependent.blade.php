<label for=""><strong>SIFA Dependents</strong></label><small>(s)<i>(I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and responsible for any legal and financial damages arising thereafter)</small></i>
    <br><br>
    <div class="table-responsive criteria">
        <table id = "sifa_dependent" class="table table-condensed table-striped table-bordered table-sm">
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
                    <td class="text-center"><a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>    
                    <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][name]" placeholder="Name" required></td>
                    <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][relationship]" placeholder="Father,Mother,Wife,.." required></td>
                    <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][cid]" placeholder="Enter their CID"  required></td>
                </tr>
                <tr class="notremovefornew">
                    <td colspan="3"></td>
                    <td class="text-right"><a href="#" class="add-table-row btn btn-info btn-xs btn-xs-custom"><i class="fa fa-plus"></i> New Row</a></td>
                </tr>
            </tbody>
        </table>
    </div>