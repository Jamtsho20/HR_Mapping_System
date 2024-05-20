@extends('layouts.app')
@section('page-title', 'Users')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
                @component('layouts.includes.filter')
                    <div class="col-md-4 form-group">
                        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text" name="username" class="form-control" value="{{ request()->get('username') }}" placeholder="Username">
                    </div>
                @endcomponent
            </div>
			<div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover border table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th class="text-center">Account Active</th>
                                <th class="text-center"><i class="fa fa-asterisk"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">{!! $user->isActive() ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                                    <td class="text-center">
                                        <a href="{{ url('system-setting/users/' . $user->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                        @if ($privileges->edit)
                                            <a href="{{ url('system-setting/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i> Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-danger text-center">No users to be displayed</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {{ $users->links() }}
                </div>
			</div>
		</div>
	</div>
</div>
@endsection