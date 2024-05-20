@extends('auth.partials.main')
@section('page-title', 'Forgot Pasword')
@section('content')
    <!-- PAGE -->
    <div class="page login-page">
        <div>
            <!-- CONTAINER OPEN -->
            <div class="container-login100">
                <div class="wrap-login100 p-0">
                    <div class="card-body">
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}" class="login100-form validate-form">
                            @csrf
                            <span class="login100-form-title"> Forgot Password?</span>
                            <div class="wrap-input100 ">
                                <input class="input100" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                <span class="symbol-input100">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                </span>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mb-2" />

                            <div class="container-login100-form-btn">
                                <button type="submit" class="login100-form-btn btn-primary"> Email Password Reset Link </button>
                            </div>
                            <div class="text-center pt-3">
                                <p class="mb-0">
                                    <a href="{{ route('login') }}" class="text-primary ms-1">Sign In</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
    <!-- End PAGE -->
@endsection