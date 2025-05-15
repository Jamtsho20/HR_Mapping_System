@extends('layouts.app')
@section('page-title', 'SSS')
@section('content')
    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('salary-saving-schemes.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New </a>
        @endsection
    @endif

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <select name="employee" class="form-control select2">
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee', request()->get('employee')) == $employee->id ? 'selected' : '' }} >{{ $employee->emp_id_name }}</option>
                    @endforeach
                </select>
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
                                                            <th> # </th>
                                                            <th> Employee </th>
                                                            <th> Policy Number </th>
                                                            <th> Amount </th>
                                                            <th> Created At </th>
                                                            <th> Updated At </th>
                                                            <th> Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($salarySavings as $record)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $record->employee->emp_id_name }} </td>
                                                                <td>{{ $record->policy_number }} </td>
                                                                <td>{{ $record->amount }} </td>
                                                                <td>{{ $record->created_at ? $record->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                                                <td>{{ $record->updated_at ? $record->updated_at->format('Y-m-d H:i:s') : '-' }}
                                                                </td>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($privileges->edit)
                                                                        <a href="{{ route('salary-saving-schemes.edit', $record->id) }}"
                                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                                            <i class="fa fa-edit"></i>
                                                                            EDIT
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center text-danger">No Matching Records Found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    {{ $salarySavings->links() }}
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
    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
