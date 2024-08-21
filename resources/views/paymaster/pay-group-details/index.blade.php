<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Pay Group Details<span class="text-danger">*</span></th></h3>
                <form action="{{ route('pay-group-details.create') }}" method="GET">
                    <input type="hidden" value="{{ $payGroup->id }}" name="payGroupId">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Pay Pay Group Details</button>
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
                                                id="basic-datatable table-responsive" >
                                                <thead>
                                                    <tr role="row">
                                                        <th>Employee Category <span class="text-danger">*</span></th>
                                                        <th>Grade <span class="text-danger">*</span></th></th>
                                                        <th>Calculation Method <span class="text-danger">*</span></th></th>
                                                        <th>Amount <span class="text-danger">*</span></th></th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($payGroup->payGroupDetails as $detail)

                                                    <tr>
                                                        <td>{{ $detail->employee_category }}</td>
                                                        <td>{{ $detail->grade ? $detail->grade->name : 'N/A' }}</td>
                                                        <td>
                                                            {{ \App\Models\MasPayGroupDetail::getCalculationMethods()[$detail->calculation_method] ?? 'Unknown Method' }}
                                                        </td>
                                                        <td>{{ $detail->amount }}</td>
                                                        <td>{{ $detail->created_at ? $detail->created_at->format('Y-m-d') : '' }}</td>
                                                        <td>{{ $detail->updated_at ? $detail->updated_at->format('Y-m-d') : '' }}</td>
                                                        <td class="text-center">

                                                            <a href="#" class="edit-btn btn btn-sm btn-rounded btn-outline-success"
                                                                data-url="{{ url('getpaygroupdetail/' . $detail->id) }}"
                                                                data-update-url="{{ url('paymaster/pay-group-details/' . $detail->id) }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>

                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('paymaster/pay-group-details/' . $detail->id) }}">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </a>

                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                        <!-- Pagination Links -->
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div>{{ $payGroupDetails->links() }}</div>
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