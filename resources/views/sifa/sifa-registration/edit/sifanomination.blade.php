<label><strong>SIFA BENEFIT NOMINATION</strong></label>
<small><i>(I hereby nominate the person(s) mentioned below to have the conferred rights to claim my SIFA benefits upon my demise, as per the percentage of shares prescribed)</i></small>
<br><br>

<div class="table-responsive">
    <table id="sifa_nomination" class="table table-bordered table-striped table-sm">
        <thead class="thead-light">
            <tr>
                <th class="text-center">#</th>
                <th>Name</th>
                <th>Relationship</th>
                <th>CID</th>
                <th>Percentage of Share</th>
                <th>Attachments(CID/Birth Certificate)</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="nomination-table-body">
            @if (!empty($sifaNominations))
                @foreach ($sifaNominations as $key => $value)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td><input type="text" name="sifa_nomination[AAAAA{{ $key }}][nominee_name]" class="form-control form-control-sm" value="{{ $value->nominee_name }}" required></td>
                        <td><input type="text" name="sifa_nomination[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm" value="{{ $value->relation_with_employee }}" required></td>
                        <td><input type="text" name="sifa_nomination[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm" value="{{ $value->cid_number }}" required></td>
                        <td><input type="number" name="sifa_nomination[AAAAA{{ $key }}][percentage_of_share]" class="form-control form-control-sm" value="{{ $value->percentage_of_share }}" required>
                            <input type="hidden" name="sifa_nomination[AAAAA{{ $key }}][id]" value="{{ $value->id }}">
                            <input type="hidden" name="sifa_nomination[AAAAA{{ $key }}][existing_attachment]" value="{{ $value->attachment }}">
                        </td>
                        <td>
                            @if (!empty($value->attachment))
                                <div class="mb-2">
                                    <a href="{{ asset('images/sifa/' . basename($value->attachment)) }}" target="_blank">View Document</a>
                                </div>
                            @endif
                            <input type="file" name="sifa_nomination[AAAAA{{ $key }}][attachment]" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right">
                    <button type="button" class="btn btn-info btn-sm" id="add-row"><i class="fa fa-plus"></i> Add Row</button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    let rowIndex = {{ isset($sifaNominations) ? count($sifaNominations) : 0 }};

    document.getElementById('add-row').addEventListener('click', function () {
        const tableBody = document.getElementById('nomination-table-body');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td class="text-center">${rowIndex + 1}</td>
            <td><input type="text" name="sifa_nomination[AAAAA${rowIndex}][nominee_name]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="sifa_nomination[AAAAA${rowIndex}][relation_with_employee]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="sifa_nomination[AAAAA${rowIndex}][cid_number]" class="form-control form-control-sm" required></td>
            <td><input type="number" name="sifa_nomination[AAAAA${rowIndex}][percentage_of_share]" class="form-control form-control-sm" required></td>
            <td><input type="file" name="sifa_nomination[AAAAA${rowIndex}][attachment]" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
        `;
        tableBody.appendChild(newRow);
        rowIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>
