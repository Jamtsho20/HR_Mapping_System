@extends('layouts.app')
@section('page-title', 'Showing User Details')
@section('content')
<form action="{{ url('system-setting/users/reset-password/' . $user->id) }}" method="POST">
@csrf
    <div class="row">
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ asset($user->avatar ? get_image($user->avatar, 128, 128) : asset('images/no-image.jpg')) }}" alt="User profile picture">
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">{{ $user->mobile_no }}</p>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                        <b>Email</b> <a class="pull-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                        <b>Last Login</b> <a class="pull-right">{{ $user->last_login }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Assigned Role(s)</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-striped">
                        <thead>
                            <th class="text-center">#</th>
                            <th>Role(s)</th>
                        </thead>
                        <tbody>
                            @foreach ($user->roles as $role)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="">{{ $role->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Reset Password</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="name">New Password</label>
                        <input type="password" name="new_password" class="form-control required" value="" />
                    </div>
                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control required" value="" />
                    </div>
                </div>
                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-success btn-flat btn-sm"><i class="fa fa-upload"></i> RESET PASSWORD</button>
                    <a href="{{ url('system-setting/users') }}" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-undo"></i> CANCEL</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection