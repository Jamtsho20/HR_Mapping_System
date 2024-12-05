<label for=""><strong>SIFA Retirement And Nominations</strong></label><small>(s)<i> (I hereby nominate the person(s) mentioned below, who is/are member(s) of my family, to have the conferred right to claim the retirement benefit upon my demise, as per the percentage of shares prescribed)</i></small><span class="text-danger">*</span></label>
<br><br>
<div class="table-responsive criteria">
    <table id="sifa_retirement_and_nomination" class="table table-condensed table-striped table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th class="text-center">#</th>
                <th width="25%">Name</th>
                <th width="25%">Relationship</th>
                <th width="25%">CID</th>
                <th width="25%">Percentage of Share</th>
            </tr>
        </thead>
        <tbody>
            @if (old('sifa_retirement_and_nomination') == '')
            <tr>
                <td class="text-center">
                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="sifa_retirement_and_nomination[AAAAA][nominee_name]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="text" name="sifa_retirement_and_nomination[AAAAA][relation_with_employee]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="text" name="sifa_retirement_and_nomination[AAAAA][cid_number]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="number" name="sifa_retirement_and_nomination[AAAAA][percentage_of_share]" class="form-control form-control-sm resetKeyForNew">
                </td>
            </tr>
            @else
            @foreach (old('sifa_retirement_and_nomination') as $key => $value)
            <tr>
                <td class="text-center">
                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="sifa_retirement_and_nomination[AAAAA{{ $key }}][nominee_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_retirement_and_nomination[AAAAA'.$key.'][name]', $value['nominee_name'] ?? '') }}">
                </td>
                <td>
                    <input type="text" name="sifa_retirement_and_nomination[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_retirement_and_nomination[AAAAA'.$key.'][relation_with_employee]', $value['relation_with_employee'] ?? '') }}">
                </td>
                <td>
                    <input type="text" name="sifa_retirement_and_nomination[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_retirement_and_nomination[AAAAA'.$key.'][cid_number]', $value['cid_number'] ?? '') }}">
                </td>
                <td>
                    <input type="number" name="sifa_retirement_and_nomination[AAAAA{{ $key }}][percentage_of_share]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_retirement_and_nomination[AAAAA'.$key.'][percentage_of_share]', $value['percentage_of_share'] ?? '') }}">
                </td>
            </tr>
            @endforeach
            @endif
            <tr class="notremovefornew">
                <td colspan="4"></td>
                <td class="text-right">
                    <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

