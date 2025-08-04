<label for=""><strong>SIFA BENEFIT NOMINATION</strong></label><small>(s)<i> (I hereby nominate the person(s) mentioned below to have the conferred rights to claim my SIFA benefits upon my demise, as per the percentage of shares prescribed)</i></small>
<br><br>
<div class="table-responsive criteria">
    <table id="sifa_nomination" class="table table-condensed table-striped table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th class="text-center">#</th>
                <th width="20%">Name</th>
                <th width="20%">Relationship</th>
                <th width="20%">CID</th>
                <th width="20%">Percentage of Share</th>
                <th width="20%">Attachments</th>
            </tr>
        </thead>
        <tbody>
            @if (old('sifa_nomination') == '')
            <tr>
                <td class="text-center">
                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="sifa_nomination[AAAAA][nominee_name]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="text" name="sifa_nomination[AAAAA][relation_with_employee]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="text" name="sifa_nomination[AAAAA][cid_number]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="number" name="sifa_nomination[AAAAA][percentage_of_share]" class="form-control form-control-sm resetKeyForNew">
                </td>
                <td>
                    <input type="file" name="sifa_nomination[AAAAA][attachment]" class="form-control form-control-sm resetKeyForNew">
                </td>

            </tr>
            @else
            @foreach (old('sifa_nomination') as $key => $value)
            <tr>
                <td class="text-center">
                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </td>
                <td>
                    <input type="text" name="sifa_nomination[AAAAA{{ $key }}][nominee_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][name]', $value['nominee_name'] ?? '') }}">
                </td>
                <td>
                    <input type="text" name="sifa_nomination[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][relation_with_employee]', $value['relation_with_employee'] ?? '') }}">
                </td>
                <td>
                    <input type="text" name="sifa_nomination[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][cid_number]', $value['cid_number'] ?? '') }}">
                </td>
                <td>
                    <input type="number" name="sifa_nomination[AAAAA{{ $key }}][percentage_of_share]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][percentage_of_share]', $value['percentage_of_share'] ?? '') }}">
                </td>
                <td>
                    <input type="file" name="sifa_nomination[AAAAA{{ $key }}][attachment]" class="form-control form-control-sm resetKeyForNew">
                </td>

            </tr>
            @endforeach
            @endif
            <tr class="notremovefornew">
                <td colspan="5"></td>
                <td class="text-right">
                    <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px"><i class="fa fa-plus"></i> Add New Row</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>