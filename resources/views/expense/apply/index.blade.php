@extends('layouts.app')
@section('page-title', 'Expense Apply')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('apply-expense.create') }}" class="btn btn-sm btn-primary" id="applyexpense" data-item-type=""><i
        class="fa fa-plus"></i> Apply
    Expense</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="accountheads" class="form-control" value="{{ request()->get('accountheads') }}"
            placeholder="Search">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        @foreach ($headers as $header)
                        @php
                        $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($header->name));
                        @endphp
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                id="tab-{{ $sanitizedName }}" data-bs-toggle="pill"
                                data-bs-target="#content-{{ $sanitizedName }}" type="button" role="tab"
                                aria-controls="content-{{ $sanitizedName }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $header->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    @foreach ($headers as $header)
                    @php
                    $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($header->name));
                    $id = $header->id;
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                        id="content-{{ $sanitizedName }}" role="tabpanel"
                        aria-labelledby="tab-{{ $sanitizedName }}" data-item-type="{{ $id }}">
                        @if ($id == 2)
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatable_wrapper"
                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
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
                                                                    <th>
                                                                        #
                                                                    </th>
                                                                    <th>
                                                                        EMPLOYEE
                                                                    </th>
                                                                    <th>
                                                                        DATE
                                                                    </th>
                                                                    <th>
                                                                        EXPENSE TYPE
                                                                    </th>
                                                                    <th>
                                                                        EXPENSE AMOUNT
                                                                    </th>
                                                                    <th>
                                                                        DESCRIPTION
                                                                    </th>
                                                                    <th>
                                                                        STATUS
                                                                    </th>
                                                                    <th>
                                                                        Action
                                                                    </th>
                                                                </tr>
                                                            <tbody>
                                                                @forelse ($expenseApplications as $expense)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $expense->employee->employee_id }} ({{ $expense->employee->title . ' ' . $expense->employee->name }})
                                                                    </td>
                                                                    <td>{{ $expense->date }}</td>
                                                                    <td>{{ $expense->expenseType->name }}
                                                                    </td>
                                                                    <td>{{ $expense->expense_amount }}</td>
                                                                    <td>{{ $expense->description }}</td>
                                                                    <td class="text-center">
                                                                        @php
                                                                        $statusClasses = [
                                                                        -1 => 'badge bg-danger',
                                                                        0 => 'badge bg-warning',
                                                                        1 => 'badge bg-primary',
                                                                        2 => 'badge bg-success',
                                                                        3 => 'badge bg-info',
                                                                        ];
                                                                        $statusText = config("global.application_status.{$expense->status}", 'Unknown Status');
                                                                        $statusClass = $statusClasses[$expense->status] ?? 'badge bg-secondary';
                                                                        @endphp

                                                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($privileges->view)
                                                                        <a href="{{ url('expense/apply-expense/' . $expense->id) }}"
                                                                            class="btn btn-sm btn-outline-secondary"><i
                                                                                class="fa fa-list"></i>
                                                                            Detail</a>
                                                                        @endif
                                                                        @if ($privileges->edit)
                                                                        <a href="{{ url('expense/apply-expense/' . $expense->id . '/edit') }}"
                                                                            class=" btn btn-sm btn-rounded btn-outline-success"><i
                                                                                class="fa fa-edit"></i>
                                                                            EDIT</a>
                                                                        @endif
                                                                        @if ($privileges->delete)
                                                                        <a href="#"
                                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                            data-url="{{ url('expense/apply-expense/' . $expense->id) }}"><i
                                                                                class="fa fa-trash"></i>
                                                                            DELETE</a>
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="8"
                                                                        class="text-center text-danger">No
                                                                        records found</td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif ($id == 3)
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatable_wrapper"
                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
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
                                                                    <th>
                                                                        #
                                                                    </th>
                                                                    <th>
                                                                        EMPLOYEE
                                                                    </th>
                                                                    <th>
                                                                        DATE
                                                                    </th>
                                                                    <th>
                                                                        TOTAL PAYABLE AMOUNT
                                                                    </th>
                                                                    <th>
                                                                        ADV. BALANCE AMOUNT
                                                                    </th>
                                                                    <th>
                                                                        TOTAL AMOUNT
                                                                    </th>
                                                                    <th>
                                                                        STATUS
                                                                    </th>
                                                                    <th>
                                                                        ACTION
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($dsaClaimApplications as $dsaClaim)
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="8"
                                                                        class="text-center text-danger">No
                                                                        records found</td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif ($id == 4)
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatable_wrapper"
                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
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
                                                                    <th>
                                                                        #
                                                                    </th>
                                                                    <th>
                                                                        EMPLOYEE
                                                                    </th>
                                                                    <th>
                                                                        CLAIM DATE
                                                                    </th>
                                                                    <th>
                                                                        CLAIM TYPE
                                                                    </th>
                                                                    <th>
                                                                        CLAIM AMOUNT
                                                                    </th>
                                                                    <th>
                                                                        CURRENT LOCATION
                                                                    </th>
                                                                    <th>
                                                                        NEW LOCATION
                                                                    </th>
                                                                    <th>
                                                                        STATUS
                                                                    </th>
                                                                    <th>
                                                                        Action
                                                                    </th>
                                                                </tr>
                                                            <tbody>
                                                                @forelse ($transferClaims as $transfer)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $transfer->employee->employee_id }} ({{ $transfer->employee->title . ' ' . $transfer->employee->name }})
                                                                    <td>{{ $transfer->created_at->format('d-m-Y') }}
                                                                    </td>
                                                                    <td>{{ $transfer->type->name }}
                                                                    </td>
                                                                    <td>{{ $transfer->amount_claimed }}
                                                                    </td>
                                                                    <td>{{ $transfer->current_location }}
                                                                    </td>
                                                                    <td>{{ $transfer->new_location }}</td>
                                                                    <td>
                                                                        @if ($transfer->status == 1)
                                                                        <span
                                                                            class="badge bg-primary">Applied</span>
                                                                        @elseif($transfer->status == 2)
                                                                        <span
                                                                            class="badge bg-info">Approved</span>
                                                                        @elseif($transfer->status == 0)
                                                                        <span
                                                                            class="badge bg-warning">Cancelled</span>
                                                                        @elseif($transfer->status == -1)
                                                                        <span
                                                                            class="badge bg-danger">Rejected</span>
                                                                        @else
                                                                        <span
                                                                            class="badge bg-secondary">Unknown
                                                                            Status</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($privileges->view)
                                                                        <a href="{{ url('expense/transfer-claim/' . $transfer->id) }}"
                                                                            class="btn btn-sm btn-outline-secondary"><i
                                                                                class="fa fa-list"></i>
                                                                            Detail</a>
                                                                        @endif
                                                                        @if ($privileges->edit)
                                                                        <a href="{{ url('expense/transfer-claim/' . $transfer->id . '/edit') }}"
                                                                            class=" btn btn-sm btn-rounded btn-outline-success"><i
                                                                                class="fa fa-edit"></i>
                                                                            EDIT</a>
                                                                        @endif
                                                                        @if ($privileges->delete)
                                                                        <a href="#"
                                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                            data-url="{{ url('expense/transfer-claim/' . $transfer->id) }}"><i
                                                                                class="fa fa-trash"></i>
                                                                            DELETE</a>
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="8"
                                                                        class="text-center text-danger">No
                                                                        records found</td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>



@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    $(document).ready(function() {
        const activeTabContent = $('.tab-pane.active');
        if (activeTabContent.length) {
            const activeType = activeTabContent.data('item-type');
            $('#applyexpense').attr('data-item-type', activeType);
        }

        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
            const targetContentId = $(e.target).data('bs-target').replace('#content-', '');
            const targetContent = $(`#content-${targetContentId}`);
            const itemType = targetContent.data('item-type');

            $('#applyexpense').attr('data-item-type', itemType);
        });

        $('#applyexpense').on('click', function(event) {
            event.preventDefault();
            const itemType = $(this).data('item-type');
            const baseUrl = $(this).attr('href');
            const url = `${baseUrl}?item_type=${itemType}`;
            window.location.href = url;
        });
    })
</script>
@endpush