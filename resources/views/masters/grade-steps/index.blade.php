@extends('layouts.app')
@section('page-title', 'Grade & Steps')
@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="grade_name" class="form-control" value="{{ request()->get('grade_name') }}" placeholder="Grade Name">
                </div>
            @endcomponent
            <div class="block-options">
                <div class="block-options-item">
                    @if ($privileges->create)
                        <a href="{{ url('master/grade-steps/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Grade & Steps</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-vcenter">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Grade</th>
                            <th>Steps & Pay Scale</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                        <tr>
                            <td>{{ $grades->firstItem() + ($loop->iteration - 1)}}</td>
                            <td>{{ $grade->name }}</td>
                            <td>
                                <table class="table table-sm table-bordered table-condensed f-s-12">
                                    <tbody>
                                        <tr>
                                            <th>Step Name</th>
                                            <th>Starting Salary</th>
                                            <th>Increment</th>
                                            <th>Ending Salary</th>
                                        </tr>
                                        @foreach ($grade->gradeSteps as $step)
                                            <tr>
                                                <td>{{ $step->name }}</td>
                                                <td class="text-right">{{ number_format($step->starting_salary) }}</td>
                                                <td class="text-right">{{ number_format($step->increment) }}</td>
                                                <td class="text-right">{{ number_format($step->ending_salary) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <td class="text-center">
                                @if ($privileges->edit)
                                    <a href="{{ url('master/grade-steps/'. $grade->id . '/edit') }}"
                                        class="btn btn-sm btn-rounded btn-outline-success f-s-10">
                                        <i class="fa fa-edit"></i> EDIT
                                    </a>
                                @endif
                                @if ($privileges->delete)
                                    <a href="#"
                                        class="delete-btn btn btn-sm btn-rounded btn-outline-danger f-s-10"
                                        data-url="{{ url('master/grade-steps/'. $grade->id) }}">
                                        <i class="fa fa-trash"></i> DELETE
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-danger">No grade & steps found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($grades->hasPages())
            <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                {{ $grades->links() }}
            </div>
        @endif
    </div>
@include('layouts.includes.delete-modal')
@endsection