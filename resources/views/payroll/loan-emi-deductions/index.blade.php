@extends('layouts.app')
@section('page-title', 'Loan / Device Emis')
@section('content')
    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('loan-emi-deductions.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Loan
                / Device EMI</a>
        @endsection
    @endif

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-8 form-group">
                <input type="text" name="for_month" class="form-control" value="{{ request()->get('for_month') }}"
                    placeholder="Search">
            </div>
        @endcomponent

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
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
                                                            <th> Pay Head </th>
                                                            <th> Employee </th>
                                                            <th> Amount </th>
                                                            <th> Start Date </th>
                                                            <th>
                                                                End Date
                                                            </th>
                                                            <th>
                                                                Recurring
                                                            </th>
                                                            <th>
                                                                Recurring Months
                                                            </th>
                                                            <th>
                                                                Paid off early
                                                            </th>
                                                            <th>
                                                                Remarks
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
                                                        @forelse($loanEMIDeductions as $record)
                                                            <tr>
                                                                <td>{{ $record->payHead->name }}</td>
                                                                <td>{{ $record->employee->name }} </td>
                                                                <td>{{ $record->amount }} </td>
                                                                <td> {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('M d, Y') : '-' }}
                                                                </td>
                                                                <td> {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('M d, Y') : '-' }}
                                                                </td>
                                                                <td>
                                                                    @if ($record->recurring)
                                                                        <i class="fa fa-check-circle-o text-success"></i>
                                                                    @else
                                                                        <i class="fa fa-times-circle text-danger"></i>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $record->recurring_months }} </td>
                                                                <td>
                                                                    @if ($record->is_paid_off)
                                                                        <i class="fa fa-check-circle-o text-success"></i>
                                                                    @else
                                                                        <i class="fa fa-times-circle text-danger"></i>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $record->remarks }} </td>
                                                                <td>{{ $record->created_at ? $record->created_at->format('Y-m-d H:i:s') : '-' }}
                                                                </td>
                                                                <td>{{ $record->updated_at ? $record->updated_at->format('Y-m-d H:i:s') : '-' }}
                                                                </td>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($privileges->edit)
                                                                        <a href="{{ route('loan-emi-deductions.edit', $record->id) }}"
                                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                                            <i class="fa fa-edit"></i>
                                                                            EDIT
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="13" class="text-center text-danger">No
                                                                    Loan / Device EMIs found</td>
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
@endpush
