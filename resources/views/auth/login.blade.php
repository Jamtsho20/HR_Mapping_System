@include('layouts.partials.header')
@extends('auth.partials.main')
@section('page-title', 'Login')
@section('content')
<!-- PAGE -->
<div class="page login-page">
    <!-- CONTAINER OPEN -->

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Human Resource Management System
                    <br><br> Login
                </h2>
            </div>

        </div>
    </div>
    <div class="container-login100">
        <div class="wrap-login100 p-0">
            <div class="card-body">
                <img src="{{ asset('assets/images/brand/logo3.png') }}" class="img-fluid mx-auto d-block"
                    style="width:120px; height:80px" alt="logo">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />


                <span class="login100-form-title"></span>

                <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                    @csrf

                    <div class="wrap-input100 ">
                        <input class="input100" type="text" name="username" placeholder="Username"
                            value="{{ old('username') }}" required>
                        <span class="symbol-input100">
                            <i class="fa fa-user-circle" aria-hidden="true"></i>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mb-2" />

                    {{-- <div class="wrap-input100 validate-input" data-bs-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password" required>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div> --}}
                    <div class="wrap-input100 validate-input" data-bs-validate="Password is required" style="position: relative;">
                        <input id="passwordInput" class="input100" type="password" name="password" placeholder="Password" required>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                        <span class="toggle-password" onclick="togglePassword()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="fa fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mb-2" />

                    <label class="custom-control custom-checkbox mt-1">
                        <input type="checkbox" class="custom-control-input" name="remember">
                        <span class="custom-control-label">Remember me</span>
                    </label>
                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn btn-primary"> Login </button>
                    </div>
                    @if (!session('msg_error'))
                    <div class="text-center pt-3">
                        <p class="mb-0"><a href="{{ route('password.request') }}" class="text-primary ms-1">Forgot
                                Password?</a></p>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    @if (session('msg_error'))
    <div class="alert alert-danger text-center" style="width: 80%; margin: 0 auto;">
        {{ session('msg_error') }}
    </div>
    @endif

    <!-- CONTAINER CLOSED -->
</div>
<!-- End PAGE -->
@endsection
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("passwordInput");
        const toggleIcon = document.getElementById("toggleIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
</script>