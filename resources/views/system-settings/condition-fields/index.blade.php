@extends('layouts.app')
@section('page-title', 'Condition Fields')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/condition-fields/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Add
    new</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header">
        <div class="col-sm-4">
            <h5>Conditions Fields</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                    <thead>
                        <tr>
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
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush