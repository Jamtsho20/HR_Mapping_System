@extends('layouts.app')
@section('page-title', 'Condition Fields')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/condition-fields/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Condition Fields</a>
@endsection
@endif
@section('content')
<div class="card">
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
                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                        <thead>
                                            <tr class="thead-light">
                                                <th>#</th>
                                                <th>Approval Head</th>
                                                <th>Field Name</th>
                                                <th>Field Label</th>
                                                <th>Has Employee Field</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @forelse($fields as $field)
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    {{$field->approval_head->name}}
                                                </td>
                                                <td>
                                                    {{$field->name}}
                                                </td>
                                                <td>
                                                    {{$field->label}}
                                                </td>
                                                <td>
                                                    {{$field->has_employee_field==1?'Yes':'No'}}
                                                </td>
                                                <td> @if ($privileges->edit)
                                                    <a href="{{ url('system-setting/condition-fields/' . $field->id . '/edit') }}"
                                                        class="btn btn-sm btn-rounded btn-outline-success f-s-10">
                                                        <i class="fa fa-edit"></i> EDIT
                                                    </a>
                                                    @endif
                                                    @if ($privileges->delete)
                                                    <a href="#"
                                                        class="delete-btn btn btn-sm btn-rounded btn-outline-danger f-s-10"
                                                        data-url="{{ url('system-setting/condition-fields/' . $field->id) }}">
                                                        <i class="fa fa-trash"></i> DELETE
                                                    </a>
                                                    @endif
                                                </td>

                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-danger">No data available</td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12" >{{ $fields->links() }}</div>
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