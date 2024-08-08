@if (!Auth::user())
    {{-- <script type="text/javascript">
        window.location = "{{ route('login') }}";
    </script> --}}
    @php
        $errorMessage = 'Please contact the superadmin for access.';
        return redirect()->route('login')->withErrors([$errorMessage]);
    @endphp
@else
@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        @php
        $loginemail = Auth::user()->email;
        @endphp
        <div class="card mb-3">
            <div class="card-body p-3">
                <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Add /</span> Update Profile</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="card">
                    <h5 class="card-header">Update Profile</h5>
                    <div class="card-body">
                        <h4 class="mb-4 pt-2">Reset Password 🔒</h4>
                        <p class="mb-4">for <span class="fw-bold">{{ !empty($loginemail) ? $loginemail : '' }}</span></p>
                        <form id="formAuthentication" action="{{ route('change.password.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Old Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control password" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>

                                @if ($errors->has('password'))
                                    <span class="password-error text-danger">{{ $errors->first('password') }}</span>
                                @else
                                    <span class="password-error text-danger" style="display:none;"></span>
                                @endif
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="newpassword">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="newpassword" class="form-control newpassword" name="new_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @if ($errors->has('new_password'))
                                <span class="newpassword-error text-danger">{{ $errors->first('new_password') }}</span>
                            @else
                                <span class="newpassword-error text-danger" style="display:none;"></span>
                            @endif
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="confirmpassword">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="confirmpassword" class="form-control confirmpassword" name="confirm_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @if ($errors->has('confirm_password'))
                                <span class="confirmpassword-error text-danger">{{ $errors->first('confirm_password') }}</span>
                            @else
                                <span class="confirmpassword-error text-danger" style="display:none;"></span>
                            @endif
                            </div>
                            <button class="btn btn-primary d-grid mb-3" type="submit">Set new password</button>
                            {{-- <div class="text-left">
                                <a href="{{ route('login') }}">
                                    <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                    Back to login
                                </a>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Browser Default -->
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).ready(function() {
            $.validator.addMethod("filesize", function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, "File size must be less than {0}");

            $.validator.addMethod("notSamePasswords", function(value, element) {
            let oldPassword = $('#password').val();
            let newPassword = $('#newpassword').val();
            // let confirmPassword = $('#confirmpassword').val();
            return oldPassword !== newPassword;
            }, "Old password, new password should not be the same");


            $("#formAuthentication").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6,
                    notSamePasswords: true,
                    // notEqualTo: "#newpassword"
                    },
                    new_password: {
                        required: true,
                        minlength: 6,
                        notSamePasswords: true
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#newpassword",
                        // notSamePasswords: true
                    }
                },
                messages: {
                    password: {
                        required: "Please provide your old password",
                        minlength: "Password must be at least 6 characters long",
                        notEqualTo:'New password and confirm password should not match',
                        notSamePasswords: "Old password and new password should not be the same"
                    },
                    new_password: {
                        required: "Please provide a new password",
                        minlength: "New password must be at least 6 characters long",
                        notSamePasswords: "Old password and new password should not be the same"
                    },
                    confirm_password: {
                        required: "Please confirm your new password",
                        minlength: "Confirm password must be at least 6 characters long",
                        equalTo: "New password and confirm password should match"
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") === "password") {
                        element.closest('.form-password-toggle').find('.password-error').text(error.text()).show();
                    } else if (element.attr("name") === "new_password") {
                        element.closest('.form-password-toggle').find('.newpassword-error').text(error.text()).show();
                    } else if (element.attr("name") === "confirm_password") {
                        element.closest('.form-password-toggle').find('.confirmpassword-error').text(error.text()).show();
                    } else {
                        error.insertAfter(element);
                    }
                },
                success: function(label, element) {
                    if ($(element).attr("name") === "password") {
                        $(element).closest('.form-password-toggle').find('.password-error').hide();
                    } else if ($(element).attr("name") === "new_password") {
                        $(element).closest('.form-password-toggle').find('.newpassword-error').hide();
                    } else if ($(element).attr("name") === "confirm_password") {
                        $(element).closest('.form-password-toggle').find('.confirmpassword-error').hide();
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault(); // Prevent default form submission
                    if ($(form).valid()) {
                        form.submit(); // Submit the form if valid
                    } else {
                        alert("Please correct the errors in the form.");
                    }
                }
            });

            // Add focusout and change event listeners for validation
            $(document).on('focusout', '.password', function() {
                $(this).valid();
            });

            $(document).on('focusout', '.newpassword', function() {
                $(this).valid();
            });

            $(document).on('focusout', '.confirmpassword', function() {
                $(this).valid();
            });

            // Limit input length to 50 characters for password fields
            $(document).on('input', '.password, .newpassword, .confirmpassword', function() {
                if ($(this).val().length > 50) {
                    $(this).val($(this).val().substring(0, 50));
                }
            });
        });
    });
</script>
@endif
