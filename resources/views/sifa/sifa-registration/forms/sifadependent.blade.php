<label for=""><strong>LIST OF DEPENDENT</strong></label><small>(s)<i> (I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and responsible for any legal and financial damages arising thereafter.)</i></small>
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
            @if (old('sifa_dependent') == '')
            <tr>
                <td class="text-center">
                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="sifa_dependents[AAAAA][dependent_name]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="text" name="sifa_dependents[AAAAA][relation_with_employee]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="text" name="sifa_dependents[AAAAA][cid_number]" class="form-control form-control-sm resetKeyForNew">
                </td>
            </tr>
            @else
            @foreach (old('sifa_dependent') as $key => $value)
            <tr>
                <td class="text-center">
                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="sifa_dependents[AAAAA{{ $key }}][dependent_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_dependents[AAAAA'.$key.'][dependent_name]', $value['dependent_name'] ?? '') }}">
                </td>
                <td>
                    <input type="text" name="sifa_dependents[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_dependents[AAAAA'.$key.'][relation_with_employee]', $value['relation_with_employee'] ?? '') }}">
                </td>
                <td>
                    <input type="text" name="sifa_dependents[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_dependents[AAAAA'.$key.'][cid_number]', $value['cid_number'] ?? '') }}">
                </td>
            </tr>
            @endforeach
            @endif
            <tr class="notremovefornew">
                <td colspan="3"></td>
                <td class="text-right">
                    <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px"><i class="fa fa-plus"></i> Add New Row</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
