@extends('layouts.app')
@section('page-title', 'Hierarchy')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="hierarchy_name" class="form-control" value="{{ request()->get('hierarchy_name') }}" placeholder="Hierarchy Name">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="{{route('hierarchies.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Hierarchy</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th class="text-center">Hierarchy Name</th>
                    <th class="text-center">Level</th>
                    <th class="text-center">Designation</th>
                    <th class="text-center">Start Date</th>
                    <th class="text-center">End Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>


                @forelse ($hierarchies as $hierarchy)
                <tr>
                    <td class="text-center">{{ $hierarchies->firstItem() + ($loop->iteration - 1) }}</td>
                    <td class="text-center">{{$hierarchy->hierarchy_name}}</td>
                    @if( $hierarchy -> level == 1)
                    <td class="text-center">Level 1</td>
                    @elseif( $hierarchy -> level == 2)
                    <td class="text-center">Level 2</td>
                    @else
                    <td class="text-center">Level 3</td>
                    @endif

                    @if( $hierarchy -> value == 1)
                    <td class="text-center">Immediate Supervisor</td>
                    @elseif( $hierarchy -> value == 2)
                    <td class="text-center">Section Head</td>
                    @elseif( $hierarchy -> value == 3)
                    <td class="text-center">Department Head</td>
                    @elseif( $hierarchy -> value == 4)
                    <td class="text-center">Management Head</td>
                    @elseif( $hierarchy -> value == 5)
                    <td class="text-center">Human Resource</td>
                    @else
                    <td class="text-center">Finance Head</td>
                    @endif
                    <td class="text-center">{{$hierarchy->start_date}}</td>
                    <td class="text-center">{{$hierarchy->end_date}}</td>
                    <td class="text-center">{{$hierarchy->status}}</td>
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{url('system-setting/hierarchies/' .$hierarchy->id .'/edit')}}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                            EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('system-setting/hierarchies/'.$hierarchy->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">No Hierarchy found</td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="card-footer">

    </div>

</div>

<div class="modal fade" id="edit-modal" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Edit Hierarchy</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="hiererchy">Hierarchy Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="hierarchy_name" value="{{ old('hierarchy_name') }}" required="required">
                            </div>
                            <div class="form-group col-4">
                                <label for="">Level <span class="text-danger">*</span></label>
                                <select class="form-control" name="level">
                                    <option value="" disabled selected hidden>Select Level</option>
                                    <option value="">Level 1</option>
                                    <option value="">Level 2</option>
                                    <option value="">Level 2</option>
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="">Value <span class="text-danger">*</span></label>
                                <select class="form-control" name="level">
                                    <option value="" disabled selected hidden>Select Level</option>
                                    <option value="">Immediate Supervisor</option>
                                    <option value="">Section Head</option>
                                    <option value="">Department Head</option>
                                    <option value="">Management</option>
                                    <option value="">Human Resource</option>
                                    <option value="">Finance Head</option>
                                </select>
                            </div>

                            <div class="form-group col-4">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="text" class="js-datepicker form-control js-datepicker" id="example-datepicker1" name="example-datepicker1" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                            </div>

                            <div class="form-group col-4">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="text" class="js-datepicker form-control js-datepicker" id="example-datepicker1" name="example-datepicker1" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                            </div>

                            <div class="form-group col-4">
                                <label for="">Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="level">
                                    <option value="" disabled selected hidden>Select Level</option>
                                    <option value="">Active</option>
                                    <option value="">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check"></i> Update
                    </button>
                    <button type="button" class="btn btn-alt-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush