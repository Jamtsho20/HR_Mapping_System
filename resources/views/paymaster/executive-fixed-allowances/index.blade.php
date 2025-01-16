@extends('layouts.app')
@section('page-title', 'Executive Fixed Allowances')
@section('content')
@if ($privileges->create)
    @section('buttons')
        <a href="{{ route('pay-heads.create')}}" data-bs-toggle="modal" data-bs-target="#add-exe-allowance-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New</a>
    @endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="Search">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Executive Fixed Allowances</h3>
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
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                Employee
                                                            </th>
                                                            <th>
                                                                Allowance
                                                            </th>
                                                            <th>
                                                                Amount
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($records as $allowance)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $allowance->employee?->emp_id_name }} </td>
                                                            <td>{{ $allowance->payHead->name }}</td>
                                                            <td>{{ $allowance->amount }}</td>
                                                            <td class="text-center">
                                                                <a href="javascript:void(0);"
                                                                        class="btn btn-sm btn-rounded btn-outline-success"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#edit-exe-allowance-modal"
                                                                        data-id="{{ $allowance->id ?? '' }}"
                                                                        data-employee-employee-id="{{ $allowance->employee->username ?? '' }}"
                                                                        data-employee-name="{{ $allowance->employee->name ?? '' }}"
                                                                        data-pay-head-name="{{ $allowance->payHead->name ?? '' }}"
                                                                        data-amount="{{ $allowance->amount ?? '' }}"
                                                                        data-update-url="{{ route('executive-fixed-allowances.update', [$allowance->id]) }}">
                                                                        <i class="fa fa-edit"></i> EDIT
                                                                    </a>
                                                                    <meta name="csrf-token" content="{{ csrf_token() }}">

                                                                    <a href="javascript:void(0);"
                                                                        id="delete-exe-allowance"
                                                                        class="btn btn-sm btn-rounded btn-outline-danger"
                                                                        data-id="{{ $allowance->id}}"
                                                                        data-delete-url="{{ route('executive-fixed-allowances.destroy', [$allowance->id]) }}">
                                                                        <i class="fa fa-bin"></i> DELETE
                                                                    </a>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No pay heads found</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div>{{ $records->links() }}</div>
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
    </div>
</div>

<!-- Add Exe Allowance Modal -->
<div class="modal fade" id="add-exe-allowance-modal" tabindex="-1" aria-labelledby="add-exe-allowance-modal"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('executive-fixed-allowances.store') }}" method="post" id="add-pay-slip-detail-form">
                    @csrf
                    @method('POST')

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="employee_id">Employee <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="employee_id" required>
                                <option value="">Select</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->emp_id_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="pay_head_id">Pay Head <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="pay_head_id" required>
                                <option value="">Select</option>
                                @foreach ($allowances as $id => $name)
                                    <option value="{{ $id }}">
                                        {{ $name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" value="" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Exe Allowance Modal -->
    <div class="modal fade" id="edit-exe-allowance-modal" tabindex="-1" aria-labelledby="edit-exe-allowance-modal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="edit-pay-slip-detail-form">
                        @csrf
                        @method('PUT')

                        <input type="hidden" class="form-control" name="id" value="{{ $allowance->id }}" required="required">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="employee_id" value="{{ $allowance->employee?->username }}" readonly disabled />
                            </div>

                            <div class="form-group col-md-6">
                                <label for="employee_name">Employee Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="employee_name" value="{{ $allowance->employee?->name }}" readonly disabled />
                            </div>

                            <div class="form-group col-md-6">
                                <label for="pay_head">Pay Head <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pay_head" value="{{ $allowance->payHead?->name }}" readonly disabled />
                            </div>

                            <div class="form-group col-md-6">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="amount" value="" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    $(document).ready(function() {
        $('#edit-exe-allowance-modal').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);

            var id = button.data('id');
            var amount = button.data('amount');
            var updateUrl = button.data('update-url');

            var modal = $(this);
            modal.find('input[name="id"]').val(id);
            modal.find('input[name="amount"]').val(amount);

            modal.find('form').attr('action', updateUrl);
        });

        $(document).on("click", "#delete-exe-allowance", function(e) {
            e.preventDefault();
            const deleteUrl = $(this).data('delete-url');
            const token = $('meta[name="csrf-token"]').attr('content');

            $.confirm({
                title: 'Delete!',
                content: 'Are you sure you want to delete the entry?',
                type: 'none',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-danger',
                        keys: ['enter'],
                        action: function() {
                            $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token, 
                            },
                            success: function(response) {
                                window.location.href = window.location.href;
                            },
                        });
                        }
                    },
                    cancel: function() {

                    }
                }
            });
        });
    });
</script>
@endpush