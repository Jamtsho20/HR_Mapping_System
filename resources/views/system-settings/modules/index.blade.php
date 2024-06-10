@extends('layouts.app')
@section('page-title', 'System Modules')
@if ($privileges->create)
    @section('buttons')
        <a href="{{ url('system-setting/modules/create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Module</a>
    @endsection
@endif
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">List of Modules</h3>
            </div>
			<div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                        <thead>
                            <tr class="thead-light">
                                <th class="text-center">#</th>
                                <th class="text-center">Icon</th>
                                <th>Modules</th>
                                <th class="text-center"><i class="fa fa-asterisk"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($modules as $module)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"><i class="fa {{ $module->icon }}"></i></td>
                                    <td>{{ $module->name }}</td>
                                    <td class="text-center">
                                        @if ($privileges->edit)
                                            <a href="{{ url('system-setting/modules/' . $module->id . '/edit') }}" class="btn btn-sm btn-outline-info">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-danger text-center">No modules to be displayed</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>
@include('layouts.includes.delete-modal')
@endsection