<label><strong>LIST OF DEPENDENTS</strong></label>
<small><i>(I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and liable and take actions as per the provisions of SIFA By-laws)</i></small>
<br><br>

<div class="table-responsive">
    <table id="sifa_dependent" class="table table-bordered table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Dependent Name</th>
                <th>Relationship with Employee</th>
                <th>CID</th>
                <th>Attachments(CID/Birth Certificate)</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="dependent-table-body">
            @if (!empty($sifaDependents))
                @foreach ($sifaDependents as $key => $value)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>
                            <input type="text" name="sifa_dependents[AAAAA{{ $key }}][dependent_name]" class="form-control form-control-sm" value="{{ $value->dependent_name }}" required>
                        </td>
                        <td>
                            <input type="text" name="sifa_dependents[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm" value="{{ $value->relation_with_employee }}" required>
                        </td>
                        <td>
                            <input type="text" name="sifa_dependents[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm" value="{{ $value->cid_number }}" required>
                            <input type="hidden" name="sifa_dependents[AAAAA{{ $key }}][id]" value="{{ $value->id }}">
                            <input type="hidden" name="sifa_dependents[AAAAA{{ $key }}][existing_attachment]" value="{{ $value->attachment }}">
                        </td>
                        <td>
                            @if (!empty($value->attachment))
                                <div class="mb-2">
                                    <a href="{{ asset('images/sifa/' . basename($value->attachment)) }}" target="_blank">View Document</a>
                                </div>
                            @endif
                            <input type="file" name="sifa_dependents[AAAAA{{ $key }}][attachment]" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-dependent-row"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right">
                    <button type="button" class="btn btn-info btn-sm" id="add-dependent-row"><i class="fa fa-plus"></i> Add Row</button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    let dependentIndex = {{ isset($sifaDependents) ? count($sifaDependents) : 0 }};

    document.getElementById('add-dependent-row').addEventListener('click', function () {
        const tableBody = document.getElementById('dependent-table-body');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td class="text-center">${dependentIndex + 1}</td>
            <td><input type="text" name="sifa_dependents[AAAAA${dependentIndex}][dependent_name]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="sifa_dependents[AAAAA${dependentIndex}][relation_with_employee]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="sifa_dependents[AAAAA${dependentIndex}][cid_number]" class="form-control form-control-sm" required></td>
            <td><input type="file" name="sifa_dependents[AAAAA${dependentIndex}][attachment]" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-dependent-row"><i class="fa fa-trash"></i></button></td>
        `;
        tableBody.appendChild(newRow);
        dependentIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-dependent-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>
