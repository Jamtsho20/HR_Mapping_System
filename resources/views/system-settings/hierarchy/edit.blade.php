@extends('layouts.app')
@section('page-title', 'Hierarchy')
@section('content')
<form action="{{ url('system-setting/hierarchies/' . $hierarchy->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Hierarchy</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="hierarchy_name">Hierarchy Name *</label>
                        <input type="text" class="form-control" name="hierarchy_name" value="{{ $hierarchy->hierarchy_name }}" readonly>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table id="hierarchies" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Level *</th>
                                    <th>Approving Authority *</th>
                                    <th>Employee</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($hierarchy->hierarchyLevels) == 0)
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
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
                                        <select class="form-control form-control-sm approving-authority-select form-control-sm approving-authority-select resetKeyForNew" name="hierarchies[AAAAA][approving_authority]">
                                            <option value="" disabled selected hidden>Select Approving Authority</option>
                                        </select>
                                    </td>
                                    <td> 
                                        <select class="form-control form-control-sm employee-select resetKeyForNew" name="hierarchies[AAAAA][employee]" required>
                                            <option disabled selected hidden>Select Employee</option>
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
                                            <option value="" disabled selected hidden>Select Status</option>
                                            @foreach (config('global.status') as $key => $type)
                                                <option value="{{ $key }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @else
                                @foreach ($hierarchy->hierarchyLevels as $key => $value)
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                        <input type="hidden" name="hierarchies[AAAAA{{ $key }}][level_id]" value="{{ old('level_id', $value['id']) }}">
                                    </td>
                                    <td>
                                        <select name="hierarchies[AAAAA{{ $key }}][level]" class="form-control form-control-sm resetKeyForNew">
                                            <option value="" disabled selected hidden>Select Level</option>
                                            @foreach (config('global.level') as $type)
                                                <option value="{{ $type }}" {{ old('level', $value['level']) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm approving-authority-select resetKeyForNew" name="hierarchies[AAAAA{{ $key }}][approving_authority]">
                                            <option value="" disabled selected hidden>Select Approving Authority</option>
                                            @foreach ($approvingAuthorities as $authority)
                                                <option value="{{ $authority->id }}" {{ old('approving_authority', $value['approving_authority_id']) == $authority->id ? 'selected' : '' }}>{{ $authority->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm employee-select resetKeyForNew" name="hierarchies[AAAAA{{ $key }}][employee]">
                                            <option disabled selected hidden>Select Employee</option>
                                            @if(isset($employeesByHierarchy[$value->id]))
                                                <option value="{{ $employeesByHierarchy[$value->id]->id }}" selected>{{ $employeesByHierarchy[$value->id]->emp_id_name }}</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="hierarchies[AAAAA{{ $key }}][start_date]" class="form-control form-control-sm resetKeyForNew" value="{{ old('start_date', $value['start_date']) }}">
                                    </td>
                                    <td>
                                        <input type="date" name="hierarchies[AAAAA{{ $key }}][end_date]" class="form-control form-control-sm resetKeyForNew" value="{{ old('end_date', $value['end_date']) }}">
                                    </td>
                                    <td>
                                        <select name="hierarchies[AAAAA{{ $key }}][status]" class="form-control form-control-sm resetKeyForNew">
                                            @foreach (config('global.status') as $statusKey => $type)
                                                <option value="{{ $statusKey }}" {{ old('status', $value['status']) == $statusKey ? 'selected' : '' }}>{{ $type }}</option>
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
        <div class="card-body font-size-sm text-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> UPDATE</button>
            <a href="{{ url('system-setting/hierarchies') }}" class="btn btn-danger btn-sm">CANCEL</a>
        </div>
    </div>
</form>
@endsection
