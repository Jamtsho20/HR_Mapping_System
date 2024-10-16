@extends('layouts.app')
@section('page-title', 'Grade & Steps')
@section('content')
<form action="{{ url('system-setting/hierarchies') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header ">
            <h5 class="card-title">Create Hierarchy</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="hierarchy_name">Hierarchy Name *</label>
                        <input type="text" class="form-control" name="hierarchy_name" value="{{ old('hierarchy_name') }}" required>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table id="hierarchies" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Level *</th>
                                    <th>Approver</th>
                                    <th>Employee</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('hierarchies') == '')
                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="hierarchies[AAAAA][level]">
                                            <option value="" disabled selected hidden>Select Level</option>
                                            @foreach (config('global.level') as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm approving-authority-select resetKeyForNew" name="hierarchies[AAAAA][approving_authority]">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach ($approvingAuthorities as $authority)
                                                <option value="{{ $authority->id }}">{{ $authority->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm employee-select resetKeyForNew" name="hierarchies[AAAAA][employee]">
                                            <option value="" disabled selected hidden>Select Employee</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="hierarchies[AAAAA][start_date]" class="form-control form-control-sm resetKeyForNew">
                                    </td>
                                    <td>
                                        <input type="date" name="hierarchies[AAAAA][end_date]" class="form-control form-control-sm resetKeyForNew">
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="hierarchies[AAAAA][status]">
                                            <option value="" disabled selected hidden>Select Level</option>
                                            @foreach (config('global.status') as $key => $type)
                                                <option value="{{ $key}}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @else

                                @foreach (old('hierarchies') as $key => $value)
                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <select name="hierarchies[AAAAA{{ $key }}][level]" class="form-control form-control-sm resetKeyForNew">
                                            <option value="" disabled selected hidden>Select Level</option>
                                            @foreach (config('global.level') as $type)
                                                <option value="{{ $type }}" {{ old('hierarchies[AAAAA'.$key.'][level]', $value['level'] ?? '') == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>                                    
                                    <td>
                                        <select class="form-control form-control-sm approving-authority-select" name="hierarchies[AAAAA{{ $key }}][approving_authority]">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach ($approvingAuthorities as $authority)
                                                <option value="{{$authority->id}}" {{ old('approving_authority', $value['approving_authority'] ?? '') == $authority->id ? 'selected' : '' }}>{{ $authority->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm employee-select" name="hierarchies[AAAAA{{ $key }}][employee]">
                                            <option value="" disabled selected hidden>Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('hierarchies[AAAAA'.$key.'][employee]', $value['employee'] ?? '') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->emp_id_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>                                    
                                    <td>
                                        <input type="date" name="hierarchies[AAAAA{{ $key }}][start_date]" class="form-control form-control-sm resetKeyForNew">
                                    </td>
                                    <td>
                                        <input type="date" name="hierarchies[AAAAA{{ $key }}][end_date]" class="form-control form-control-sm resetKeyForNew">
                                    </td>
                                    <td>
                                        <select name="grade_steps[AAAAA{{ $key }}][status]" class="form-control form-control-sm resetKeyForNew">
                                            @foreach (config('global.status') as $key => $label)
                                                <option value="{{ $key}}" {{ old('status', $value['status'] ?? '') == $key ? 'selected' : $key }}>{{ $label }}</option>
                                            @endforeach

                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                <tr class="notremovefornew">
                                    <td colspan="6"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body font-size-sm" style="text-align: right;">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> SAVE</button>
            <a href="{{ url('system-setting/hierarchies') }}" class="btn btn-danger btn-sm"> CANCEL</a>
        </div>
    </div>
</form>
@endsection