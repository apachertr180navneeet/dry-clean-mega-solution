@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="card mb-3">
            <div class="card-body p-3">
                <h4 class="fw-bold mb-0"><span class="text-muted fw-light"> Add /</span> Update Profile</h4>
            </div>
        </div>
        <div class="row">
            <!-- Browser Default -->
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="formAuthentication" class="mb-3" action="{{ url('profile/update/' . $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="name" placeholder="Enter your username" value="{{ !empty($user->name) ? $user->name : old('name') }}" autofocus />
                                <span class="name-error text-danger" style="display:none;"></span>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" autofocus />
                                <span class="image-error text-danger" style="display:none;"></span>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ !empty($user->email) ? $user->email : old('email') }}" readonly />
                                <span class="email-error text-danger" style="display:none;"></span>
                            </div>
                            @if (empty($user->id))
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                <span class="password-error text-danger" style="display:none;"></span>
                            </div>
                            @endif
                            <button class="btn btn-primary d-grid w-100" type="submit">{{ !empty($user->id) ? 'Update' : 'Submit' }}</button>
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
            $(document).ready(() => {
        $.validator.addMethod("filesize", function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        }, "File size must be less than {0}");
        $.validator.addMethod("noSpecialChars", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
            }, "Username must not contain special characters");

        $("#formAuthentication").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 50,
                    noSpecialChars: true
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 50
                },
                image: {
                    required: false,
                    extension: "jpg|jpeg|png|bmp",
                    filesize: 5242880 // 5 MB in bytes
                },
                password: {
                    required: function() {
                        return $('#password').length > 0;
                    },
                    minlength: 6
                }
            },
            messages: {
                name: {
                    required: "Please enter your username",
                    minlength: "Username must be at least 2 characters long",
                    maxlength: "Username must be less than 50 characters",
                    noSpecialChars: "Username must not contain special characters"
                },
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address",
                    maxlength: "Email must be less than 50 characters"
                },
                image: {
                    extension: "Please upload only image files (jpg, jpeg, png, bmp)",
                    filesize: "Image size must be less than 5 MB"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Password must be at least 6 characters long"
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "name") {
                    element.next('.name-error').text(error.text()).show();
                } else if (element.attr("name") === "email") {
                    element.next('.email-error').text(error.text()).show();
                } else if (element.attr("name") === "image") {
                    element.next('.image-error').text(error.text()).show();
                } else if (element.attr("name") === "password") {
                    element.next('.password-error').text(error.text()).show();
                } else {
                    error.insertAfter(element);
                }
            },
            success: function(label, element) {
                if ($(element).attr("name") === "name") {
                    $(element).next('.name-error').hide();
                } else if ($(element).attr("name") === "email") {
                    $(element).next('.email-error').hide();
                } else if ($(element).attr("name") === "image") {
                    $(element).next('.image-error').hide();
                } else if ($(element).attr("name") === "password") {
                    $(element).next('.password-error').hide();
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        // Limit input length for username and email
        $('#username, #email').on('input', function() {
            if ($(this).val().length > 50) {
                $(this).val($(this).val().substring(0, 50));
            }
        });

        // Allow only valid email format in email field
        $('#email').on('input', function() {
            if (!this.value.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                $('.email-error').text("Please enter a valid email address").show();
            } else {
                $('.email-error').hide();
            }
        });
    });
    });
    </script>
