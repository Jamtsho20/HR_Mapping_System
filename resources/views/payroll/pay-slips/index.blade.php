@extends('layouts.app')
@section('page-title', 'Payslips')
@section('content')
    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('pay-slips.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Payslip</a>
        @endsection
    @endif

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-8 form-group">
                <input type="text" name="for_month" class="form-control"
                    value="{{ request()->get('for_month') }}"placeholder="Search">
            </div>
        @endcomponent

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pay Slips</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="dataTables_length" id="responsive-datatable_length"
                                            data-select2-id="responsive-datatable_length">
                                            <label data-select2-id="26">
                                                Show
                                                <select class="select2">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                                entries
                                            </label>
                                        </div>
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
                                                                    For Month
                                                                </th>
                                                                <th>
                                                                    Status
                                                                </th>
                                                                <th>
                                                                    Created At
                                                                </th>
                                                                <th>
                                                                    Updated At
                                                                </th>
                                                                <th>
                                                                    Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($paySlips as $record)
                                                                <tr>
                                                                    <td>{{ \Carbon\Carbon::parse($record->for_month)->format('M, Y') }}
                                                                    </td>
                                                                    <td>{{ $record->status['label'] }}</td>
                                                                    <td>{{ $record->created_at ? $record->created_at->format('Y-m-d H:i:s') : '-' }}
                                                                    </td>
                                                                    <td>{{ $record->updated_at ? $record->updated_at->format('Y-m-d H:i:s') : '-' }}
                                                                    </td>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($privileges->edit)
                                                                            <a href="{{ route('pay-slips.show', $record->id) }}"
                                                                                class="btn btn-sm btn-rounded btn-outline-info">
                                                                                <i class="fa fa-list"></i>
                                                                                DETAILS
                                                                            </a>
                                                                            @if ($record->status['key'] == 1)
                                                                                <a href="{{ route('pay-slips.process', ['id' => $record->id, 'status' => 2]) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-success"
                                                                                    id="prepare-btn">
                                                                                    <i class="fa fa-spinner"></i>
                                                                                    PREPARE
                                                                                </a>
                                                                            @endif
                                                                            @if ($record->status['key'] == 2)
                                                                                <a href="{{ route('pay-slips.verify', ['id' => $record->id, 'status' => 3]) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-primary"
                                                                                    id="verify-btn">
                                                                                    <i class="fa fa-check"></i> VERIFY
                                                                                </a>
                                                                            @endif
                                                                            @if ($record->status['key'] == 3)
                                                                                <a href="{{ route('pay-slips.approve', ['id' => $record->id, 'status' => 4]) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-success"
                                                                                    id="approve-btn">
                                                                                    <i class="fa fa-check"></i> POST
                                                                                </a>
                                                                            @endif
                                                                            @if ($record->status['key'] == 4)
                                                                                <a href="{{ route('pay-slips.mail', $record->id) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-secondary"
                                                                                    id="email-payslip-btn">
                                                                                    <i class="fa fa-envelope"></i> MAIL
                                                                                    PAYSLIPS
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                        @if ($privileges->delete)
                                                                            @if ($record->status['key'] == 1)
                                                                                <a href="javascript:void(0);"
                                                                                    class="btn btn-sm btn-rounded btn-outline-danger delete-btn"
                                                                                    data-url="{{ route('pay-slips.destroy', $record->id) }}">
                                                                                    <i class="fa fa-list"></i>
                                                                                    DELETE
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-danger">No
                                                                        Payslips found</td>
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
                </div>
            </div>
        </div>
    </div>
    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            // PROCESS PAYSLIP
            $('#prepare-btn').on('click', function() {
                event.preventDefault();

                var processUrl = $(this).attr('href');

                window.location.href = processUrl;

                // $.confirm({
                //     title: 'Process Payslip',
                //     content: 'Please ensure all allowances, deductions, changes in salary are done prior to processing Pay Slip.',
                //     buttons: {
                //         confirm: {
                //             text: 'Yes, Proceed',
                //             btnClass: 'btn-primary',
                //             action: function() {
                //                 window.location.href = processUrl;
                //             }
                //         },
                //         cancel: {
                //             text: 'Cancel',
                //             btnClass: 'btn-danger',
                //             action: function() {
                //                 //
                //             }
                //         }
                //     }
                // });
            });

            // VERIFY PAYSLIP
            $('#verify-btn').on('click', function() {
                event.preventDefault();

                var verifyUrl = $(this).attr('href');

                $.confirm({
                    title: 'Verify Payslip',
                    content: 'Do you verify the Payslip for the month is correct?',
                    buttons: {
                        confirm: {
                            text: 'Yes, Proceed',
                            btnClass: 'btn-primary',
                            action: function() {
                                window.location.href = verifyUrl;
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger',
                            action: function() {
                                //
                            }
                        }
                    }
                });
            });

            // APPROVE PAYSLIP
            $('#approve-btn').on('click', function() {
                event.preventDefault();

                var approveUrl = $(this).attr('href');

                $.confirm({
                    title: 'Approve Payslip',
                    content: 'Do you approve the Payslip for the month?',
                    buttons: {
                        confirm: {
                            text: 'Yes, Proceed',
                            btnClass: 'btn-primary',
                            action: function() {
                                window.location.href = approveUrl;
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger',
                            action: function() {
                                //
                            }
                        }
                    }
                });
            });

            // MAIL PAYSLIP
            $('#email-payslip-btn').on('click', function() {
                event.preventDefault();

                var emailUrl = $(this).attr('href');

                $.confirm({
                    title: 'Mail Payslips',
                    content: 'Are you sure to mail employees their pay slips? This cannot be undone.',
                    buttons: {
                        confirm: {
                            text: 'Yes, Proceed',
                            btnClass: 'btn-primary',
                            action: function() {
                                window.location.href = emailUrl;
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger',
                            action: function() {
                                //
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
