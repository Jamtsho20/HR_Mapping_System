@extends('layouts.app')
@section('page-title', 'Grade & Steps')
@section('content')
    <form action="{{ url('master/grade-steps') }}" method="POST">
        @csrf
        <div class="block">
            <div class="block-header block-header-default">
                <h5 class="block-title">Create Grade & Steps</h5>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grade_name">Grade Name *</label>
                            <input type="text" class="form-control" name="grade_name" value="{{ old('grade_name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table id="grade-steps" class="table table-condensed table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th width="3%" class="text-center">#</th>
                                        <th>Step Name *</th>
                                        <th>Starting Salary</th>
                                        <th>Increment</th>
                                        <th>Ending Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (old('grade_steps') == '')
                                        <tr>
                                            <td class="text-center">
                                                <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                            </td>
                                            <td>
                                                <input type="text" name="grade_steps[AAAAA][step_name]" class="form-control form-control-sm resetKeyForNew" required>
                                            </td>
                                            <td>
                                                <input type="number" name="grade_steps[AAAAA][starting_salary]" class="form-control form-control-sm resetKeyForNew">
                                            </td>
                                            <td>
                                                <input type="number" name="grade_steps[AAAAA][increment]" class="form-control form-control-sm resetKeyForNew">
                                            </td>
                                            <td>
                                                <input type="number" name="grade_steps[AAAAA][ending_salary]" class="form-control form-control-sm resetKeyForNew">
                                            </td>
                                        </tr>
                                    @else
                                        @foreach (old('grade_steps') as $key => $value)
                                            <tr>
                                                <td class="text-center">
                                                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                                </td>
                                                <td>
                                                    <input type="text" name="grade_steps[AAAAA{{$key}}][step_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('step_name', $value['step_name']) }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="grade_steps[AAAAA{{ $key }}][starting_salary]" class="form-control form-control-sm resetKeyForNew" value="{{ old('starting_salary', $value['starting_salary']) }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="grade_steps[AAAAA{{ $key }}][increment]" class="form-control form-control-sm resetKeyForNew" value="{{ old('increment', $value['increment']) }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="grade_steps[AAAAA{{ $key }}][ending_salary]" class="form-control form-control-sm resetKeyForNew" value="{{ old('ending_salary', $value['ending_salary']) }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr class="notremovefornew">
                                        <td colspan="4"></td>
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
            <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm" style="text-align: right;">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> SAVE</button>
                <a href="{{ url('master/grade-steps') }}" class="btn btn-danger btn-sm"> CANCEL</a>
            </div>
        </div>
    </form>
@endsection