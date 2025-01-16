<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Pay Slab Details</h3>
                <form action="{{ route('pay-slab-details.create') }}" method="GET">
                    <input type="hidden" value="{{ $paySlab->id }}" name="payslabId">
                    <button type="button" class="add-btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add-modal">
                        <i class="fa fa-plus"></i> Add New Pay Slab Detail
                    </button>
                </form>
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
                                                    <tr role="row" class="thead-light">
                                                        <th>Pay From</th>
                                                        <th>Pay To</th>
                                                        <th>Amount</th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($paySlabDetails as $detail)
                                                    <tr>
                                                        <td>{{ $detail->pay_from }}</td>
                                                        <td>{{ $detail->pay_to }}</td>
                                                        <td>{{ $detail->amount }}</td>
                                                        <td>{{ $detail->created_at ? $detail->created_at->format('Y-m-d') : '' }}</td>
                                                        <td>{{ $detail->updated_at ? $detail->updated_at->format('Y-m-d') : '' }}</td>
                                                        <td class="text-center">
                                                            <a href="#" class="edit-btn btn btn-sm btn-rounded btn-outline-success"
                                                                data-url="{{ url('getpayslabdetail/' . $detail->id) }}"
                                                                data-update-url="{{ url('paymaster/pay-slab-details/' . $detail->id) }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>
                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('paymaster/pay-slab-details/' . $detail->id) }}">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div>{{ $paySlabDetails->links() }}</div>
                                        </div>

                                        <!-- Pagination Links -->
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
<!-- Add Modal-->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa;">
            <div class="modal-header">
                <h5 class="modal-title" id="addDetailLabel">Add New Pay Slab Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('pay-slab-details.store') }}" method="POST" id="add-modal-form">
                    @csrf
                    <input type="hidden" name="mas_pay_slab_id" value="{{ $paySlab->id }}">
                    <div class="mb-3">
                        <label for="pay_from" class="form-label">Pay From <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="pay_from" name="pay_from" value="{{ old('pay_from') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="pay_to" class="form-label">Pay To <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="pay_to" name="pay_to" value="{{ old('pay_to') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Add Detail</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')