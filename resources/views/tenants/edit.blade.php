@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="row align-items-center">
                        <div class="col-lg-10 col-md-6">
                            <h5 class="card-header">Edit Admin</h5>
                        </div>
                        <div class="card-body">
                            <form id="formAuthentication" class="mb-3" action="{{ route('tenants.update', $tenents->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control name" id="username" name="name"
                                            value="{{ $tenents->name }}" />
                                        <span class="name-error text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control email" id="email" name="email"
                                            value="{{ $tenents->email }}" />
                                        <span class="email-error text-danger">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="domain" class="form-label">Domain Name</label>
                                        <input type="text" class="form-control domain" id="domain" name="domain"
                                            placeholder="Enter domain name" value="{{ $tenents->domains[0]->domain }}" />
                                        <span class="domain-error text-danger">
                                            @error('domain')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="starting_date" class="form-label">Starting Date</label>
                                        <input type="date" name="starting_date" class="form-control starting_date"
                                            value="{{ $tenents->subscriptions[0]->starting_date }}" />
                                        <span class="starting_date-error text-danger">
                                            @error('starting_date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control end_date"
                                            value="{{ $tenents->subscriptions[0]->end_date }}" />
                                        <span class="end_date-error text-danger">
                                            @error('end_date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="active" class="form-label">Is Activate</label>
                                        <select name="active" id="active" class="form-select active-select">
                                            <option value="1" {{ $tenents->is_active == 1 ? 'selected' : '' }}>
                                                Activate</option>
                                            <option value="0" {{ $tenents->is_active == 0 ? 'selected' : '' }}>
                                                Deactivate</option>
                                        </select>
                                        <span class="active-error text-danger" style="display:none;"> </span>
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
            $.validator.addMethod("filesize", function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, "File size must be less than {0}");
            $.validator.addMethod("noSpecialChars", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
            }, "Username must not contain special characters");
            $.validator.addMethod("endDateAfterStartDate", function(value, element) {
                var startDate = new Date($("input[name='starting_date']").val());
                var endDate = new Date(value);
                return this.optional(element) || endDate >= startDate;
            }, "End date must be after the start date");
            $.validator.addMethod("noSpaces", function(value, element) {
            return this.optional(element) || /^[^\s].*$/.test(value);
        }, "Username must not contain spaces");

            $.validator.addMethod("yearOnlyFourDigits", function(value, element) {
                var year = value.split('-')[0];
                return this.optional(element) || (year.length === 4);
            }, "Year must be exactly four digits");

            $("#formAuthentication").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 50,
                        noSpecialChars: true,
                        noSpaces: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    domain: {
                        required: true,
                        maxlength: 50,
                        noSpaces: true
                    },
                    starting_date: {
                        required: true,
                        date: true,
                        yearOnlyFourDigits: true
                        
                    },
                    end_date: {
                        required: true,
                        date: true,
                        endDateAfterStartDate: true,
                        yearOnlyFourDigits: true
                    },
                    active: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the username",
                        maxlength: "Username must be less than 50 characters",
                        noSpecialChars: "Username must not contain special characters",
                        noSpaces: "Username must not contain spaces"
                    },
                    email: {
                        required: "Please enter the email",
                        email: "Please enter a valid email address"
                    },
                    domain: {
                        required: "Please enter the domain name",
                        maxlength: "Domain name must be less than 50 characters",
                        noSpaces: "Domain must not contain spaces"
                    },
                    starting_date: {
                        required: "Please enter the starting date",
                        date: "Please enter a valid date",
                        yearOnlyFourDigits: "Year must be exactly four digits"
                    },
                    end_date: {
                        required: "Please enter the end date",
                        date: "Please enter a valid date",
                        endDateAfterStartDate: "End date must be after the start date",
                        yearOnlyFourDigits: "Year must be exactly four digits"
                    },
                    active: {
                        required: "Please select activation status"
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") === "name") {
                        element.next('.name-error').text(error.text()).show();
                    } else if (element.attr("name") === "email") {
                        element.next('.email-error').text(error.text()).show();
                    } else if (element.attr("name") === "domain") {
                        element.next('.domain-error').text(error.text()).show();
                    } else if (element.attr("name") === "starting_date") {
                        element.next('.starting_date-error').text(error.text()).show();
                    } else if (element.attr("name") === "end_date") {
                        element.next('.end_date-error').text(error.text()).show();
                    } else if (element.attr("name") === "active") {
                        element.next('.active-error').text(error.text()).show();
                    } else {
                        error.insertAfter(element);
                    }
                },
                success: function(label, element) {
                    if ($(element).attr("name") === "name") {
                        $(element).next('.name-error').hide();
                    } else if ($(element).attr("name") === "email") {
                        $(element).next('.email-error').hide();
                    } else if ($(element).attr("name") === "domain") {
                        $(element).next('.domain-error').hide();
                    } else if ($(element).attr("name") === "starting_date") {
                        $(element).next('.starting_date-error').hide();
                    } else if ($(element).attr("name") === "end_date") {
                        $(element).next('.end_date-error').hide();
                    } else if ($(element).attr("name") === "active") {
                        $(element).next('.active-error').hide();
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault(); // Prevent default form submission
                    let valid = true;

                    $("#formAuthentication").find('select.active-select').each(function() {
                        if ($(this).val() === "") {
                            $(this).next('.active-error').text(
                                "Please select activation status").show();
                            valid = false;
                        } else {
                            $(this).next('.active-error').hide();
                        }
                    });

                    if (valid) {
                        form.submit(); // Submit the form if valid
                    } else {
                        alert("Please correct the errors in the form.");
                    }
                }
            });

            // Add focusout and change event listeners for validation
            $(document).on('focusout', '.name', function() {
                $(this).valid();
            });

            $(document).on('focusout', '.email', function() {
                $(this).valid();
            });

            $(document).on('change', '.domain', function() {
                $(this).valid();
            });

            $(document).on('focusout', '.starting_date', function() {
                $(this).valid();
            });

            $(document).on('change', '.end_date', function() {
                $(this).valid();
            });

            $(document).on('change', '.active-select', function() {
                $(this).valid();
            });

            // Limit input length to 20 characters for name and email fields
            $(document).on('input', '.name, .email, .domain', function() {
                if ($(this).val().length > 50) {
                    $(this).val($(this).val().substring(0, 50));
                }
            });

            // Limit year input to exactly 4 digits in starting_date and end_date fields
            // $(document).on('input', '.starting_date, .end_date', function() {
            //     let value = $(this).val();
            //     if (/^\d{2}-\d{2}-\d{4}$/.test(value)) {
            //         // Correct format
            //         let year = value.split('-')[2];
            //         if (year.length > 4) {
            //             $(this).val(value.substring(0, 6) + year.substring(0, 4));
            //         }
            //     } else {
            //         // Prevent input if it doesn't match the format
            //         $(this).val(value.replace(/[^0-9-]/g, '').substring(0, 10));
            //     }
            // });

            // // Ensure the year part is limited to 4 digits on input
            // $(document).on('input', '.starting_date, .end_date', function() {
            //     let parts = $(this).val().split('-');
            //     if (parts.length === 3) {
            //         if (parts[2].length > 4) {
            //             parts[2] = parts[2].substring(0, 4);
            //             $(this).val(parts.join('-'));
            //         }
            //     }
            // });
        });
    });
</script>
