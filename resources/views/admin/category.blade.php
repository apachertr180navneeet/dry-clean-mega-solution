@extends('backend.layouts.app')
@section('content')
<style>
    .check-error{
        color: #6f6b7d !important;
    }
</style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp Add_order_page_section">
                <div class="card mt-3">
                    <div class="card-body">
                        <h4>Category</h4>
                        <form method="post" action="{{ route('add.category.details') }}" enctype="multipart/form-data"
                            id="categoryForm">
                            @csrf

                            <input type="hidden" name="cat[]" id="main_id" value="0" />
                            <div class="row">
                                <div class="col-12 addsec mb-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 ">

                                            <select name="category[]" id="" class="form-select cat-select check-error mb-2">
                                                <option value="" selected disabled> Select Category</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="category-error text-danger" style="display:none;">Item name is
                                                required and must be less than 50 characters.</span>
                                        </div>
                                        <div class="col-md-4 mb-2"> <button type="button"
                                                class="btn btn_1F446E_hp
                                            addsection">Add
                                                Item</button></div>
                                                <div id="error-message" style="display: none; color: red;"></div>
                                        <div class="table-responsive mt-2">

                                            <table class="table table-hover ">
                                                <tbody class="addtbody">

                                                    <tr>
                                                        {{-- <td>01</td> --}}
                                                        <td>
                                                            <input type="text" name="item_name[0][]" id=""
                                                                class="form-control item-name check-error" placeholder="Item">
                                                            <span class="name-error text-danger" style="display:none;">Item
                                                                name is required and must be less than 50 characters.</span>
                                                        </td>
                                                        <td>
                                                            <select name="item_type[0][]" id="" class="form-select service-select check-error">
                                                                <option value="" selected disabled> Select item type
                                                                </option>
                                                                @foreach ($producttypes as $producttype)
                                                                    <option value="{{ $producttype->id }}">{{ $producttype->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="type-error text-danger" style="display:none;">Item
                                                                type is required and must be less than 50 characters.</span>
                                                        </td>
                                                        <td>
                                                            <select name="service[0][]" id=""
                                                                class="form-select service-select check-error">
                                                                <option value="" selected disabled> Select Service
                                                                </option>
                                                                @foreach ($services as $Service)
                                                                    <option value="{{ $Service->id }}">{{ $Service->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="service-error text-danger"
                                                                style="display:none;">Item name is required and must be less
                                                                than 50 characters.</span>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="price[0][]" id=""
                                                                class="form-control price" placeholder="Price">
                                                            <span class="price-error text-danger"
                                                                style="display:none;">Price is required and must be a
                                                                number.</span>
                                                        </td>
                                                        <td>
                                                            <input type="file" name="image[0][]" id=""
                                                                class="form-control image">
                                                            <span class="image-error text-danger"
                                                                style="display:none;"></span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button type="button" data-count_row="0"
                                                                    class="btn p-0 me-2 addnewrow"><i
                                                                        class="fa-solid fa-circle-plus fs-3"></i></button>
                                                                {{-- <button class="btn p-0 me-2"><i
                                                                    class="fa-solid fa-circle-minus text-danger fs-3"></i></button>
                                                            --}}
                                                            </div>
                                                        </td>
                                                    </tr>


                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <div id="append_select_column">

                                </div>
                            </div>


                            <div class="row justify-content-end">
                                <div class="col-md-4">
                                    <div class="Add_order_btn_area text-end mb-2">
                                        <button type="submit" class="btn">Save</button>
                                        <a href="{{ route('categorylist') }}" class="btn btn_1F446E_hp">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endsection
        <script>
            document.addEventListener("DOMContentLoaded", function() {
               function applyValidation() {
                            $("#categoryForm").validate({
                                rules: {
                                    "category[]": {
                                        required: true
                                    },
                                    "item_name[0][]": {
                                        required: true,
                                        maxlength: 50
                                    },
                                    "item_type[0][]": {
                                        required: true,
                                        maxlength: 50
                                    },
                                    "service[0][]": {
                                        required: true
                                    },
                                    "price[0][]": {
                                        required: true,
                                        number: true,
                                        maxlength: 8
                                    },
                                },
                                messages: {
                                    "category[]": {
                                        required: "Please select a category"
                                    },
                                    "item_name[0][]": {
                                        required: "Please enter item name",
                                        maxlength: "Item name must be less than 50 characters"
                                    },
                                    "item_type[0][]": {
                                        required: "Please enter item type",
                                        maxlength: "Item type must be less than 50 characters"
                                    },
                                    "service[0][]": {
                                        required: "Please select a service"
                                    },
                                    "price[0][]": {
                                        required: "Please enter price",
                                        number: "Please enter a valid number",
                                        maxlength: "Price must be less than 8 characters"
                                    },
                                },
                                errorPlacement: function(error, element) {
                                    if (element.attr("name").includes("item_name")) {
                                        element.next('.name-error').text(error.text())
                                            .show();
                                    } else if (element.attr("name").includes("item_type")) {
                                        element.next('.type-error').text(error.text())
                                            .show();
                                    } else if (element.attr("name").includes("service")) {
                                        element.next('.service-error').text(error.text())
                                            .show();
                                    } else if (element.attr("name").includes("price")) {
                                        element.next('.price-error').text(error.text())
                                            .show();
                                    } else if (element.attr("name").includes("image")) {
                                        element.next('.image-error').text(error.text())
                                            .show();
                                    } else if (element.attr("name").includes("category")) {
                                        element.next('.category-error').text(error.text())
                                            .show();
                                    } else {
                                        error.insertAfter(element);
                                    }
                                },
                                success: function(label, element) {
                                    if ($(element).attr("name").includes("item_name")) {
                                        $(element).next('.name-error').hide();
                                    } else if ($(element).attr("name").includes(
                                            "item_type")) {
                                        $(element).next('.type-error').hide();
                                    } else if ($(element).attr("name").includes(
                                            "service")) {
                                        $(element).next('.service-error').hide();
                                    } else if ($(element).attr("name").includes("price")) {
                                        $(element).next('.price-error').hide();
                                    } else if ($(element).attr("name").includes("image")) {
                                        $(element).next('.image-error').hide();
                                    } else if ($(element).attr("name").includes(
                                            "category")) {
                                        $(element).next('.category-error').hide();
                                    }
                                },
                                submitHandler: function(form, event) {
                                    event
                                        .preventDefault(); // Prevent default form submission
                                    let valid = true;
                                    // $("#categoryForm").find('select.cat-select').each(function() {
                                    //     $(this).rules("add",
                                    //         {
                                    //             required: true,
                                    //             messages: {
                                    //                 required: "Name is required",
                                    //             }
                                    //         });
                                    // });
                                    $(form).find('input[name^="item_name"], input[name^="item_type"], select[name^="service"], input[name^="price"], input[name^="image"]').each(function() {
                                        if (!$(this).valid()) {
                                            valid = false;
                                        }
                                    });

                                    $("#categoryForm").find('select.cat-select').each(
                                        function() {
                                            if ($(this).val() === "") {
                                                $(this).next('.category-error').text(
                                                        "Please select a category")
                                                    .show();
                                                valid = false;
                                            } else {
                                                $(this).next('.category-error').hide();
                                            }
                                        });

                                    $("#categoryForm").find('select.service-select').each(
                                        function() {
                                            if ($(this).val() === "") {
                                                $(this).next('.service-error').text(
                                                        "Please select a service")
                                                    .show();
                                                valid = false;
                                            } else {
                                                $(this).next('.service-error').hide();
                                            }
                                        });

                                    if (valid) {
                                        form.submit(); // Submit the form if valid
                                    }
                                    // else {
                                    //     $('#error-message').text('Please correct the errors in the form.').show();
                                    // }
                                }
                            });
                        }
                        $(document).ready(() => {
                        $.validator.addMethod("filesize", function(value, element, param) {
                            return this.optional(element) || (element.files[0].size <= param);
                        }, "File size must be less than {0}");


                        applyValidation();

                        // Reapply validation on dynamic addition
                        // $(document).on('click', '.addnewrow, .addsection', function() {
                        //     applyValidation();
                        // });
                        $(document).on('click', '.addnewrow', function() {
                            applyValidation();
                        });
                        $(document).on('click', '.addsection', function() {
                            applyValidation();
                        });

                        $(document).on('focusout', '.item-name', function() {
                            $(this).valid();
                        });

                        $(document).on('focusout', '.item-type', function() {
                            $(this).valid();
                        });

                        $(document).on('change', '.service-select', function() {
                            $(this).valid();
                        });

                        $(document).on('focusout', '.price', function() {
                            $(this).valid();
                        });

                        $(document).on('change', '.image', function() {
                            $(this).valid();
                        });

                        $(document).on('change', '.cat-select', function() {
                            $(this).valid();
                        });

                        // Limit input length to 20 characters for item_name and item_type fields
                        $(document).on('input', '.item-name, .item-type', function() {
                            if ($(this).val().length > 50) {
                                $(this).val($(this).val().substring(0, 50));
                            }
                        });

                        // Allow only numeric input in the price field
                        $(document).on('input', '.price', function() {
                            this.value = this.value.replace(/[^0-9.]/g, '');
                            if (this.value.length > 8) {
                                this.value = this.value.substring(0, 8);
                            }
                            var parts = this.value.split('.');
                            if (parts[1] && parts[1].length > 2) {
                                this.value = parts[0] + '.' + parts[1].substring(0, 2);
                            }
                        });
                    });
                // applyValidation();
                $(function() {
                    // Adding row on click to Add New Row button
                    $(document).on('click', '.addnewrow', function() {
                        // alert("++++");
                        var row_id = $(this).closest('tr').parent();
                        var new_count = parseInt($(this).data('count_row'));

                        console.log("click add colunm");
                        let dynamicRowHTML = `
                        <tr class="rowClass">
                            <td><input type="text" name="item_name[` + new_count + `][]" id="" class="form-control check-error" placeholder="Item">
                                <span class="name-error text-danger" style="display:none;">Item name is required and must be less than 20 characters.</span></td>
                            <td>
                                <select name="item_type[` + new_count + `][]" id="" class="form-select check-error">
                                    <option value="" selected disabled> Select Service</option>
                                    <?php foreach ($producttypes as $producttype): ?>
                                        <option value="<?php echo $producttype->id; ?>"><?php echo $producttype->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="type-error text-danger" style="display:none;">Item type is required and must be less than 20 characters.</span>
                            </td>
                            <td>
                                <select name="service[` + new_count + `][]" id="" class="form-select check-error">
                                    <option value="" selected disabled> Select Service</option>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?php echo $service->id; ?>"><?php echo $service->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="service-error text-danger" style="display:none;">Service is required.</span>
                            </td>
                            <td><input type="text" name="price[` + new_count + `][]" id="" class="form-control" placeholder="Price">
                                <span class="price-error text-danger" style="display:none;">Price is required and must be a number.</span></td>
                            <td><input type="file" name="image[` + new_count + `][]" id="" class="form-control" >
                                <span class="image-error text-danger" style="display:none;"></span></td>
                            <td>
                                <div class="d-flex">
                                    <button type="button" data-count_row="` + new_count + `" class="btn p-0 me-2 addnewrow"><i class="fa-solid fa-circle-plus fs-3"></i></button>
                                    <button type="button" class="btn p-0 me-2 remove"><i class="fa-solid fa-circle-minus text-danger fs-3"></i></button>
                                </div>
                            </td>
                        </tr>`;
                        row_id.append(dynamicRowHTML);
                        applyValidation();
                    });

                    $(document).on('click', '.remove', function() {
                        // alert("++++");
                        $(this).closest('tr').remove();
                    });

                    $(document).on('click', '.addsection', function() {
                        var new_section = $("#main_id").val();
                        // alert(new_section);
                        var new_count = parseInt(new_section) + 1;
                        console.log("click add addsection");
                        let dynamicRowHTML = `
                        <div class="row mb-2">
                            <div class="col-md-4 ">
                                <select name="category[]" id="" class="form-select category-select check-error">
                                    <option value="" selected disabled> Select Category</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?php echo $product->id; ?>"><?php echo $product->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="category-error text-danger" style="display:none;">Category is required.</span>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-hover ">
                                    <tbody class="addtbody">
                                        <tr>
                                            <td>
                                                <input type="text" name="item_name[` + new_count + `][]" id="" class="form-control check-error" placeholder="Item">
                                                <span class="name-error text-danger" style="display:none;">Item name is required and must be less than 20 characters.</span>
                                            </td>
                                            <td>
                                                <input type="text" name="item_type[` + new_count + `][]" id="" class="form-control check-error" placeholder="Item Type">
                                                <span class="type-error text-danger" style="display:none;">Item type is required and must be less than 20 characters.</span>
                                            </td>
                                            <td>
                                                <select name="service[` + new_count + `][]" id="" class="form-select check-error">
                                                    <option value="" selected disabled> Select Service</option>
                                                    <?php foreach ($services as $service): ?>
                                                        <option value="<?php echo $service->id; ?>"><?php echo $service->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="service-error text-danger" style="display:none;">Service is required.</span>
                                            </td>
                                            <td>
                                                <input type="text" name="price[` + new_count + `][]" id="" class="form-control" placeholder="Price">
                                                <span class="price-error text-danger" style="display:none;">Price is required and must be a number.</span>
                                            </td>
                                            <td><input type="file" name="image[` + new_count + `][]" id="" class="form-control" >
                                                <span class="image-error text-danger" style="display:none;"></span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <button type="button" data-count_row="` + new_count + `" class="btn p-0 me-2 addnewrow"><i
                                                            class="fa-solid fa-circle-plus fs-3"></i></button>
                                                    {{-- <button class="btn p-0 me-2"><i class="fa-solid fa-circle-minus text-danger fs-3"></i></button> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                        $('#append_select_column').append(dynamicRowHTML);
                        $("#main_id").val(new_count);
                        applyValidation();
                    });




                });
            });
        </script>
