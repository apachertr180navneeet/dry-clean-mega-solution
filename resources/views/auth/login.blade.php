@extends('backend.layouts.auth_app')
@section('content')
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4  login_page_section_hp">
                <!-- Login -->
                <div class="card">
                    {{-- @dd("hello Tenant"); --}}
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4">
                            <a href="javascript:void(0);" class="app-brand-link gap-2">
                                <img src="{{ url('theam/Images/logo.png') }}" style="width: 130px;">
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 pt-2 text_14355a_hp">Welcome to Mega Solutions! 👋</h4>
                        <p class="mb-4">Please sign-in to your account and start the adventure</p>
                        @if (Session::has('error'))
                            <span class="text-danger">{{ Session::get('error') }}</span>
                        @endif
                        @if (Session::has('success'))
                            <span class="text-success">{{ Session::get('success') }}</span>
                        @endif
                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email or username" value="{{old('email')}}" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                    <a href="{{ route('password.request') }}">
                                        <small>Forgot Password?</small>
                                    </a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @if ($errors->any())
                                    <span class="text-danger">{{ $errors->first() }}</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <button class="btn w-100 btn_1F446E_hp" type="submit"  id="Signinbtn">Sign in</button>
                                <div class="spinner" id="loader" style="display: none;"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
  const passwordInput = document.getElementById("password");
  const eyeIcon = passwordInput.nextElementSibling.querySelector("i");

  eyeIcon.addEventListener("click", function() {
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);
    this.classList.toggle("ti-eye-off");
    this.classList.toggle("ti-eye");
  });
});
</script>
