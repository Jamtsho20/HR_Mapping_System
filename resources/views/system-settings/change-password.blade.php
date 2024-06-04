@extends('layouts.app')
@section('page-title', 'Change Password')
@section('content')
<form action="{{ url('change-password') }}" method="POST">
@csrf
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Change Your Password</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="name">Old Password</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="name">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                </div>
                <div class="block-content block-content-full block-content-sm text-center">
                    <button type="submit" class="btn btn-success btn-flat btn-sm"><i class="fa fa-upload"></i> UPDATE PASSWORD</button>
                    <a href="{{ url('dashboard') }}" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-undo"></i> CANCEL</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection