@extends('layouts.app')
@section('page-title', 'Pay slips')
@section('content')
    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('pay-slips.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Pay slip</a>
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
                                                            @php
                                                                $latestRecordId = \App\Models\PaySlip::latest()->value('id');
                                                            @endphp
                                                            @forelse($paySlips as $record)
                                                                <tr>
                                                                    <td>{{ \Carbon\Carbon::parse($record->for_month)->format('M, Y') }}</td>
                                                                    @php
                                                                        $statusKey = $record->status['key'];
                                                                        $statusLabel = $record->status['label'];

                                                                        $badgeClass = match($statusKey) {
                                                                            0 => 'bg-danger',    // Cancelled
                                                                            1 => 'bg-warning',   // New
                                                                            2 => 'bg-info',      // Verified
                                                                            3 => 'bg-primary',   // Approved
                                                                            4 => 'bg-success',   // Mailed or Custom
                                                                            default => 'bg-secondary', // Fallback
                                                                        };
                                                                    @endphp

                                                                    <td>
                                                                        <span class="badge rounded-pill {{ $badgeClass }}">{{ $statusLabel }}</span>
                                                                    </td>
                                                                    <td>{{ $record->created_at ? $record->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                                                    <td>{{ $record->updated_at ? $record->updated_at->format('Y-m-d H:i:s') : '-' }}</td>
                                                                    <td>
                                                                        @if ($privileges->edit)
                                                                            @if ($record->id == $latestRecordId)
                                                                                <a href="{{ route('pay-slips.show', $record->id) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-info">
                                                                                    <i class="fa fa-list"></i>
                                                                                    DETAILS
                                                                                </a>
                                                                            @endif
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

                                                                            @php
                                                                                $forMonth = \Carbon\Carbon::parse($record->for_month);
                                                                                $isThisOrNextMonth = $forMonth->isSameMonth(now()) || $forMonth->isSameMonth(now()->copy()->addMonth());
                                                                            @endphp

                                                                            @if ($record->status['key'] == 4 && $isCurrentMonth)
                                                                                <a href="{{ route('pay-slips.mail', $record->id) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-secondary"
                                                                                    id="email-payslip-btn">
                                                                                    <i class="fa fa-envelope"></i> MAIL PAYSLIPS
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
                                                                        Pay slips found</td>
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

                var url = $(this).attr('href');

                window.location.href = url;

                // $.confirm({
                //     title: 'Process Pay slip',
                //     content: 'Please ensure all allowances, deductions, changes in salary are done prior to processing Pay Slip.',
                //     buttons: {
                //         confirm: {
                //             text: 'Yes, Proceed',
                //             btnClass: 'btn-primary',
                //             action: function() {
                //                 window.location.href = prepareUrl;
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
                    title: 'Verify Pay slip',
                    content: 'Are you sure the pay slip for the month is verified?',
                    type: 'alert-primary',
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
                    title: 'Approve & Post Pay slip',
                    content: 'Are you sure you want to approve and post the Pay slip for the month?',
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
                    title: 'Mail Pay slips',
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
