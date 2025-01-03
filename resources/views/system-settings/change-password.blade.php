@extends('layouts.app')
@section('page-title', 'Change Password')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <form action="{{ url('change-password') }}" method="POST">
                @csrf
                <div class="form-group position-relative col-md-4">
                    <label for="old_password">Old Password</label>
                    <input type="password" id="old_password" name="old_password" class="form-control" required>
                    <span class="position-absolute toggle-password"
                        onclick="togglePasswordVisibility('old_password', this)"
                        style="right: 30px; top: 35px; cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="form-group position-relative col-md-4">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    <span class="position-absolute toggle-password"
                        onclick="togglePasswordVisibility('new_password', this)"
                        style="right: 30px; top: 35px; cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="form-group position-relative col-md-4">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    <span class="position-absolute toggle-password"
                        onclick="togglePasswordVisibility('confirm_password', this)"
                        style="right: 30px; top: 35px; cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="card-footer">
                    @include('layouts.includes.buttons', [
                    'buttonName' => 'UPDATE PASSWORD',
                    'cancelUrl' => url('dashboard'),
                    'cancelName' => 'CANCEL'
                    ])
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconElement) {
        const input = document.getElementById(inputId);
        const icon = iconElement.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection