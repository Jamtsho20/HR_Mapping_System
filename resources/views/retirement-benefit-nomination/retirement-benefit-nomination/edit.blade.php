@extends('layouts.app')
@section('page-title', 'Edit Retirement Benefit Nomination')
@section('content')

<form action="{{ route('retirement-benefit-nomination.update', $retirementNomination->id) }}" method="POST" class="button-control" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            <input type="hidden" name="employee_id" value="{{ $retirementNomination->mas_employee_id }}">
            <input type="hidden" name="status" id="status" value="{{ $retirementNomination->status }}">

            @include('sifa.sifa-registration.forms.personalinfo')

            <label><strong>Retirement Benefit Nomination</strong></label>
            <br><br>

            <div class="table-responsive criteria">
                <table id="retirement_nomination" class="table table-bordered table-striped table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Relationship</th>
                            <th>CID</th>
                            <th>Percentage of Share</th>
                            <th>Attachments (CID/Birth Certificate)</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="retirement_nomination_table">
                        @if (!empty($retirementNomination->details))
                            @foreach ($retirementNomination->details as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td><input type="text" name="retirement_benefit[AAAAA{{ $key }}][nominee_name]" class="form-control form-control-sm" value="{{ $value->nominee_name }}" required></td>
                                    <td><input type="text" name="retirement_benefit[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm" value="{{ $value->relation_with_employee }}" required></td>
                                    <td><input type="text" name="retirement_benefit[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm" value="{{ $value->cid_number }}" required></td>
                                    <td>
                                        <input type="number" name="retirement_benefit[AAAAA{{ $key }}][percentage_of_share]" class="form-control form-control-sm" value="{{ $value->percentage_of_share }}" required>
                                        <input type="hidden" name="retirement_benefit[AAAAA{{ $key }}][id]" value="{{ $value->id }}">
                                        <input type="hidden" name="retirement_benefit[AAAAA{{ $key }}][existing_attachment]" value="{{ $value->attachment }}">
                                    </td>
                                    <td>
                                        @if (!empty($value->attachment))
                                            <div class="mb-2">
                                                <a href="{{ asset($value->attachment) }}" target="_blank">View Document</a>
                                            </div>
                                        @endif
                                        <input type="file" name="retirement_benefit[AAAAA{{ $key }}][attachment]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
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
                                <button type="button" class="btn btn-info btn-sm" id="add-retirement-row"><i class="fa fa-plus"></i> Add Row</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="form-group d-flex justify-content-center">
                <button type="submit" class="btn btn-primary me-3">Update</button>
                <a href="{{ route('retirement-benefit-nomination.index') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
    let rowIndex = {{ isset($retirementNomination->details) ? count($retirementNomination->details) : 0 }};

    document.getElementById('add-retirement-row').addEventListener('click', function () {
        const tableBody = document.getElementById('retirement_nomination_table');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td class="text-center">${rowIndex + 1}</td>
            <td><input type="text" name="retirement_benefit[AAAAA${rowIndex}][nominee_name]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="retirement_benefit[AAAAA${rowIndex}][relation_with_employee]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="retirement_benefit[AAAAA${rowIndex}][cid_number]" class="form-control form-control-sm" required></td>
            <td><input type="number" name="retirement_benefit[AAAAA${rowIndex}][percentage_of_share]" class="form-control form-control-sm" required></td>
            <td><input type="file" name="retirement_benefit[AAAAA${rowIndex}][attachment]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png"></td>
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

@endsection
