@extends('layouts.app-auth')

@section('title', 'Login - Surat Desa')

@section('content')
<!-- BEGIN login -->
<div class="login">
    <!-- BEGIN login-content -->
    <div class="login-content">
        <form action="{{ route('login.post') }}" method="POST" name="login_form">
            @csrf
            <h1 class="text-center">Surat Desa</h1>
            <div class="text-muted text-center mb-4">
                Silahkan login untuk melanjutkan.
            </div>

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control form-control-lg fs-15px"
                        value="{{ old('email') }}" placeholder="username@address.com">
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex">
                    <label class="form-label">Password</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control form-control-lg fs-15px"
                        placeholder="Enter your password">
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="customCheck1">
                    <label class="form-check-label fw-500" for="customCheck1">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
        </form>
    </div>
    <!-- END login-content -->
</div>
<!-- END login -->
@endsection