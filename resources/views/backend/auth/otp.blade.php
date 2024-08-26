@extends('backend.layouts.auth_app')
@section('content')
<!-- Content -->
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Login -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4">
                        <a href="javascript:void(0);" class="app-brand-link gap-2">
                            <img src="{{url('public/theam/Images/logo.png')}}" style="width: 130px;">
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2 text_14355a_hp">Welcome to Mega Solutions! 👋</h4>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <form id="formAuthentication" class="mb-3" action="{{route('otp.post')}}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- <div class="mb-3">
                            <label for="email" class="form-label">Enter EMail</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email"
                                autofocus />
                        </div> --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter OTP</label>
                            <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP"
                                autofocus />
                        </div>
                        <div class="mb-3">
                            <button class="btn btn_1F446E_hp w-100" type="submit">Submit</button>
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
