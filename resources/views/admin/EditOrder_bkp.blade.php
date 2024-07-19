@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp Add_order_page_section">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="client_list_heading_area">
                                <h4>@if (isset($order)) Edit @else Add @endif Order</h4>
                            </div>
                        </div>

                    </div>
                    @if (isset($order))
                    <form action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                @else
                    <form action="{{ route('add.order') }}" method="POST" id="addOrderFormValidation" enctype="multipart/form-data">
                @endif
                    {{-- <form action="{{ route('add.order') }}" method="post" id="addOrderFormValidation"> --}}
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="form-label">Client
                                                Number</label>
                                            <input type="text" value="{{ old('client_num', $order->client_num ?? '') }}" id="number" name="client_num" class="form-control"
                                                id="exampleFormControlInput1" placeholder="Daniel G. Depp">
                                        </div>

                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="form-label">Client Name</label>
                                            <input type="text" id="name" value="{{ old('client_name', $order->client_name ?? '') }}" name="client_name" class="form-control"
                                                id="exampleFormControlInput1" placeholder="Daniel G. Depp">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="form-label">Booking
                                                Date</label>
                                            <input type="date" id="booking_date" value="{{ old('booking_date', $order->booking_date ?? '') }}" name="booking_date" class="form-control"
                                                id="exampleFormControlInput1" placeholder="Daniel G. Depp">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="form-label">Booking
                                                Time</label>
                                            <input type="time" id="booking_time" value="{{ old('booking_time', $order->booking_time ?? '') }}" name="booking_time" class="form-control"
                                                id="exampleFormControlInput1" placeholder="Daniel G. Depp"
                                                value="12:00">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="form-label">Delivery
                                                Date</label>
                                            <input type="date" id="delivery_date" value="{{ old('delivery_date', $order->delivery_date ?? '') }}" name="delivery_date"
                                                class="form-control" id="exampleFormControlInput1"
                                                placeholder="Daniel G. Depp">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="form-label">Delivery
                                                Time</label>
                                            <input type="time" id="delivery_time" value="{{ old('delivery_time', $order->delivery_time ?? '') }}" name="delivery_time"
                                                class="form-control" id="exampleFormControlInput1"
                                                placeholder="Daniel G. Depp">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="form-label">Discount
                                                Offer</label>
                                            <select name="discount" id="" class="form-select">
                                                <option value="" selected>Select Discount Offer</option>
                                                @foreach ($discounts as $discount)
                                                    <option value="{{ $discount->id }}">{{ $discount->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3">
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Gross Total:</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6>180.00</h6>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Discount Amount:</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6>0</h6>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Express Amount:</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <div class="form-check form-switch float-end">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckDefault">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Total Count:</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6>2 pc</h6>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Total Amount:</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6>180.00</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="Add_order_btn_area text-end">
                                            <button class="btn w-100" type="button" data-bs-toggle="modal"
                                                data-bs-target="#CreateOrder">Save</button>
                                        </div>
                                    </div>
                                    <!-- Create Order Model -->
<div class="modal fade" id="CreateOrder" tabindex="-1" aria-labelledby="CreateOrderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h5>Would you like to Create a New Order?</h5>
                <button type="button" class="btn btn-primary" id="yesButton" data-bs-toggle="modal" data-bs-target="#yes">Yes</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<!-- Print Order Model -->
<div class="modal fade" id="yes" tabindex="-1" aria-labelledby="yesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h5>Please Select from the Following Options</h5>
                <a type="button" class="btn btn-primary" href="{{ url('/admin/receipt') }}">
    <i class="fa-solid fa-file-invoice me-2"></i> Print Receipt
</a>

                <button type="button" class="btn btn-success"><i class="fa-solid fa-tag me-2"></i> Print Tag</button>
            </div>
        </div>
    </div>
</div>
<!-- end -->




                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="client_list_area_hp">
                                    <div class="client_list_heading_area w-100">
                                        <div class="client_list_heading_search_area w-100">
                                            <i class="menu-icon tf-icons ti ti-search"></i>
                                            <input type="search" class="form-control" placeholder="Searching ...">
                                        </div>
                                    </div>
                                </div>
                                <div class="border rounded p-2 mb-2">
                                    <div class="row">
                                        <div class="col-lg-9 col-md-9">
                                            <h6 class="mb-2 text-dark">Carpet(Item)</h6>
                                            <div>
                                                <span class="badge bg-light text-dark mb-2">Small upto4sqft(Category)</span>
                                                <span class="badge bg-success text-dark mb-2">Large(upto 10sqft)</span>
                                                <span class="badge bg-light text-dark mb-2">Medium(upto8sqft)</span>
                                                <span class="badge bg-light text-dark mb-2">Full w Lining</span>
                                                <span class="badge bg-light text-dark mb-2">Half w Lining</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 mb-2">
                                                    <h6 class="mb-0">Dry Clean(Operation/service)</h6>
                                                    <h6 class="mb-0 text-dark">110/pc(Price/pc)</h6>
                                                </div>
                                                <div class="col-lg-4 mb-2">
                                                    <h6 class="mb-0">Steam Press</h6>
                                                    <h6 class="mb-0 text-dark"> 50/pc</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 text-center ">
                                            <img src="{{ url('theam/Images/shirt/shirt.jpg') }}" alt="shirt "
                                                style="width: 50px;">
                                            <div class="Add_order_btn_area">
                                                <button class="btn" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasRight"
                                                    aria-controls="offcanvasRight">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border rounded p-2 mb-2">
                                    <div class="row">
                                        <div class="col-lg-9 col-md-9">
                                            <h6 class="mb-2 text-dark">Chudidar/Payjama</h6>
                                            <div>
                                                <span class="badge bg-light text-dark mb-2">Kids</span>
                                                <span class="badge bg-success text-dark mb-2">Cotton</span>
                                                <span class="badge bg-light text-dark mb-2">Silk</span>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4 mb-2">
                                                    <h6 class="mb-0">Dry Clean</h6>
                                                    <h6 class="mb-0 text-dark">110/pc </h6>
                                                </div>
                                                <div class="col-lg-4 mb-2">
                                                    <h6 class="mb-0">Steam Press</h6>
                                                    <h6 class="mb-0 text-dark"> 50/pc</h6>
                                                </div>
                                                <div class="col-lg-4 mb-2">
                                                    <h6 class="mb-0">Starching</h6>
                                                    <h6 class="mb-0 text-dark"> 50/pc</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 text-center ">
                                            <img src="{{ url('theam/Images/shirt/shirt.jpg') }}" alt="shirt "
                                                style="width: 50px;">
                                            <div class="Add_order_btn_area">
                                                <button class="btn">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                                    aria-labelledby="offcanvasRightLabel">
                                    <div class="offcanvas-header">
                                        <h5 id="offcanvasRightLabel">Curtain Panel</h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <div class="border-bottom mb-3">
                                            <h6 class="mb-2 text-dark">Chudidar/Payjama</h6>
                                            <div>
                                                <span class="badge bg-light text-dark mb-2">Small upto4sqft</span>
                                                <span class="badge bg-success text-dark mb-2">Large(upto 10sqft)</span>
                                                <span class="badge bg-light text-dark mb-2">Medium(upto8sqft)</span>
                                                <span class="badge bg-light text-dark mb-2">Full w Lining</span>
                                                <span class="badge bg-light text-dark mb-2">Half w Lining</span>
                                            </div>
                                        </div>
                                        <div class="border-bottom mb-3">
                                            <h6 class="mb-2 text-dark">Chudidar/Payjama</h6>
                                            <div>
                                                <span class="badge bg-light text-dark mb-2">Kids</span>
                                                <span class="badge bg-success text-dark mb-2">Cotton</span>
                                                <span class="badge bg-light text-dark mb-2">Silk</span>
                                                <span class="badge bg-light text-dark mb-2">Silk</span>
                                                <span class="badge bg-light text-dark mb-2">Silk</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="offcanvas-footer px-4 pb-2">
                                        <div class="input-group mb-3">
                                            <button class="input-group-text"><i class="fa-solid fa-plus"></i></button>
                                            <input type="text" class="form-control text-center" placeholder="Pc"
                                                aria-label="Amount (to the nearest dollar)">
                                            <button class="input-group-text"><i class="fa-solid fa-minus"></i></button>
                                        </div>
                                        <div class="text-center">

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                                    value="option1">
                                                <label class="form-check-label" for="inlineCheckbox1">Washing
                                                    Area</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                    value="option2">
                                                <label class="form-check-label" for="inlineCheckbox2">Pressing
                                                    Area</label>
                                            </div>

                                        </div>
                                        <div class="Add_order_btn_area">
                                            <button class="btn w-100">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>



                </div>
            </div>
        </div>
    </div>
    @endsection
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>
    <!-- jQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>



    <script>



        $(document).ready(function () {
            //form validation start here

            $('#yesButton').on('click', function() {
        $('#CreateOrder').modal('hide');
    });

            $("#number").on("input", function () {
                // Remove non-numeric characters from the input value
                var val = $(this).val().replace(/\D/g, '');
                // Update the input value with the sanitized value
                $(this).val(val);
                // Optionally, you can also limit the maximum length of the input
                var maxLength = 10;
                if (val.length > maxLength) {
                    $(this).val(val.slice(0, maxLength));
                }
            });
            $("#name").on("input", function () {
                var maxLength = 50;
                var val = $(this).val();
                if (val.length > maxLength) {
                    $(this).val(val.slice(0, maxLength));
                }
            });

            // Add custom validation method for minimum booking date
            $.validator.addMethod("minBookingDate", function (value, element) {
                var selectedDate = new Date(value);
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0); // Set hours to midnight for comparison
                return selectedDate >= currentDate;
            }, "Booking date cannot be earlier than today(current date)");

            $.validator.addMethod("minDeliveryDate", function (value, element) {
                var selectedDate = new Date(value);
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0); // Set hours to midnight for comparison
                return selectedDate >= currentDate;
            }, "Delivery date cannot be earlier than today");

            $("#addOrderFormValidation").validate({
                rules: {
                    client_name: {
                        required: true
                        , minlength: 2
                        , maxlength: 50
                        ,
                    }
                    , client_num: {
                        required: true
                        , number: true
                        , minlength: 10
                        , maxlength: 10
                        ,
                    }
                    , booking_date: {
                        required: true
                        , date: true
                        , minBookingDate: true
                        ,
                    }
                    , booking_time: {
                        required: true
                        , time: true
                        ,
                    }
                    , delivery_date: {
                        required: true
                        , date: true
                        , minDeliveryDate: true
                        ,
                    }
                    , delivery_time: {
                        required: true
                        , time: true
                        ,
                    }
                    ,
                }
                , messages: {
                    client_name: {
                        required: "Please enter client name"
                        , minlength: "Plese enter client name minimun 2 character"
                        , maxlength: "Please enter client name maximum 50 character"
                        ,
                    }
                    , client_num: {
                        required: "Please enter client mobile number"
                        , number: "Please enter a valid number"
                        , minlength: "mobile number must be minimum 10 digits"
                        , maxlength: "mobile number must be maxmum 10 digits"
                        ,
                    }
                    , booking_date: {
                        required: "Please enter order booking start date"
                        , date: "Please enter a valid time"
                        , minBookingDate: "Booking date cannot be earlier than today(current date)"
                        ,
                    }
                    , booking_time: {
                        required: "Please enter order booking start time"
                        ,
                    }
                    , delivery_date: {
                        required: "Please enter order delivery date"
                        , date: "Please enter a valid date"
                        , minDeliveryDate: "Delivery date cannot be earlier than today(current date)"
                        ,
                    }
                    , delivery_time: {
                        required: "Please enter order delivery time"
                        , date: "Please enter a valid time"
                        ,
                    }
                    ,
                }
                , submitHandler: function (form) {
                    console.log("Form submitted!");
                    $(form).submit();
                }
            });

            //order form validation end here
        });

    </script>
