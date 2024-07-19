@extends('backend.layouts.app')
@section('content')
    <style>
        .disabled {
            pointer-events: none;
        }

        .btn-danger {
            display: none;
            /* Ensure it's hidden by default */
        }

        .dev-hide {
            display: none !important;
        }
    </style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp Add_order_page_section">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="client_list_heading_area">
                                    <h4>
                                        {{-- @if (isset($order))
                                            Edit
                                        @else
                                            Add
                                        @endif Order --}}
                                        Add Order
                                    </h4>
                                </div>
                            </div>

                        </div>
                        {{-- @if (isset($order))
                            <form action="{{ route('order.update', $order->order_id) }}" method="POST"
                                enctype="multipart/form-data" id="orderForm">
                                @method('PUT')
                            @else --}}
                                <form action="{{ route('add.order') }}" method="POST" id="addOrderFormValidation"
                                    enctype="multipart/form-data">
                        {{-- @endif --}}
                        @csrf

                        <div class="row">
                            <div class="col-lg-6 col-md-6 mb-2">
                                <!-- Form Inputs for Client and Order Details -->
                                <div class="row">
                                    <!-- Client Number -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="client_num" class="form-label">Client Number</label>
                                            <input type="text" value="{{ old('mobile', $order->mobile ?? '') }}"
                                                id="number" name="client_num" class="form-control"
                                                placeholder="Client Number">
                                        </div>
                                    </div>
                                    <!-- Client Name -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="client_name" class="form-label">Client Name</label>
                                            <input type="text" id="client_name"
                                                value="{{ old('name', $order->name ?? '') }}" name="client_name"
                                                class="form-control" placeholder="Client Name">
                                        </div>
                                    </div>
                                    <!-- Booking Date -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="booking_date" class="form-label">Booking Date</label>
                                            <input type="date" id="booking_date"
                                                value="{{ old('order_date', $order->order_date ?? '') }}"
                                                name="booking_date" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Booking Time -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="booking_time" class="form-label">Booking Time</label>
                                            <input type="time" id="booking_time"
                                                value="{{ old('order_time', $order->order_time ?? '') }}"
                                                name="booking_time" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Delivery Date -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="delivery_date" class="form-label">Delivery Date</label>
                                            <input type="date" id="delivery_date"
                                                value="{{ old('delivery_date', $order->delivery_date ?? '') }}"
                                                name="delivery_date" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Delivery Time -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="delivery_time" class="form-label">Delivery Time</label>
                                            <input type="time" id="delivery_time"
                                                value="{{ old('delivery_time', $order->delivery_time ?? '') }}"
                                                name="delivery_time" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Discount Offer -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="discount" class="form-label">Discount Offer</label>
                                            <select name="discount" id="discount" class="form-select">
                                                <option value="0" selected>Select Discount Offer</option>
                                                @foreach ($discounts as $discount)
                                                    <option value="{{ $discount->amount }}">{{ $discount->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr />
                                    <!-- Gross Total Section -->
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3">
                                        <div class="row justify-content-between">
                                            <input type="hidden" name="gross_total" id="gross_total" />
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Gross Total:</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6 id="grossTotal">0.0</h6>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Discount Amount:</h6>
                                            </div>
                                            <div id="discountAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6>0.0</h6>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Express Amount:</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <div class="form-check form-switch float-end">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckDefault" name="express_charge" value="0"
                                                        onchange="toggleCheckbox()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <input type="hidden" name="total_qty" id="total_qty" />
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Total Count:</h6>
                                            </div>
                                            <div id="totalQty" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6>0 pc</h6>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                <h6>Total Amount:</h6>
                                            </div>
                                            <div id="totalAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                <h6>0</h6>
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
                                    <div class="modal fade" id="CreateOrder" tabindex="-1"
                                        aria-labelledby="CreateOrderLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <h5>Would you like to Create a New Order?</h5>
                                                    <button type="submit" class="btn btn-primary" id="yesButton"
                                                        data-bs-toggle="modal" data-bs-target="#yes">Yes</button>
                                                    <button type="button" class="btn btn-primary"
                                                        data-bs-dismiss="modal">No</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end -->

                                    <!-- Print Order Model -->
                                    {{-- <div class="modal fade" id="yes" tabindex="-1" aria-labelledby="yesLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <h5>Please Select from the Following Options</h5>
                                                        <a type="button" class="btn btn-success" id="sendWhatsAppMessage" href="{{ url('/send-wh-message') }}">
                                                            <i class="fab fa-whatsapp me-2"></i> Send On WhatsApp
                                                        </a>
                                                        <a type="button" class="btn btn-primary" href="{{ url('/admin/receipt/'. Session::get('orderId') ) }}">
                                                            <i class="fa-solid fa-file-invoice me-2"></i> Print Receipt
                                                        </a>

                                                        <button type="button" class="btn btn-success"><i class="fa-solid fa-tag me-2"></i> Print Tag</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    <!-- end -->
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 mb-2">
                                <!-- Product Items Section -->
                                <div class="client_list_area_hp">
                                    <div class="client_list_heading_area w-100">
                                        <div class="client_list_heading_search_area w-100">
                                            <i class="menu-icon tf-icons ti ti-search"></i>
                                            <input type="search" class="form-control" placeholder="Searching ...">
                                        </div>
                                    </div>
                                </div>

                                <input value="" name="categoryPriceItem" id="categoryPriceItem" height="500px"
                                    width="500">
                                <input value="" name="categoryItem" id="categoryItem">

                                <div class="row">
                                    <div id="productItemError" class="alert alert-danger" style="display: none;">
                                        Please add at least one product item.
                                    </div>
                                    @foreach ($groupedProductItems as $groupedProductItem)
                                        @php
                                            // dd($groupedProductItems);
                                            $productItem = $groupedProductItem['product_item'];
                                            $uniqueCategories = $groupedProductItem['unique_categories'];
                                        @endphp

                                        <div class="border rounded p-2 mb-2">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 mainopdiv">
                                                    <h6 class="mb-2 text-dark">{{ $productItem->name }}</h6>
                                                    <div class="categorysection">
                                                        @foreach ($uniqueCategories as $categoryName)
                                                            <span
                                                                onclick="categoryItem('{{ $categoryName }}','{{ $productItem->id }}', this)"
                                                                class="badge text-dark mb-2 subcategory bg-light">{{ $categoryName }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div class="oprationData disabled">
                                                        {!! $groupedProductItem['operationData'] !!}
                                                        {{-- @dd($groupedProductItem); --}}
                                                    </div>
                                                    {{-- @dd($groupedProductItem['operationData']); --}}
                                                </div>
                                                <div class="col-lg-3 col-md-3 text-center">
                                                    <img class="mb-2"
                                                        src="{{ url('images/categories_img/' . $productItem->image) }}"
                                                        alt="{{ $productItem->name }}" style="width: 50px;">
                                                    <div class="Add_order_btn_area">
                                                        <button type="button" id="addbtnpreview"
                                                            class="btn add-product-btn" data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight"
                                                            data-product-name="{{ $productItem->name }}"
                                                            data-images="{{ url('images/categories_img/' . $productItem->image) }}">Add</button>
                                                        <button class="btn btn-danger dev-hide"
                                                            id="productId{{ $productItem->id }}" type="button"
                                                            onclick="removeProductItem('{{ $productItem->id }}')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                                        aria-labelledby="offcanvasRightLabel">
                                        <div class="offcanvas-header">
                                            <h5 id="offcanvasRightLabel">Curtain Panel</h5>
                                            <button id="addOrderModel" type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body mainopdiv">
                                            <div class="border-bottom mb-3">
                                                <h6 class="mb-2 text-dark" id="categoryPreviewItemName">Chudidar/Payjama
                                                </h6>
                                            </div>
                                            <div class="border-bottom mb-3">
                                                <div>
                                                    <span id="categoryPreviewCategName"></span>
                                                </div>
                                            </div>
                                            <div class="border-bottom mb-3">
                                                <div>
                                                    <span id="categoryPreviewServiceName"
                                                        class="mb-2 oprationData"></span>
                                                </div>
                                            </div>

                                            <div class="offcanvas-footer px-4 pb-2">

                                                <div class="input-group mb-3">
                                                    <button type="button" class="input-group-text decrease"><i
                                                            class="fa-solid fa-minus"></i></button>
                                                    <input type="text" class="form-control text-center piece-count"
                                                        value="0" id="qtyPlsMns" name="qty" placeholder="Pc"
                                                        aria-label="Amount (to the nearest dollar)">
                                                    <button type="button" class="input-group-text increase"><i
                                                            class="fa-solid fa-plus"></i></button>
                                                </div>
                                                <div class="Add_order_btn_area">
                                                    <button type="button" id="addRightOdrbtn"
                                                        class="btn w-100">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Create Order Modal -->
                        <div class="modal fade" id="CreateOrder" tabindex="-1" aria-labelledby="CreateOrderLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <h5>Would you like to create a new order?</h5>
                                        <button type="submit" class="btn btn-primary" id="yesButton"
                                            data-bs-toggle="modal" data-bs-target="#yes">Yes</button>
                                        <button type="button" class="btn btn-primary"
                                            data-bs-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Print Order Modal -->
                        {{-- <div class="modal fade" id="yes" tabindex="-1" aria-labelledby="yesLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <h5>Please select from the following options:</h5>
                                            <a class="btn btn-primary" href="{{ url('/admin/receipt' . $order->id) }}">
                                                <i class="fa-solid fa-file-invoice me-2"></i> Print Receipt
                                            </a>
                                            <button type="button" class="btn btn-success">
                                                <i class="fa-solid fa-tag me-2"></i> Print Tag
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <!-- jQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let array = {
            data: []
        };
        // let newarray = [];

        // let AllobjectArray = [];
        document.addEventListener('DOMContentLoaded', (event) => {
            const bookingDateInput = document.getElementById('booking_date');
            if (!bookingDateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                bookingDateInput.value = today;
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
            const bookingTimeInput = document.getElementById('booking_time');
            const updateBookingTime = () => {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                bookingTimeInput.value = `${hours}:${minutes}`;
            };

            if (!bookingTimeInput.value) {
                updateBookingTime();
            }

            setInterval(updateBookingTime, 50000);
        });

        let selectedOperation = '';
        let selectedDiscount = 0;
        let items = {};

        $(document).on('click', '.add-product-btn', function() {
            const parentBorder = this.closest('.border');
            if (parentBorder) {
                const categorySection = parentBorder.querySelector('.categorysection');
                const operationSection = parentBorder.querySelector('.oprationData');
                if (categorySection) {
                    const categorySectionClone = categorySection.cloneNode(true);
                    const operationSectionClone = operationSection.cloneNode(true);

                    $('#categoryPreviewItemName').text($(this).data('product-name'));
                    $('#categoryPreviewCategName').html(categorySectionClone.outerHTML);
                    $('#categoryPreviewServiceName').html(removeClassFromHtml(operationSectionClone.outerHTML,
                        'disabled'));
                }
            }
        });

        const removeClassFromHtml = (htmlString, classNameToRemove) => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');
            doc.querySelectorAll('.' + classNameToRemove).forEach(element => element.classList.remove(
                classNameToRemove));
            return doc.body.innerHTML;
        };

        const categoryItem = (operationName, PrdId, control) => {
            $.ajax({
                url: "{{ url('get-service') }}",
                type: 'POST',
                data: {
                    'name': operationName,
                    'id': PrdId,
                    '_token': '{{ csrf_token() }}'
                },
                success: response => {
                    $(control).closest('.mainopdiv').find('.oprationData').html(response);
                    $(control).closest('.mainopdiv').find('.subcategory').removeClass('bg-success')
                        .addClass('bg-light');
                    $(control).addClass('bg-success').removeClass('bg-light');
                },
                error: (xhr, status, error) => console.error(xhr.responseText)
            });
        };

        function removeProductItem(itemId) {
            console.log('selectedPrice-rm', itemId);
            let rBtn = $('#productId' + itemId);
            if (rBtn) {
                rBtn.addClass('dev-hide');
            }
            if (items[itemId]) {
                delete items[itemId];
                updateGrossTotal();
            } else {
                alert('Item does not exist.');
            }
        }

        //for store data in a varaiable
        // const categoryPriceItemData = JSON.parse($('#categoryPriceItem').val());
        //end store

        let currentlySelectedService = null;
        const categoryPriceItem = (categoryPriceItemV, control) => {
            console.log("categoryPriceItemV ==>", categoryPriceItemV);
            console.log(`control`, control);

            // Remove highlight from previously selected service
            if (currentlySelectedService) {
                currentlySelectedService.classList.remove('bg-success', 'text-white');
                currentlySelectedService.classList.add('bg-light', 'text-dark');
            }

            // Highlight the newly selected service
            $(control).addClass('bg-success text-white').removeClass('bg-light text-dark');

            // Update the currently selected service
            currentlySelectedService = control;


            // $('#categoryPriceItem').val($('#categoryPriceItem').val() + categoryPriceItemV); 
            // let data = categoryPriceItemV;
            // let productItem = {
            //     name: data[0],
            //     id: data[1], // Assuming data[1] is the product ID
            //     // Add other properties if needed
            // };
            // if (newarray || newarray != null || newarray != undefined || newarray.length != 0)
            //     newarray.push(productItem);
            // console.log("my array", newarray);
            // document.getElementById('categoryPriceItem').value = '';


            // newarray = JSON.stringify(newarray);

            // $('#categoryPriceItem').empty();
            // $('#categoryPriceItem').val(JSON.stringify(array)); 
            // updateCategoryPriceItemInput();
            // newarray = JSON.parse(newarray);
            selectedOperation = categoryPriceItemV;
            const itemCatId = selectedOperation;
            $('#categoryItem').val(itemCatId[3]);
            console.log("newjjjj", itemCatId.price);
            console.log("newjjjj", itemCatId);
        };

        //         function categoryPriceItem(itemAttr, element) {
        //     // Assuming you need to store the selected operation globally
        //     window.selectedOperation = itemAttr;
        //     console.log('Selected Operation:', itemAttr);
        // }

        $(document).ready(() => {
            $('.increase').click(function() {
                const input = $(this).closest('.input-group').find('.piece-count');
                const value = parseInt(input.val()) + 1;
                input.val(value);
            });

            $('.decrease').click(function() {
                const input = $(this).closest('.input-group').find('.piece-count');
                const value = Math.max(parseInt(input.val()) - 1, 0);
                input.val(value);
            });

            $('#yesButton').on('click', () => $('#CreateOrder').modal('hide'));
            // alert("hjvefhgewvfwehg");
            $('#number').on("input", function() {
                const val = $(this).val().replace(/\D/g, '');
                $(this).val(val.slice(0, 10));
            });

            $('#name').on("input", function() {
                const val = $(this).val();
                $(this).val(val.slice(0, 50));
            });


            $.validator.addMethod("minBookingDate", function(value) {
                const selectedDate = new Date(value);
                const currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);
                return selectedDate >= currentDate;
            }, "Booking date cannot be earlier than today.");

            $.validator.addMethod("minDeliveryDate", function(value) {
                const selectedDate = new Date(value);
                const currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);
                return selectedDate >= currentDate;
            }, "Delivery date cannot be earlier than today.");

            $("#addOrderFormValidation").validate({
                rules: {
                    client_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    client_num: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    booking_date: {
                        required: true,
                        date: true,
                        minBookingDate: true
                    },
                    booking_time: {
                        required: true
                    },
                    delivery_date: {
                        required: true,
                        date: true,
                        minDeliveryDate: true
                    },
                    delivery_time: {
                        required: true
                    },
                    discount: {
                        required: true,
                        min: 1 // Ensures a value other than 0 is selected
                    }
                },
                messages: {
                    client_name: {
                        required: "Please enter client name",
                        minlength: "Please enter at least 2 characters",
                        maxlength: "Please enter no more than 50 characters"
                    },
                    client_num: {
                        required: "Please enter client mobile number",
                        number: "Please enter a valid number",
                        minlength: "Mobile number must be 10 digits",
                        maxlength: "Mobile number must be 10 digits"
                    },
                    booking_date: {
                        required: "Please enter booking date",
                        date: "Please enter a valid date",
                        minBookingDate: "Booking date cannot be earlier than today"
                    },
                    booking_time: {
                        required: "Please enter booking time"
                    },
                    delivery_date: {
                        required: "Please enter delivery date",
                        date: "Please enter a valid date",
                        minDeliveryDate: "Delivery date cannot be earlier than today"
                    },
                    delivery_time: {
                        required: "Please enter delivery time",
                        date: "Please enter a valid time"
                    },
                    discount: {
                        required: "Please select a discount offer",
                        min: "Please select a valid discount offer"
                    }
                },
                submitHandler: function(form) {
                    if (Object.keys(items).length === 0) {
                        $('#productItemError').show();
                        $('html, body').animate({
                            scrollTop: $("#productItemError").offset().top
                        }, 500);
                        return false;
                    } else {
                        $('#productItemError').hide();
                    }
                    console.log('handler>>>', items);
                    // Append item details to form before submission
                    Object.keys(items).forEach(key => {

                        const item = items[key];
                        if (item.qty > 0) {
                            $('#orderForm, #addOrderFormValidation').append(
                                `<input type="hidden" name="items[${item.productName}][${item.category}][${item.service}][qty]" value="${item.qty}">`
                            );
                            $('#orderForm, #addOrderFormValidation').append(
                                `<input type="hidden" name="items[${item.productName}][${item.category}][${item.service}][unit_price]" value="${item.unitPrice}">`
                            );
                        }
                    });
                    // $(this).append('<input type="hidden" name="categoryPriceItemData" value="' + JSON.stringify(categoryPriceItemData) + '">');
                    form.submit();
                }
            });

            $('#number').on("keyup", function() {
                const clientNum = $(this).val();
                if (clientNum.length === 10) {
                    $.ajax({
                        url: "/fetch-client-name",
                        method: "GET",
                        data: {
                            client_num: clientNum
                        },
                        success: response => {
                            if (response.success) {
                                $("#client_name").val(response.client_name);
                            } else {
                                console.error(response.message);
                            }
                        },
                        error: (xhr, status, error) => console.error("Error fetching client name:",
                            error)
                    });
                }
            });

            $('#discount').on('change', function() {
                selectedDiscount = parseFloat(this.value);
                updateGrossTotal();
            });

            $('#addRightOdrbtn').on('click', function() {
                const qty = parseInt($('#qtyPlsMns').val());
                const itemData = selectedOperation;
                // const productName = $.trim($('#categoryPreviewItemName').text());
                const productName = itemData.pid;
                const category = itemData.item_cat_id;
                // const service = $.trim($('#categoryPreviewServiceName').text());
                // const service = itemData.op_name;
                const service = `${itemData.op_id}, ${itemData.price}`;
                const unitPrice = parseFloat(selectedOperation[2]);
                console.log("for category", itemData[3]);
                console.log("for service", service);
                console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>productName", productName, )
                console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>category", category)
                console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>service", service)
                console.log('selectedPrice', selectedOperation);
                console.log('itemdata', itemData);
                console.log('itemdata', itemData.op_name);
                console.log('categoryData', itemData.item_cat_id);
                console.log('selectedPrice IDM', {
                    productName: itemData[4],
                    category: itemData[3],
                    service: itemData[0],
                    qty: qty,
                    unitPrice
                });
                if (qty > 0) {
                    const itemId = `${itemData[4]}`;
                    // const rBtn = $('#productId' + itemId);

                    // if (rBtn) {
                    //     rBtn.removeClass('dev-hide');
                    // }
                    // const itemId = `${productName}-${category}-${service}`;
                    if (!items[itemId]) {
                        items[itemId] = {
                            productName: itemData[4],
                            // category: itemData[3],
                            category: category,
                            service: service,
                            qty: qty,
                            unitPrice
                        };
                    }
                    console.log("new servicejjjj", service);
                    items[itemId].qty = qty;

                    addToDataArray(productName, category, service, qty);
                    $('#categoryPriceItem').empty();
                    $('#categoryPriceItem').val(JSON.stringify(array));
                    $('#productItemError').hide();
                    updateGrossTotal();
                }

                document.getElementById('addOrderModel').click();
                $('#qtyPlsMns').val(0); // Reset quantity input
            });
        });

        function addToDataArray(productName, category, service, qty) {
            if (!Array.isArray(productName)) productName = [productName];
            if (!Array.isArray(category)) category = [category];
            if (!Array.isArray(service)) service = [service];
            if (!Array.isArray(qty)) qty = [qty];

            productName.forEach((name, index) => {
                const categoryValue = category[index];
                const serviceValue = service[index];
                const qtyValue = qty[index];

                console.log(">>>>>>>>>>", name, "<>", categoryValue, ">>>><<<<", serviceValue, "<<<<<<>>>",
                    qtyValue);

                let product = array.data.find(item => item.name === name);
                if (!product) {
                    product = {
                        name: name,
                        CatItems: [],
                        // a2: []
                    };
                    array.data.push(product);
                }

                // For a1 array
                let categoryObjA1 = product.CatItems.find(item => item.name === categoryValue);
                if (!categoryObjA1) {
                    categoryObjA1 = {
                        // name: categoryValue.trim(),
                        name: categoryValue,
                        Operations: [],
                        // b2: []
                    };
                    product.CatItems.push(categoryObjA1);
                }

                // For a2 array
                // let categoryObjA2 = product.a2.find(item => item.name === categoryValue);
                // if (!categoryObjA2) {
                //     categoryObjA2 = {
                //         name: categoryValue.trim(),
                //         b1: [],
                //         b2: []
                //     };
                //     product.a2.push(categoryObjA2);
                // }

                // Push or update serviceObj for a1 -> b1
                let serviceObjA1B1 = categoryObjA1.Operations.find(item => item.type === serviceValue);
                if (!serviceObjA1B1) {
                    serviceObjA1B1 = {
                        type: serviceValue,
                        qt: qtyValue
                    };
                    categoryObjA1.Operations.push(serviceObjA1B1);
                } else {
                    serviceObjA1B1.qt = qtyValue;
                }

                // Push or update serviceObj for a1 -> b2
                // let serviceObjA1B2 = categoryObjA1.b2.find(item => item.type === serviceValue);
                // if (!serviceObjA1B2) {
                //     serviceObjA1B2 = {
                //         type: serviceValue,
                //         qt: qtyValue
                //     };
                //     categoryObjA1.b2.push(serviceObjA1B2);
                // } else {
                //     serviceObjA1B2.qt = qtyValue;
                // }

                // Push or update serviceObj for a2 -> b1
                // let serviceObjA2B1 = categoryObjA2.b1.find(item => item.type === serviceValue);
                // if (!serviceObjA2B1) {
                //     serviceObjA2B1 = {
                //         type: serviceValue,
                //         qt: qtyValue
                //     };
                //     categoryObjA2.b1.push(serviceObjA2B1);
                // } else {
                //     serviceObjA2B1.qt = qtyValue;
                // }

                // Push or update serviceObj for a2 -> b2
                // let serviceObjA2B2 = categoryObjA2.b2.find(item => item.type === serviceValue);
                // if (!serviceObjA2B2) {
                //     serviceObjA2B2 = {
                //         type: serviceValue,
                //         qt: qtyValue
                //     };
                //     categoryObjA2.b2.push(serviceObjA2B2);
                // } else {
                //     serviceObjA2B2.qt = qtyValue;
                // }

                console.log("goodarray", array);
            });
        }

        function toggleCheckbox() {
            const checkbox = document.getElementById("flexSwitchCheckDefault");
            checkbox.value = checkbox.checked ? 1 : 0;
        }

        function updateGrossTotal() {
            let grossTotal = 0;
            let totalQty = 0;
            console.log(items)
            var amount = selectedOperation;
            console.log("newdata" + parseInt(amount[2]));
            // console.log("newdata" +  amount.price);

            var newqty = parseInt($('#qtyPlsMns').val());
            console.log("newqty" + newqty);
            totalQty = newqty;
            // var newamt=parseInt(amount);
            var newamt = parseInt(amount[2]);
            console.log("newamt " + newamt);
            grossTotal = amount.price * totalQty;
            console.log(grossTotal + " new gross total");

            var gt = document.getElementById('gross_total').value;
            gt = (gt) ? parseInt(gt) + grossTotal : grossTotal;
            gt = isNaN(gt) ? 0 : gt;
            document.getElementById('grossTotal').innerText = gt;
            document.getElementById('gross_total').value = gt;
            //
            var qty = document.getElementById('total_qty').value;
            qty = (qty) ? parseInt(qty) + totalQty : totalQty;
            qty = isNaN(qty) ? 0 : qty;
            document.getElementById('totalQty').innerText = qty + ' pc';
            document.getElementById('total_qty').value = qty;

            var discountAmount = (gt * selectedDiscount) / 100;
            console.log(discountAmount);
            var totalAmount = gt - discountAmount;

            document.getElementById('discountAmount').innerText = discountAmount.toFixed(2);
            document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
        }
    </script>

    {{-- //controller code --}}
    {{-- public function addOrder(Request $request)
    {
        try {
            $data = $request->all();
            // dd($data);
            $categoryPriceItem = json_decode($request->input('categoryPriceItem'), true);
            //new code 
            $operationsArray = []; // Initialize an empty array to store all operations

            foreach ($categoryPriceItem['data'] as $product) {
                $productName = $product['name'];

                foreach ($product['CatItems'] as $categoryItem) {
                    $categoryId = $categoryItem['name'];

                    foreach ($categoryItem['Operations'] as $operation) {
                        $operationType = $operation['type'];
                        $operationParts = explode(', ', $operationType);

                        // Assuming the first part is the operation name and the second part is the price
                        $operationName = $operationParts[0]; // Operation name
                        $price = $operationParts[1]; // Price

                        // Create an array to store both parts
                        $OperationArray = [
                            'name' => $operationName,
                            'price' => $price,
                            'quantity' => $operation['qt'], // Include quantity
                            'productName' => $productName, // Include product name
                            'categoryId' => $categoryId // Include category ID
                        ];

                        // Push the operation details into the $operationsArray
                        $operationsArray[] = $OperationArray;
                    }
                }
            }

            $validatedData = $request->validate([
                'client_num' => 'required|numeric',
                'client_name' => 'required|min:2|max:20',
                'booking_date' => 'required|date',
                'booking_time' => 'required|date_format:H:i',
                'delivery_date' => 'required|date',
                'delivery_time' => 'required|date_format:H:i',
                'discount' => 'required',
                'total_qty' => 'required',
                // 'data' => 'required|json',
            ]);
            // dd($validatedData,$request);

            // Retrieve client or create new one
            $client = DB::table('users')->where('mobile', $validatedData['client_num'])->first();
            if ($client) {
                $user_id = $client->id;
            } else {
                $user = User::create([
                    'name' => $validatedData['client_name'],
                    'mobile' => $validatedData['client_num'],
                    'role_id' => 2
                ]);
                $user_id = $user->id;
            }

            // Determine discount ID
            $discountId = $this->getDiscountId($request->discount);

            // Calculate total price with discount and optional express charge
            list($totalPriceDis, $totalDiscount) = $this->calculateTotalPrice($request);

            // Create the order and save in db
            $order = Order::create([
                'invoice_number' => '',
                'user_id' => $user_id,
                'order_date' => $validatedData['booking_date'],
                'order_time' => $validatedData['booking_time'],
                'delivery_date' => $validatedData['delivery_date'],
                'delivery_time' => $validatedData['delivery_time'],
                'discount_id' => $discountId,
                'service_id' => null, // Will be assigned later
                'status' => 'pending',
                'total_qty' => $validatedData['total_qty'],
                'total_price' => $totalPriceDis,
            ]);

            // Generate and save invoice number
            if ($order) {
                $orderId = $order->id;
                $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($orderId, 3, '0', STR_PAD_LEFT);
                $order->invoice_number = $invoiceNumber;
                $order->save();
            }
            // Get the count of operations
            $operationsCount = count($operationsArray);

            // Insert order items
            for ($i = 0; $i < $operationsCount; $i++) {
                $item = $operationsArray[$i];
                // Create and save the order item for each unique operation
                $order->orderItems()->create([
                    'order_id' => $order->id,
                    'product_item_id' => $item['productName'],
                    'product_category_id' => $item['categoryId'],
                    'operation_id' => $item['name'],
                    'quantity' => $item['quantity'],
                    'operation_price' => $item['price'],
                    'price' => $item['quantity'] * $item['price'],
                    'status' => 'pending'
                ]);
            }


            // Create payment details
            PaymentDetail::create([
                'order_id' => $order->id,
                'total_quantity' => $validatedData['total_qty'],
                'total_amount' => $totalPriceDis,
                'discount_amount' => $totalDiscount,
                'service_charge' => $request->express_charge == '1' ? ($totalPriceDis * 50) / 100 : 0,
                'paid_amount' => 0, // Initially no amount paid
                'status' => 'Due',
                'payment_type' => null // Payment type is null initially
            ]);
            // dd($pdet);

            return redirect()->route('viewOrder');
        } catch (Throwable $throwable) {
            return back()->withErrors($throwable->getMessage())->withInput();
        }
    } --}}


    {{-- index code --}}
    {{-- public function index()
    {
        $productItems = ProductItem::with('categories')->get();
        $groupedProductItems = [];
        foreach ($productItems as $productItem) {
            $uniqueCategories = [];
            $uniqueCategoriesid = [];
            foreach ($productItem->categories as $category) {
                //dd($category);
                $categoryName = $category->name;
                if (!in_array($categoryName, $uniqueCategories)) {
                    $uniqueCategories[] = $categoryName;
                }
            }
            $groupedProductItems[] = [
                'product_item' => $productItem,
                'unique_categories' => $uniqueCategories,
                'operationData' => $this->getOperationData($productItem->id, $uniqueCategories[0] ?? ''),
            ];
            // dd($groupedProductItems);
        }
        $discounts = Discount::all();
        return view('admin.EditOrder', compact('groupedProductItems', 'discounts'));
    } --}}
