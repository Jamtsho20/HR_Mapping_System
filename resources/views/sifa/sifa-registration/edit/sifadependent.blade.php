<label for=""><strong>SIFA Dependents</strong></label>
<small>(s)<i>(I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and responsible for any legal and financial damages arising thereafter)</i></small>
<br><br>
<div class="tab-pane">
    <div class="table-responsive">
        <table id="sifa_dependent" class="table table-condensed table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th>Dependent Name</th>
                    <th>Relationship with Employee</th>
                    <th>CID</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($sifaDependents) && count($sifaDependents) == 0)
                <tr>
                    <td class="text-center">
                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                    </td>
                    <td>
                        <input type="text" name="sifa_dependents[AAAAA][dependent_name]" class="form-control form-control-sm resetKeyForNew" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_dependents[AAAAA][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_dependents[AAAAA][cid_number]" class="form-control form-control-sm resetKeyForNew" required>
                    </td>
                </tr>
                @else
                @foreach ($sifaDependents as $key => $value)
                <tr>
                    <td class="text-center">
                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        <input type="hidden" name="sifa_dependents[AAAAA{{ $key }}][id]" value="{{ $value->id }}">
                    </td>
                    <td>
                        <input type="text" name="sifa_dependents[AAAAA{{ $key }}][dependent_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_dependents[AAAAA'.$key.'][dependent_name]', $value->dependent_name) }}" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_dependents[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_dependents[AAAAA'.$key.'][relation_with_employee]', $value->relation_with_employee) }}" required>
                    </td>
                    <td>
                        <input type="text" name="sifa_dependents[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm resetKeyForNew" value="{{ old('sifa_dependents[AAAAA'.$key.'][cid_number]', $value->cid_number) }}" required>
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
</div>
