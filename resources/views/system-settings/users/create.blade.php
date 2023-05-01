@extends('layouts.app')
@section('page-title', 'Create User & Assign Role')
@section('content')
<form action="{{ url('system-setting/users') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
	<div class="col-md-4">
		<div class="box box-default">
			<div class="box-header">
                <h3 class="box-title">General Information</h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="name">Name *</label>
					<input type="text" name="name" class="form-control required" value="{{ old('name')}}" />
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="box box-default">
			<div class="box-header">
				<h3 class="box-title">Username & Password *</h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="username">Username (Email) *</label>
					<input type="text" name="username" class="form-control required email" value="{{ old('username')}}" autocomplete="off"/>
				</div>
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="password" name="password" class="form-control required password" value="{{ old('password')}}" autocomplete="off"/>
				</div>
				<div class="form-group">
					<label for="confirm_password">Confirm Password *</label>
					<input type="password" name="confirm_password" class="form-control required confirmpassword" value="{{ old('confirm_password')}}" autocomplete="off"/>
				</div>
				<div class="form-group">
					<label for="confirm_password">Profile Picture (Max Size : 2 MB)</label>
					<input type="file" name="profile_pic" class="form-control" />
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="box box-default">
			<div class="box-header">
				<h3 class="box-title">Assign Roles *</h3>
			</div>
			<div class="box-body no-padding">
				<table id="user-roles" class="table table-striped table-condensed">
					<thead>
						<tr>
							<th class="col-md-1 text-center">#</th>
							<th>Role</th>
						</tr>
					</thead>
					<tbody>
						@forelse($roles as $role)
						<tr>
							<td class="text-center">
								<input type="checkbox" value="{{$role->id}}" name="roles[]" />
							</td>
							<td>{{$role->name}}</td>
						</tr>
						@empty
						<tr>
							<td colspan="2">
								No roles found
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			<div class="box-footer text-center">
				<button type="submit" class="btn btn-success margin-r-5 btn-flat btn-sm"><i class="fa fa-upload"></i> CREATE USER</button>
				<a href="{{ url('system-setting/users') }}" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-undo"></i> CANCEL</a>
			</div>
		</div>
	</div>
</div>
</form>
@endsection