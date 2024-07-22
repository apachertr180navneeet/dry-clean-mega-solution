@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="row align-items-center">
                        <div class="col-lg-10 col-md-6">
                            <h5 class="card-header">Add Admin</h5>
                        </div>
                        <div class="card-body">
                            <form id="formAuthentication" class="mb-3" action="{{ route('tenants.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="name"
                                            placeholder="Enter your username" value="" autofocus />
                                        <span class="text-danger" id="username_error">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="Enter your email" value="" />
                                        <span class="text-danger" id="email_error">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="email" class="form-label">Domain Name</label>
                                        <input type="text" class="form-control" id="domain" name="domain"
                                            placeholder="Enter domain name" value="" />
                                        <span class="text-danger" id="domain_error">
                                            @error('domain')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3 form-password-toggle">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password" />
                                            <span class="input-group-text cursor-pointer"><i
                                                    class="ti ti-eye-off"></i></span>
                                        </div>
                                        <label id="password-error" class="error" for="password"></label>
                                        <span class="text-danger" id="password_error">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3 form-password-toggle">
                                        <label class="form-label" for="starting_date">Starting Date</label>
                                        <input type="date" name="starting_date" class="form-control date-input"
                                            id="starting_date" />
                                        <span class="text-danger" id="starting_date_error">
                                            @error('starting_date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3 form-password-toggle">
                                        <label class="form-label" for="end_date">End Date</label>
                                        <input type="date" name="end_date" class="form-control date-input"
                                            id="end_date" />
                                        <span class="text-danger" id="end_date_error">
                                            @error('end_date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="Add_order_btn_area text-end mb-2">
                                        <button class="btn btn_1F446E_hp">Save</button>
                                        <a href="{{ route('tenants.store') }}" class="btn btn_1F446E_hp">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).ready(function() {
            //Validate Username
            // alert("+++++");
            $('#username').on('input', function() {
                var name = $('#username').val().trim();
                if (name.length >= 50) {
                    $(this).attr('maxlength', 50);
                } else {
                    $(this).removeAttr('maxlength');
                }
                if (name && (name.length > 50)) {
                    $('#username_error').empty();
                }
            });
            $.validator.addMethod("noSpecialChars", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
            }, "Username must not contain special characters");

            $.validator.addMethod("greaterThan", function(value, element, params) {
                var start_date = new Date($('#starting_date').val());
                var end_date = new Date(value);
                return end_date > start_date;
            }, "End date must be greater than start date");
            $.validator.addMethod("noSpaces", function(value, element) {
            return this.optional(element) || /^[^\s].*$/.test(value);
        }, "Username must not contain spaces");
            $.validator.addMethod("minBookingDate", function(value, element) {
                var selectedDate = new Date(value);
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0); // Set hours to midnight for comparison
                return selectedDate >= currentDate;
            }, "Booking date cannot be earlier than today.");
            $.validator.addMethod("yearOnlyFourDigits", function(value, element) {
                var year = value.split('-')[0];
                return this.optional(element) || (year.length === 4);
            }, "Year must be exactly four digits");


            $("#formAuthentication").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 50,
                        noSpecialChars: true,
                        noSpaces: true
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 50,
                    },
                    domain: {
                        required: true,
                        maxlength: 50,
                        noSpaces: true
                    },
                    password: {
                        required: true,
                    },
                    starting_date: {
                        required: true,
                        date: true,
                        minBookingDate: true,
                        yearOnlyFourDigits: true
                    },
                    end_date: {
                        required: true,
                        date: true,
                        greaterThan: true,
                        yearOnlyFourDigits: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter username",
                        minlength: "Plese enter username minimun 2 character",
                        maxlength: "Please enter username maximum 50 character",
                        noSpecialChars: "Username must not contain special characters",
                        noSpaces: "Username must not contain spaces"
                    },
                    email: {
                        required: "Please enter email address",
                        email: "Please enter a valid email address",
                        maxlength: "Please enter an email with a maximum of 50 characters"
                    },
                    domain: {
                        required: "Please enter domain",
                        maxlength: "Please enter an email with a maximum of 50 characters",
                        noSpaces: "Domain must not contain spaces"
                    },
                    password: {
                        required: "Please enter password",
                    },
                    starting_date: {
                        required: "Please enter starting date",
                        date: "Please enter a valid date",
                        yearOnlyFourDigits: "Year must be exactly four digits"
                    },
                    end_date: {
                        required: "Please enter end date",
                        date: "Please enter a valid date",
                        yearOnlyFourDigits: "Year must be exactly four digits"
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") === "name") {
                        $('#username_error').text(error.text());
                    } else if (element.attr("name") === "email") {
                        $('#email_error').text(error.text());
                    } else if (element.attr("name") === "domain") {
                        $('#domain_error').text(error.text());
                    } else if (element.attr("name") === "starting_date") {
                        $('#starting_date_error').text(error.text());
                    } else if (element.attr("name") === "end_date") {
                        $('#end_date_error').text(error.text());
                    } else if (element.attr("name") === "password") {
                        $('#password_error').text(error.text());
                    } else {
                        error.insertAfter(element);
                    }
                },
                success: function(label, element) {
                    if ($(element).attr("name") === "name") {
                        $('#username_error').empty();
                    } else if ($(element).attr("name") === "email") {
                        $('#email_error').empty();
                    } else if ($(element).attr("name") === "domain") {
                        $('#domain_error').empty();
                    } else if ($(element).attr("name") === "starting_date") {
                        $('#starting_date_error').empty();
                    } else if ($(element).attr("name") === "end_date") {
                        $('#end_date_error').empty();
                    } else if ($(element).attr("name") === "password") {
                        $('#password_error').empty();
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            // Add input length restriction
            $(document).on('input', '#username, #email, #domain', function() {
                if ($(this).val().length > 50) {
                    $(this).val($(this).val().substring(0, 50));
                }
            });

            // $(document).on('input', '.date-input', function() {
            //     var value = $(this).val();
            //     if (!/^\d{0,2}(\-)?\d{0,2}(\-)?\d{0,4}$/.test(value)) {
            //         $(this).val(value.slice(0, -1));
            //     }
            //     if (/^\d{2}$/.test(value)) {
            //         $(this).val(value + '-');
            //     }
            //     if (/^\d{2}\-\d{2}$/.test(value)) {
            //         $(this).val(value + '-');
            //     }
            //     if (/^\d{2}\-\d{2}\-\d{4}$/.test(value) && value.length > 10) {
            //         $(this).val(value.substring(0, 10));
            //     }
            //     if (/^\d{2}\-\d{2}\-\d{5,}$/.test(value)) {
            //         $(this).val(value.slice(0, -1));
            //     }
            // });


        });
    });
</script>
{{-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script> --}}
