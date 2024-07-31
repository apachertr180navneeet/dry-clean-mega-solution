@extends('backend.layouts.auth_app')
@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Forgot Password -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4">
                        <a href="javascript:void(0);" class="app-brand-link gap-2">
                            <img src="{{ url('theam/Images/logo.png') }}" style="width: 130px;">
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2 text_14355a_hp">Reset Password? 🔒</h4>
                    <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                    @if (Session::has('error'))
                        <span class="text-danger">{{ Session::get('error') }}</span>
                    @endif
                    <form id="formAuthentication" class="mb-3" action="{{ route('store.new.password') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data['id'] }}" /> 
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label" for="password">New Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="new_password" class="form-control" name="new_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" value="" autofocus />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>

                            </div>
                            <div class="mb-3">
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label" for="password">Confirm Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password"  class="form-control" id="confirm_password" name="confirm_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" value="" autofocus/>
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>

                            </div>
                           
                            <button class="btn btn_1F446E_hp w-100">Save</button>
                        </form>
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center text_14355a_hp">
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
