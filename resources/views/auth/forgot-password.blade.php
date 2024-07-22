@extends('backend.layouts.auth_app')
@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4 ">
            <!-- Forgot Password -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4">
                        <a href="javascript:void(0);" class="app-brand-link gap-2">
                            <img src="{{url('theam/Images/logo.png')}}" style="width: 130px;">
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2 text_14355a_hp">Forgot Password? 🔒</h4>
                    <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                    @if (Session::has('error'))
                        <span class="text-danger">{{ Session::get('error') }}</span>
                    @endif
                    @if (Session::has('success'))
                        <span class="text-success">{{ Session::get('success') }}</span>
                    @endif
                    <form id="formAuthentication" class="mb-3" action="{{route('password.email')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" value="" autofocus />
                        </div>
                        <button class="btn btn_1F446E_hp w-100">Send Reset Link</button>
                    </form>
                    <div class="text-center">
                        <a href="{{route('login')}}" class="d-flex align-items-center justify-content-center text_14355a_hp">
                            <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                            Back to login
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
</div>
@endsection
