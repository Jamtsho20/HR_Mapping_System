<label for=""><strong>SIFA Nominations</strong></label>
<small>(s)<i> (I hereby nominate the person(s) mentioned below, who is/are member(s) of my family, to have the conferred right to claim the retirement and SIFA benefit upon my demise, as per the percentage of shares prescribed)</i></small>
<br><br>
<div class="tab-pane">
    <div class="table-responsive criteria">
        <table id="sifa_nomination" class="table table-condensed table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th>Name</th>
                    <th>Relationship</th>
                    <th>CID</th>
                    <th>Percentage of Share</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($sifaNominations) && count($sifaNominations) == 0)
                <tr>
                    <td class="text-center">
                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                    </td>
                    <td>
                        <input type="text" name="sifa_nomination[AAAAA][nominee_name]" class="form-control form-control-sm resetKeyForNew" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_nomination[AAAAA][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_nomination[AAAAA][cid_number]" class="form-control form-control-sm resetKeyForNew" required>
                    </td>
                    <td>
                        <input type="number" name="sifa_nomination[AAAAA][percentage_of_share]" class="form-control form-control-sm resetKeyForNew" required>
                    </td>
                </tr>
                @else
                @foreach ($sifaNominations as $key => $value)
                <tr>
                    <td class="text-center">
                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        <input type="hidden" name="sifa_nomination[AAAAA{{ $key }}][id]" value="{{ $value->id }}">
                    </td>
                    <td>
                        <input type="text" name="sifa_nomination[AAAAA{{ $key }}][nominee_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][nominee_name]', $value->nominee_name) }}" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_nomination[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][relation_with_employee]', $value->relation_with_employee) }}" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_nomination[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][cid_number]', $value->cid_number) }}" required>
                    </td>
                    <td>
                        <input type="number" name="sifa_nomination[AAAAA{{ $key }}][percentage_of_share]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_nomination[AAAAA'.$key.'][percentage_of_share]', $value->percentage_of_share) }}" required>
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
</div>
