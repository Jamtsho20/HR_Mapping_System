@extends('layouts.app')

@section('page-title', 'Edit Pay Slab')

@section('content')
<form action="{{ url('paymaster/pay-slabs/' . $paySlab->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $paySlab->name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="effective_date">Effective Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="effective_date" value="{{ old('effective_date', $paySlab->effective_date->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="formula">Formula <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="formula" required>{{ old('formula', $paySlab->formula) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-slabs') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
<!-- Pay Slab Details Form -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pay Slab Details</h3>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                        <div class="dataTables_scroll">
                                            <div class="dataTables_scrollHead"
                                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                <div class="dataTables_scrollHeadInner"
                                                    style="box-sizing: content-box; padding-right: 0px;">
                                                    <table
                                                        class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                        id="basic-datatable table-responsive">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>Pay From</th>
                                                                <th>Pay To</th>
                                                                <th>Amount</th>
                                                                <th>Created At</th>
                                                                <th>Updated At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($details as $index => $detail)
                                                            <tr>
                                                                <td>
                                                                    <input type="number" class="form-control form-control-sm" name="detail[{{ $index }}][pay_from]" value="{{ old('detail.'.$index.'.pay_from', $detail->pay_from) }}" placeholder="Pay From" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control form-control-sm" name="detail[{{ $index }}][pay_to]" value="{{ old('detail.'.$index.'.pay_to', $detail->pay_to) }}" placeholder="Pay To" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control form-control-sm" name="detail[{{ $index }}][amount]" value="{{ old('detail.'.$index.'.amount', $detail->amount) }}" placeholder="Amount" required>
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control form-control-sm" name="detail[{{ $index }}][created_at]" value="{{ old('detail.'.$index.'.created_at', $detail->created_at ? $detail->created_at->format('Y-m-d') : '') }}" placeholder="Created At" required>
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control form-control-sm" name="detail[{{ $index }}][updated_at]" value="{{ old('detail.'.$index.'.updated_at', $detail->updated_at ? $detail->updated_at->format('Y-m-d') : '') }}" placeholder="Updated At" required>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="#" class="edit-detail-btn btn-sm btn btn-rounded btn-outline-success" 
                                                                    data-id="{{ $detail->id }}"
                                                                    data-pay-from="{{ $detail->pay_from }}"
                                                                    data-pay-to="{{ $detail->pay_to }}"
                                                                    data-amount="{{ $detail->amount }}"
                                                                    data-created-at="{{ $detail->created_at->format('Y-m-d') }}"
                                                                    data-updated-at="{{ $detail->updated_at->format('Y-m-d') }}">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                    </a>
                                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                        data-id="{{ $detail->id }}"
                                                                        data-pay-from="{{ $detail->pay_from }}"
                                                                        data-pay-to="{{ $detail->pay_to }}"
                                                                        data-amount="{{ $detail->amount }}"
                                                                        data-created-at="{{ $detail->created_at->format('Y-m-d') }}"
                                                                        data-updated-at="{{ $detail->updated_at->format('Y-m-d') }}">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            <tr class="notremovefornew">
                                                                <td colspan="7" class="text-right">
                                                                    <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                    <!-- Pagination Links -->
                                                    <div class="pagination-wrapper">
                                                        {{ $details->links() }}
                                                    </div>
                                                
                                            </div>
                                        </div>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


<!-- Edit Detail Modal -->
<div class="modal fade" id="edit-detail-modal" tabindex="-1" aria-labelledby="editDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailLabel">Edit Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDetailForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="pay_from" class="form-label">Pay From</label>
                        <input type="number" class="form-control" id="pay_from" name="pay_from" required>
                    </div>
                    <div class="mb-3">
                        <label for="pay_to" class="form-label">Pay To</label>
                        <input type="number" class="form-control" id="pay_to" name="pay_to" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="form-label">Created At</label>
                        <input type="date" class="form-control" id="created_at" name="created_at" required>
                    </div>
                    <div class="mb-3">
                        <label for="updated_at" class="form-label">Updated At</label>
                        <input type="date" class="form-control" id="updated_at" name="updated_at" required>
                    </div>
                    <button type="submit" name="detail_id" id="detail_id" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button><a href="{{ url('paymaster/pay-slabs') }}">
                </form>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-detail-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const id = this.getAttribute('data-id');
            const payFrom = this.getAttribute('data-pay-from');
            const payTo = this.getAttribute('data-pay-to');
            const amount = this.getAttribute('data-amount');
            const createdAt = this.getAttribute('data-created-at');
            const updatedAt = this.getAttribute('data-updated-at');

            // Set the form action URL and input values
            const form = document.getElementById('editDetailForm');
            form.action = `/paymaster/pay-slabs/details/${id}`; // Adjust this URL as needed

            document.getElementById('pay_from').value = payFrom;
            document.getElementById('pay_to').value = payTo;
            document.getElementById('amount').value = amount;

            // Check if date fields are not null before setting them
            document.getElementById('created_at').value = createdAt || '';
            document.getElementById('updated_at').value = updatedAt || '';

            document.getElementById('detail_id').value = id;

            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('edit-detail-modal'));
            myModal.show();
        });
    });
});
</script>

@endpush
