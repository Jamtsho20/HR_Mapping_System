@extends('layouts.app')
@section('page-title', 'Edit User')
@section('content')
<form action="{{ url('system-setting/users/' . $user->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">General Information</h3>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label for="name">Name *</label>
					<input type="text" name="name" class="form-control required" value="{{ old('name', $user->name) }}" required>
				</div>
				<div class="form-group">
					<label for="username">Username *</label>
					<input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
				</div>
				<div class="form-group">
					<label for="username">Email *</label>
					<input type="text" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
				</div>
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="text" name="password" class="form-control" required>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Assign Roles *</h3>
			</div>
			<div class="card-body p-0">
				<table id="user-roles" class="table table-bordered table-striped table-condensed table-sm">
					<thead class="thead-light">
						<tr>
							<th>Role *</th>
						</tr>
					</thead>
					<tbody>
						@foreach($roles as $role)
							<tr>
								<td>
									<label class="css-control css-checkbox">
										<input type="checkbox" class="css-control-input" value="{{ $role->id }}" name="roles[]" {{ in_array($role->id, $rolesAssigned) ? 'checked' : ''}}>
										<span class="css-control-indicator"></span> {{ $role->name }}
									</label>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="card-footer">
				<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE USER</button>
				<a href="{{ url('system-setting/users') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
			</div>
		</div>
	</div>
</div>
</form>
@endsection