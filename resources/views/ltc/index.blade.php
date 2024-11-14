@extends('layouts.app')
@section('page-title', 'Leave Travel Concession')
@section('content')
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
                        <h3 class="card-title">Leave Travel Concession</h3>
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
                                                            @forelse($ltcs as $record)
                                                                <tr>
                                                                    <td>{{ \Carbon\Carbon::parse($record->for_month)->format('M, Y') }}
                                                                    </td>
                                                                    <td>{{ $record->status['label'] }} </td>
                                                                    <td>{{ $record->created_at ? $record->created_at->format('Y-m-d H:i:s') : '-' }}
                                                                    </td>
                                                                    <td>{{ $record->updated_at ? $record->updated_at->format('Y-m-d H:i:s') : '-' }}
                                                                    </td>
                                                                    </td>
                                                                    <td>
                                                                        @if ($privileges->edit)
                                                                            @if ($record->status['key'] == 0)
                                                                                <a href="{{ route('ltc.show', $record->id) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-success">
                                                                                    <i class="fa fa-spinner"></i>
                                                                                    PROCESS
                                                                                </a>

                                                                                <a href="{{ route('ltc.finalize', $record->id) }}"
                                                                                    class="btn btn-sm btn-rounded btn-outline-primary"
                                                                                    id="finalize-btn">
                                                                                    <i class="fa fa-check"></i> FINALIZE
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
            // FINALIZE PAYSLIP
            $('#finalize-btn').on('click', function() {
                event.preventDefault();

                var finalizeUrl = $(this).attr('href');

                $.confirm({
                    title: 'Approve LTC',
                    content: "Are you sure to approve the LTC for this month?",
                    buttons: {
                        confirm: {
                            text: 'Yes, I confirm',
                            btnClass: 'btn-primary',
                            action: function() {
                                window.location.href = finalizeUrl;
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
