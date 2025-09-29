
@include('layouts.partials.header')
@extends('auth.partials.main')
@section('page-title', 'Reset Password')
@section('content')
    <!-- PAGE -->
    <div class="page login-page">
        <!-- CONTAINER OPEN -->
        <div class="container-login100">
            <div class="wrap-login100 p-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.store') }}" class="login100-form validate-form">
                        @csrf
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <span class="login100-form-title"> Reset Password </span>
                        <div class="wrap-input100 ">
                            <input class="input100" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            <span class="symbol-input100">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mb-2" />

                        <div class="wrap-input100">
                            <input class="input100" type="password" name="password" placeholder="Password" required>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mb-2" />

                        <div class="wrap-input100">
                            <input class="input100" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mb-2" />

                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary"> Reset Password </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
    <!-- End PAGE -->
@endsection
