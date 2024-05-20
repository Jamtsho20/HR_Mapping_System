@extends('layouts.app')
@section('page-title', 'Create Role and Permissions')
@section('buttons')
    <a href="{{ url('system-setting/roles')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')
<form action="{{ url('system-setting/roles') }}" method="POST">
@csrf
<div class="row">
	<div class="col-md-3">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">Add Module</h3>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label for="name">Role Name *</label>
					<input type="text" name="role_name" class="form-control form-control-sm" value="{{ old('role_name') }}" required>
				</div>
				<div class="form-group">
					<label for="name">Role Description</label>
					<textarea name="role_description" class="form-control form-control-sm" rows="6">{{ old('role_description') }}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Assign Permission</h3>
			</div>
			<div class="card-body p-0">
				<table id="set-access" class="table table-condensed table-sm table-bordered table-hover">
					<thead class="thead-light">
						<th>Main Module</th>
						<th>Sub Module</th>
						<th class="text-center">All</th>
						<th class="text-center">View</th>
						<th class="text-center">Create</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Delete</th>
					</thead>
					<tbody>
						@php $currentTopMenu = ''; @endphp
						@foreach($modules as $module)
                            @if($module->name != $currentTopMenu && $currentTopMenu != '')
								<thead class="thead-light">
									<tr>
										<th>Main Module</th>
										<th>Sub Module</th>
										<th class="text-center"><strong>All</strong></th>
										<th class="text-center"><strong>View</strong></th>
										<th class="text-center"><strong>Create</strong></th>
										<th class="text-center"><strong>Edit</strong></th>
										<th class="text-center"><strong>Delete</strong></th>
									</tr>
								</thead>
							@endif
							@foreach($module->systemSubMenus as $subMenu)
								<tr>
									<td>{{ $module->name != $currentTopMenu ? $module->name : '' }}</td>
									<td>
										<input type="hidden" name="permission_role[menu-{{$subMenu->id}}][sub_menu_id]" value="{{ $subMenu->id }}" disabled="disabled" class="module-id resetKeyForNew">
										{{ $subMenu->name }}
									</td>
									<td class="text-center">
										<label class="css-control css-checkbox">
											<input type="checkbox" name="all" class="all-privileges css-control-input" value="1">
											<span class="lbl css-control-indicator"></span>
										</label>
									</td>
									<td class="text-center">
										<label class="css-control css-checkbox">
											<input type="checkbox" name="permission_role[menu-{{$subMenu->id}}][view]" class="check-perm resetKeyForNew css-control-input" value="1">
											<span class="lbl css-control-indicator"></span>
										</label>
									</td>
									<td class="text-center">
										<label class="css-control css-checkbox">
											<input type="checkbox" name="permission_role[menu-{{$subMenu->id}}][create]" class="check-perm resetKeyForNew css-control-input" value="1">
											<span class="lbl css-control-indicator"></span>
										</label>
									</td>
									<td class="text-center">
										<label class="css-control css-checkbox">
											<input type="checkbox" name="permission_role[menu-{{$subMenu->id}}][edit]" class="check-perm resetKeyForNew css-control-input" value="1">
											<span class="lbl css-control-indicator"></span>
										</label>
									</td>
									<td class="text-center">
										<label class="css-control css-checkbox">
											<input type="checkbox" name="permission_role[menu-{{$subMenu->id}}][delete]" class="check-perm resetKeyForNew css-control-input" value="1">
											<span class="lbl css-control-indicator"></span>
										</label>
									</td>
								</tr>
								@php $currentTopMenu = $module->name; @endphp
							@endforeach
						@endforeach
					</tbody>
				</table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> CREATE ROLE</button>
                <a href="{{ url('system-setting/roles') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
		</div>
	</div>
</div>
</form>
@endsection
@push('page_scripts')
	<script>
		$(document).ready(function(){
			$('.all-privileges').on('change', function(){
				if ( $(this).is(':checked') ) {
					$(this).closest('tr').find('input[type="hidden"].module-id').prop('disabled', false);
					$(this).closest('tr').find('input[type="checkbox"].check-perm').prop('checked', true);
				} else {
					$(this).closest('tr').find('input[type="checkbox"].check-perm').prop('checked', false);
					$(this).closest('tr').find('input[type="hidden"].module-id').prop('disabled', true);
				}
			});

			$('.check-perm').on('change', function() {
				var curRow = $(this).closest('tr');
				var element = curRow.find('input[type="hidden"].module-id');
				var selectAll = curRow.find('input[name="all"].all-privileges');

				if ($(this).is(':checked')) {
					if (element.is(':disabled')) {
						element.removeAttr('disabled');
					}
				}

				//count how many checkboxes are checked for permission (total there should be 4)
				var permissionCount = curRow.find('input[type=checkbox].check-perm:checked')

				//if count is 0 then disable the menu
				if (permissionCount.length == 0) {
					element.attr('disabled', true);
					selectAll.prop('checked', false);
				} else if (permissionCount.length < 4) { //if out of 4 one is unchecked then uncheck the all check box
					selectAll.prop('checked', false);
				} else if (permissionCount.length == 4) { // if all 4 are checked then check the all checkbox as well
					selectAll.prop('checked', true);
				}
			});
		});
	</script>
@endpush