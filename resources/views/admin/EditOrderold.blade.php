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

        .check-error {
            color: #6f6b7d !important;
        }

        .active-alphabet {
            background-color: #C6F7D0;
            /* green background */
            border: 1px solid #3E8E41;
            /* green border */
            border-radius: 5px;
            padding: 5px;
        }

        .alphabetical-index {
            display: flex;
        }

        .alphabetical-index a {
            width: 30px;
            height: 30px;
            display: flex;
            /* background: red; */
            align-content: center;
            align-items: center;
            justify-content: center;
            margin-right: 5px;
            border-radius: 4px;
            margin-bottom: 5px
        }

        .alphabetical-index a:hover {

            background: #7367f01f;

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

                            <div class="row ">
                                <div class="col-lg-12 col-md-12 mb-2">
                                    <!-- Form Inputs for Client and Order Details -->
                                    <div class="row">
                                        <!-- Client Number -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_num" class="form-label">Client Number</label>
                                                <input type="text" value="{{ old('mobile', $order->mobile ?? '') }}"
                                                    id="number" name="client_num" class="form-control"
                                                    placeholder="Client Number">
                                            </div>
                                        </div>
                                        <!-- Client Name -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_name" class="form-label">Client Name</label>
                                                <input type="text" id="client_name"
                                                    value="{{ old('name', $order->name ?? '') }}" name="client_name"
                                                    class="form-control" placeholder="Client Name">
                                            </div>
                                        </div>
                                        <!-- Booking Date -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="booking_date" class="form-label">Booking Date</label>
                                                <input type="date" id="booking_date"
                                                    value="{{ old('order_date', $order->order_date ?? '') }}"
                                                    name="booking_date" class="form-control">
                                            </div>
                                        </div>
                                        <!-- Booking Time -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="booking_time" class="form-label">Booking Time</label>
                                                <input type="time" id="booking_time"
                                                    value="{{ old('order_time', $order->order_time ?? '') }}"
                                                    name="booking_time" class="form-control">
                                            </div>
                                        </div>
                                        <!-- Delivery Date -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                                <input type="date" id="delivery_date"
                                                    value="{{ old('delivery_date', $order->delivery_date ?? '') }}"
                                                    name="delivery_date" class="form-control">
                                            </div>
                                        </div>
                                        <!-- Delivery Time -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="delivery_time" class="form-label">Delivery Time</label>
                                                <div class="input-group">
                                                    <select id="delivery_time" name="delivery_time" class="form-control">
                                                        @foreach ($timeSlots['time_ranges'] as $time)
                                                            <option value="{{ $time['start'] }}" {{ old('delivery_time', $order->delivery_time ?? '') == $time['start'] ? 'selected' : '' }}>
                                                                {{ $time['range'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Discount Offer -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="discount" class="form-label">Discount Offer</label>
                                                <select name="discount" id="discount" class="form-select check-error"
                                                    required>
                                                    <option value="0" selected>Select Discount Offer</option>
                                                    @foreach ($discounts as $discount)
                                                        <option value="{{ $discount->amount }}">{{ $discount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <span class="error-message text-danger" id="discountError"
                                                    style="display:none;">Please select a discount offer.</span>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="col-lg-12 col-md-12 mb-2">
                                            <!-- Product Items Section -->
                                            <!-- <div class="client_list_area_hp">
                                                            <div class="client_list_heading_area w-100">
                                                                <div class="client_list_heading_search_area w-100">
                                                                    <i class="menu-icon tf-icons ti ti-search"></i>
                                                                    <input type="search" class="form-control"
                                                                        placeholder="Searching ...">
                                                                </div>
                                                            </div>
                                                        </div> -->
                                            <div id="productItemError" class="text-danger" style="display:none;">Please
                                                select at least one item.</div>

                                            <input type="hidden" value="" name="categoryPriceItem"
                                                id="categoryPriceItem">
                                            <input type="hidden" value="" id="AddorderItemsData"
                                                name="order_items_add_data">
                                            <input type="hidden" value="" name="categoryItem" id="categoryItem">

                                            <div class="row">
                                                <div class="col-12 addsec mb-3">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12">
                                                            <div class="alphabetical-index">
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="A">A</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="B">B</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="C">C</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="D">D</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="E">E</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="F">F</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="G">G</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="H">H</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="I">I</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="J">J</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="K">K</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="L">L</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="M">M</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="N">N</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="O">O</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="P">P</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="Q">Q</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="R">R</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="S">S</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="T">T</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="U">U</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="V">V</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="W">W</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="X">X</a>
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="Y">Y</a>
                                                                <!-- Add all alphabets here -->
                                                                <a href="#" class="alphabet"
                                                                    data-alphabet="Z">Z</a>
                                                            </div>
                                                            Select Order Item
                                                            <span class="category-error text-danger"
                                                                style="display:none;">Item name is required and must be
                                                                less than 20 characters.</span>
                                                        </div>
                                                        <div class="table-responsive mt-2">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Select Item</th>
                                                                        <th>Select Type</th>
                                                                        <th>Select Service</th>
                                                                        <th>Quantity</th>
                                                                        <th>Price</th> 
                                                                        <th>Comment</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="addtbody">
                                                                    <tr>
                                                                        <td>
                                                                            {{-- @dd($groupedProductItems); --}}
                                                                            {{-- <select name="category[]"
                                                                                class="form-select cat-select check-error">
                                                                                <option value="" selected disabled>
                                                                                    Select Item</option>
                                                                                @foreach ($groupedProductItems as $groupedItem)
                                                                                    {{-- @dd($groupedProductItems); --}}
                                                                            {{-- <option
                                                                                        value="{{ $groupedItem['product_item']['id'] }}">
                                                                                        {{ $groupedItem['product_item']['name'] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select> --}}
                                                                            {{-- <select name="category[]" class="form-select cat-select check-error">
                                                                                <option value="" selected disabled>Select Item</option>
                                                                                @foreach ($groupedProductItems as $groupedItem)
                                                                                    <option value="{{ $groupedItem['product_item']['id'] }}" data-alphabet="{{ strtoupper(substr($groupedItem['product_item']['name'], 0, 1)) }}">{{ $groupedItem['product_item']['name'] }}</option>
                                                                                @endforeach
                                                                            </select> --}}
                                                                            <select name="category[]"
                                                                                class="form-select cat-select check-error">
                                                                                <option value="" selected disabled>
                                                                                    Select Item</option>
                                                                                @foreach (collect($groupedProductItems)->sortBy(function ($item) {
            return $item['product_item']['name'];
        }) as $groupedItem)
                                                                                    <option
                                                                                        value="{{ $groupedItem['product_item']['id'] }}"
                                                                                        data-alphabet="{{ strtoupper(substr($groupedItem['product_item']['name'], 0, 1)) }}">
                                                                                        {{ $groupedItem['product_item']['name'] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        {{--
                                                                        @dd($groupedProductItems['unique_categories']);
                                                                        --}}
                                                                        {{-- @dd($groupedProductItems['product_item']);
                                                                        --}}
                                                                        <td>
                                                                            <select name="type[]"
                                                                                class="form-select type-select check-error">
                                                                                <option value="" selected disabled>
                                                                                    Select Item Type</option>
                                                                                {{-- @dd($groupedProductItems); --}}
                                                                                @foreach ($groupedProductItems as $groupedItem)
                                                                                    @foreach ($groupedItem['unique_categories'] as $category)
                                                                                        <option
                                                                                            value="{{ $category }}">
                                                                                            {{ $category }}</option>
                                                                                    @endforeach
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select name="service[]"
                                                                                class="form-select service-select check-error">
                                                                                <option value="" selected disabled>
                                                                                    Select Service</option>
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{ $service->id }}">
                                                                                        {{ $service->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="service-error text-danger"
                                                                                style="display:none;">Item name is
                                                                                required and must be less than 20
                                                                                characters.</span>
                                                                        </td>
                                                                        <td style="min-width: 100px !important;">
                                                                            <input type="number" name="quantity[]"
                                                                                class="form-control quantity"
                                                                                placeholder="Quantity">
                                                                        </td>
                                                                        <td style="min-width: 100px !important;">
                                                                            <input type="text" name="price[]"
                                                                                class="form-control price"
                                                                                placeholder="Price" readonly>
                                                                            <span class="price-error text-danger"
                                                                                style="display:none;">Price is required
                                                                                and must be a number.</span>
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" name="nltype[]"
                                                                                class="form-control nltype"
                                                                                placeholder="Normal/Laundry" readonly> 
                                                                                <input type="hidden" name="comment[]"
                                                                                class="form-control comment" id="comment"
                                                                                placeholder="Enter comment here"> 
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <button type="button"
                                                                                    class="btn p-0 me-2 addnewrow"><i
                                                                                        class="fa-solid fa-circle-plus fs-3"></i></button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div id="append_select_column"></div>
                                            </div>
                                        </div>
                                        <hr />
                                        <!-- Gross Total Section -->


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

                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-12 mb-3">
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
                                                <div id="discountAmount"
                                                    class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
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
                                                            id="flexSwitchCheckDefault" name="express_charge"
                                                            value="1" onchange="toggleCheckbox()">
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
                                            <div class="col-md-12">
                                                <div class="Add_order_btn_area text-end">
                                                    <button class="btn w-100" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#CreateOrder">Save</button>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const discountSelect = document.getElementById('discount');
            discountSelect.addEventListener('change', captureTableData);
            // Function to initialize event listeners and data capture
            function initializeOrderPage() {
                const productItems = @json($productItems); // Convert PHP array to JavaScript array
                const services = @json($services); // Convert PHP array to JavaScript array

                // Function to filter and populate type options based on selected item
                function filterOptions(row) {
                    let selectedItemId = row.find('.cat-select').val();
                    console.log(selectedItemId);
                    let typeSelect = row.find('.type-select');
                    let serviceSelect = row.find('.service-select');

                    typeSelect.empty().append('<option value="" selected disabled>Select Type</option>');
                    serviceSelect.empty().append('<option value="" selected disabled>Select Service</option>');

                    if (selectedItemId) {
                        let selectedProduct = productItems.find(product => product.id == selectedItemId);
                        console.log(selectedProduct);

                        if (selectedProduct) {
                            selectedProduct.categories.forEach(category => {
                                typeSelect.append(
                                    `<option value="${category.id}">${category.name}</option>`);
                            });
                        }
                    }
                }

                // Function to apply event listeners to each row
                function applyEventListeners(row) {
                    row.find('.cat-select').on('change', function() {

                        let catselect = $(this).val();
                        console.log("catselect>>>>>>>>",catselect);
                        let nltype = row.find('.nltype'); 
                        let commentField = row.find('.comment'); 
                        let commentHeader = $('#commentheader');
                        if (catselect == 16 || catselect == 17) {
                            nltype.val("Laundry");
                            commentField.attr("type", "text");
                            commentHeader.css("display", "");
                            // commentField.attr("type", "hidden");
                        }else{
                            nltype.val("Normal");
                            commentHeader.css("display", "none");
                            commentField.attr("type", "hidden");
                        }
                        filterOptions(row);
                        captureTableData();
                    });

                    row.find('.type-select').on('change', function() {
                        let item = row.find('.cat-select').val();
                        let type = row.find('.type-select').val();

                        if (item && type) {
                            $.ajax({
                                url: '/admin/get-services',
                                method: 'GET',
                                data: {
                                    item: item,
                                    type: type
                                },
                                success: function(response) {
                                    console.log(
                                        response); // Log the response to see its structure
                                    let serviceSelect = row.find('.service-select');
                                    serviceSelect.empty().append(
                                        '<option value="" selected disabled>Select Service</option>'
                                    );

                                    if (Array.isArray(response.services)) {
                                        response.services.forEach(service => {
                                            serviceSelect.append(
                                                `<option value="${service.id}">${service.name}</option>`
                                            );
                                        });
                                        if (response.services.length > 0) {
                                            // Automatically select the first service
                                            serviceSelect.val(response.services[0].id).change();
                                        }
                                    } else if (response.services) {
                                        serviceSelect.append(
                                            `<option value="${response.services.id}">${response.services.name}</option>`
                                        );
                                        // Automatically select the single service
                                        serviceSelect.val(response.services.id).change();
                                    } else {
                                        console.error('Invalid response format for services:',
                                            response.services);
                                    }
                                    captureTableData();
                                },
                                error: function(xhr, status, error) {
                                    console.error('Failed to fetch services:', error);
                                }
                            });
                        }
                    });
                    row.find('.service-select').on('change', function() {
                        let item = row.find('.cat-select').val();
                        let type = row.find('.type-select').val();
                        let service = row.find('.service-select').val();

                        if (item && type && service) {
                            $.ajax({
                                url: '/admin/get-price',
                                method: 'GET',
                                data: {
                                    item: item,
                                    type: type,
                                    service: service
                                },
                                success: function(response) {
                                    row.find('.price').val(response.price);
                                    captureTableData();
                                },
                                error: function(xhr, status, error) {
                                    console.error('Failed to fetch price:', error);
                                    row.find('.price').val('');
                                }
                            });
                        } else {
                            row.find('.price').val('');
                        }
                    });

                    row.find('.service-select').on('change', function() {
                        captureTableData();
                    });

                    row.find('input[name="quantity[]"]').on('input', function() {
                        captureTableData();
                    });
                     
                    row.find('input[name="comment[]"]').on('input', function() {
                        captureTableData();
                    });
                }

                // Initialize event listeners and data capture for existing rows
                $('.addtbody tr').each(function() {
                    filterOptions($(this));
                    applyEventListeners($(this));
                    captureTableData();
                });

                // Event listener for adding new row
                $(document).on('click', '.addnewrow', function() {
                    let row_id = $(this).closest('tbody');
                    let new_count = row_id.find('tr').length;

                    let dynamicRowHTML = `
                        <tr>
                            <td>
                                <select name="category[]" class="form-select cat-select check-error">
                                    <option value="" selected disabled>Select Item</option>
                                   @foreach (collect($groupedProductItems)->sortBy(function ($item) {
                                        return $item['product_item']['name'];
                                    }) as $groupedItem)
                                                                                    <option
                                                                                        value="{{ $groupedItem['product_item']['id'] }}"
                                                                                        data-alphabet="{{ strtoupper(substr($groupedItem['product_item']['name'], 0, 1)) }}">
                                                                                        {{ $groupedItem['product_item']['name'] }}
                                                                                    </option>
                                                                                @endforeach
                                </select>
                                 <span class="error-message text-danger"></span>
                            </td>
                            <td>
                                <select name="type[]" class="form-select type-select check-error">
                                    <option value="" selected disabled>Select Type</option>
                                </select>
                                 <span class="error-message text-danger"></span>
                            </td>
                            <td>
                                <select name="service[]" class="form-select service-select check-error">
                                    <option value="" selected disabled>Select Service</option>
                                </select>
                                  <span class="error-message text-danger"></span>
                            </td>
                            <td style="min-width: 100px !important;">
                                <input type="number" name="quantity[]" class="form-control" placeholder="Quantity" value="">
                                 <span class="error-message text-danger"></span>
                            </td>
                            <td style="min-width: 100px !important;"><input type="text" name="price[]" class="form-control price" placeholder="Price" readonly> <span class="error-message text-danger"></span></td>
                             <td>
                                                                            <input type="hidden" name="nltype[]"
                                                                                class="form-control nltype"
                                                                                placeholder="Normal/Laundry" readonly> 
                                                                                <input  name="comment[]"
                                                                                class="form-control comment"
                                                                                placeholder="Enter comment here"> 
                                                                        </td>
                            <td>
                                <div class="d-flex">
                                    <button type="button" class="btn p-0 me-2 addnewrow"><i class="fa-solid fa-circle-plus fs-3"></i></button>
                                    <button type="button" class="btn p-0 me-2 remove"><i class="fa-solid fa-circle-minus text-danger fs-3"></i></button>
                                </div>
                            </td>
                        </tr>`;

                    row_id.append(dynamicRowHTML);

                    let newRow = row_id.find('tr').last();
                    // console.log(newRow);
                    applyEventListeners(newRow);
                    filterOptions(newRow);
                    captureTableData();
                    applyValidation(newRow);
                    // $("#addOrderFormValidation").validate().element(newRow.find("select[name='category[]']"));
                    // $("#addOrderFormValidation").validate().element(newRow.find("select[name='type[]']"));
                    // $("#addOrderFormValidation").validate().element(newRow.find("select[name='service[]']"));
                    // $("#addOrderFormValidation").validate().element(newRow.find("input[name='quantity[]']"));
                });

                // Event listener for removing row
                $(document).on('click', '.remove', function() {
                    $(this).closest('tr').remove();
                    captureTableData();
                });

                function applyValidation(row) {
                    row.find('select[name="category[]"]').rules("add", {
                        required: true,
                        messages: {
                            required: "Please select an item"
                        }
                    });
                    row.find('select[name="type[]"]').rules("add", {
                        required: true,
                        messages: {
                            required: "Please select an item type"
                        }
                    });
                    row.find('select[name="service[]"]').rules("add", {
                        required: true,
                        messages: {
                            required: "Please select a service"
                        }
                    });
                    row.find('input[name="quantity[]"]').rules("add", {
                        required: true,
                        digits: true,
                        maxlength: 10,
                        min: 1,
                        messages: {
                            required: "Please enter quantity",
                            digits: "Please enter a valid quantity (numeric value)",
                            maxlength: "Quantity can't be higher than 10 digits",
                            min: "Quantity can't be zero, at least one is required"
                        }
                    });
                    row.find('input[name="price[]"]').rules("add", {
                        required: true,
                        number: true,
                        messages: {
                            required: "Please enter price",
                            number: "Please enter a valid price (numeric value)"
                        }
                    });
                }
            }

            // Function to capture table data and update UI
            function captureTableData() {
                let orderItems = [];
                let rows = document.querySelectorAll('.addtbody tr');

                let totalQty = 0;
                let grossTotal = 0;
                let discountAmount = parseFloat(document.getElementById('discount').value) || 0;

                let isDuplicate = false;

                rows.forEach(function(row) {
                    let category = row.querySelector('select[name="category[]"]').value;
                    let type = row.querySelector('select[name="type[]"]').value;
                    let service = row.querySelector('select[name="service[]"]').value;
                    let quantity = row.querySelector('input[name="quantity[]"]').value;
                    let price = row.querySelector('input[name="price[]"]').value;
                    let nltype = row.querySelector('input[name="nltype[]"]').value;
                    let comment = row.querySelector('input[name="comment[]"]').value;

                    quantity = parseInt(quantity) || 0;
                    price = parseFloat(price) || 0;

                    totalQty += quantity;
                    grossTotal += quantity * price;

                    if (category === "" || type === "") {
                        return; // Skip this row for duplicate check
                    }

                    // Find or create the category in the orderItems array
                    let categoryIndex = orderItems.findIndex(item => item.category === category);
                    if (categoryIndex === -1) {
                        orderItems.push({
                            category: category,
                            types: []
                        });
                        categoryIndex = orderItems.length - 1;
                    }

                    // Find or create the type within the category
                    let typeIndex = orderItems[categoryIndex].types.findIndex(t => t.type === type);
                    if (typeIndex === -1) {
                        orderItems[categoryIndex].types.push({
                            type: type,
                            services: []
                        });
                        typeIndex = orderItems[categoryIndex].types.length - 1;
                    }

                    // Add the service to the type
                    orderItems[categoryIndex].types[typeIndex].services.push({
                        service: service,
                        quantity: quantity,
                        price: price,
                        nltype: nltype,
                        comment: comment
                    });
                });


                let totalAmount = grossTotal - (grossTotal * (discountAmount / 100));

                // Update UI elements with calculated totals
                document.getElementById('gross_total').value = grossTotal.toFixed(2); // Set total amount * quantity
                document.getElementById('grossTotal').innerText = grossTotal.toFixed(
                    2); // Set total amount * quantity
                document.getElementById('discountAmount').innerText = (grossTotal * (discountAmount / 100)).toFixed(
                    2); // Set discount amount
                document.getElementById('total_qty').value = totalQty;
                document.getElementById('totalQty').innerText = totalQty + " pc";
                document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);

                console.log(orderItems);
                console.log(grossTotal);

                // Set the value of the hidden input field to the JSON stringified array
                document.getElementById('AddorderItemsData').value = JSON.stringify(orderItems);
            }

            // Call the initialization function when the DOM is loaded
            initializeOrderPage();


            let array = {
                data: []
            };
            // let newarray = [];

            // let AllobjectArray = [];
            $('.alphabet').on('click', function(e) {
                e.preventDefault();
                var alphabet = $(this).data('alphabet');
                var options = $('select[name="category[]"] option');
                options.hide();
                options.filter(function() {
                    return $(this).data('alphabet') === alphabet;
                }).show();
                $(this).addClass('active-alphabet').siblings().removeClass('active-alphabet');
            });

            const bookingDateInput = document.getElementById('booking_date');
            console.log("daatef", bookingDateInput);
            if (!bookingDateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                bookingDateInput.value = today;
            }

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
                        $('#categoryPreviewServiceName').html(removeClassFromHtml(operationSectionClone
                            .outerHTML,
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
                        $(control).closest('.mainopdiv').find('.subcategory').removeClass(
                                'bg-success')
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
                /// lert("hjvefhgewvfwehg");
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

                $.validator.addMethod("notEarlierThanBookingTime", function(value, element, params) {
                    const bookingDate = $("#booking_date").val();
                    const deliveryDate = $("#delivery_date").val();
                    const bookingTime = $("#booking_time").val();
                    const deliveryTime = value;
                    if (bookingDate && deliveryDate && bookingTime && deliveryTime) {
                        const bookingDateParts = bookingDate.split("-");
                        const deliveryDateParts = deliveryDate.split("-");
                        const bookingTimeParts = bookingTime.split(":");
                        const deliveryTimeParts = deliveryTime.split(":");
                        const bookingDateYear = parseInt(bookingDateParts[0]);
                        const bookingDateMonth = parseInt(bookingDateParts[1]);
                        const bookingDateDay = parseInt(bookingDateParts[2]);
                        const deliveryDateYear = parseInt(deliveryDateParts[0]);
                        const deliveryDateMonth = parseInt(deliveryDateParts[1]);
                        const deliveryDateDay = parseInt(deliveryDateParts[2]);
                        const bookingTimeHours = parseInt(bookingTimeParts[0]);
                        const bookingTimeMinutes = parseInt(bookingTimeParts[1]);
                        const deliveryTimeHours = parseInt(deliveryTimeParts[0]);
                        const deliveryTimeMinutes = parseInt(deliveryTimeParts[1]);
                        if (deliveryDateYear < bookingDateYear || (deliveryDateYear ===
                                bookingDateYear && deliveryDateMonth < bookingDateMonth) || (
                                deliveryDateYear === bookingDateYear && deliveryDateMonth ===
                                bookingDateMonth && deliveryDateDay < bookingDateDay) || (
                                deliveryDateYear === bookingDateYear && deliveryDateMonth ===
                                bookingDateMonth && deliveryDateDay === bookingDateDay &&
                                deliveryTimeHours < bookingTimeHours) || (deliveryDateYear ===
                                bookingDateYear && deliveryDateMonth === bookingDateMonth &&
                                deliveryDateDay === bookingDateDay && deliveryTimeHours ===
                                bookingTimeHours && deliveryTimeMinutes < bookingTimeMinutes)) {
                            return false;
                        }
                    }
                    return true;
                }, "Delivery time cannot be earlier than booking time.");

                $.validator.addMethod("notEarlierThanBookingDate", function(value) {
                    const bookingDate = $("#booking_date").val();
                    const deliveryDate = value;
                    if (bookingDate && deliveryDate) {
                        const bookingDateParts = bookingDate.split("-");
                        const deliveryDateParts = deliveryDate.split("-");
                        const bookingDateYear = parseInt(bookingDateParts[0]);
                        const bookingDateMonth = parseInt(bookingDateParts[1]);
                        const bookingDateDay = parseInt(bookingDateParts[2]);
                        const deliveryDateYear = parseInt(deliveryDateParts[0]);
                        const deliveryDateMonth = parseInt(deliveryDateParts[1]);
                        const deliveryDateDay = parseInt(deliveryDateParts[2]);
                        if (deliveryDateYear < bookingDateYear || (deliveryDateYear ===
                                bookingDateYear && deliveryDateMonth < bookingDateMonth) || (
                                deliveryDateYear === bookingDateYear && deliveryDateMonth ===
                                bookingDateMonth && deliveryDateDay < bookingDateDay)) {
                            return false;
                        }
                    }
                    return true;
                }, "Delivery date cannot be earlier than booking date.");

                $.validator.addMethod("validYearLength", function(value, element) {
                    const dateParts = value.split("-");
                    const year = dateParts[0];
                    return year.length <= 4;
                }, "Year cannot be more than 4 digits.");

                // function applyValidation() {
                //     console.log("hello");
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
                            minBookingDate: true,
                            validYearLength: true
                        },
                        booking_time: {
                            required: true
                        },
                        delivery_date: {
                            required: true,
                            date: true,
                            minDeliveryDate: true,
                            notEarlierThanBookingDate: true,
                            validYearLength: true
                        },
                        delivery_time: {
                            required: true,
                            notEarlierThanBookingTime: true
                        },
                        // discount: {
                        //     required: true,
                        //     min : 1
                        // },
                        "category[]": {
                            required: true,
                        },
                        "type[]": {
                            required: true,
                        },
                        "service[]": {
                            required: true,
                        },
                        "quantity[]": {
                            required: true,
                            digits: true, // Ensure quantity is a digit
                            maxlength: 10,
                            min: 1,
                        },
                        "price[]": {
                            required: true,
                            number: true, // Ensure price is a number
                        },
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
                            minBookingDate: "Booking date cannot be earlier than today",
                            validYearLength: "Year cannot be more than 4 digits."
                        },
                        booking_time: {
                            required: "Please enter booking time"
                        },
                        delivery_date: {
                            required: "Please enter delivery date",
                            date: "Please enter a valid date",
                            minDeliveryDate: "Delivery date cannot be earlier than today",
                            notEarlierThanBookingDate: "Delivery date cannot be earlier than booking date",
                            validYearLength: "Year cannot be more than 4 digits."
                        },
                        delivery_time: {
                            required: "Please enter delivery time",
                            date: "Please enter a valid time",
                            notEarlierThanBookingTime: "Delivery time cannot be earlier than booking time"
                        },
                        // discount: {
                        //     required: "Please select a discount offer",
                        //     min: "Please select a valid discount offer"
                        // },
                        "category[]": {
                            required: "Please select an item",
                        },
                        "type[]": {
                            required: "Please select an item type",
                        },
                        "service[]": {
                            required: "Please select a service",
                        },
                        "quantity[]": {
                            required: "Please enter quantity",
                            digits: "Please enter a valid quantity (numeric value)",
                            maxlength: "Quantity can't be higher than 10 digits",
                            min: "Quantity can't be zero, at least one is required",
                        },
                        "price[]": {
                            required: "Please enter price",
                            number: "Please enter a valid price (numeric value)",
                        },
                    },
                    //     errorPlacement: function (error, element) {
                    //     if (element.attr("name") == "discount") {
                    //         error.appendTo("#discountError");
                    //     } else if (element.attr("name") == "category[]") {
                    //         error.appendTo(element.parent().find(".category-error"));
                    //     } else if (element.attr("name") == "service[]") {
                    //         error.appendTo(element.parent().find(".service-error"));
                    //     } else {
                    //         error.insertAfter(element);
                    //     }
                    // }
                    submitHandler: function(form) {
                        let isValid = true;

                        // Validate each dynamic row
                        $('.addtbody tr').each(function() {
                            let row = $(this);
                            let errorMessages = row.find('.error-message');

                            if (!row.find('.cat-select').val()) {
                                errorMessages.eq(0).text('Please select an item');
                                isValid = false;
                            } else {
                                errorMessages.eq(0).text('');
                            }

                            if (!row.find('.type-select').val()) {
                                errorMessages.eq(1).text('Please select an item type');
                                isValid = false;
                            } else {
                                errorMessages.eq(1).text('');
                            }

                            if (!row.find('.service-select').val()) {
                                errorMessages.eq(2).text('Please select a service');
                                isValid = false;
                            } else {
                                errorMessages.eq(2).text('');
                            }

                            if (!row.find('input[name="quantity[]"]').val()) {
                                errorMessages.eq(3).text('Please enter quantity');
                                isValid = false;
                            } else {
                                errorMessages.eq(3).text('');
                            }

                            if (!row.find('input[name="price[]"]').val()) {
                                errorMessages.eq(4).text('Please enter price');
                                isValid = false;
                            } else {
                                errorMessages.eq(4).text('');
                            }
                        });

                        // If all dynamic fields are valid, submit the form
                        if (isValid) {
                            form.submit();
                        } else {
                            // alert('Please fill out all required fields in each row.');
                            $('.all-error').text(
                                'Please fill out all required fields in each row.');
                        }
                    }
                });

                $('#number').on("keyup", function() {
                    const clientNum = $(this).val();
                    if (clientNum.length === 10) {
                        $.ajax({
                            url: "/admin/fetch-client-name",
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
                            error: (xhr, status, error) => console.error(
                                "Error fetching client name:",
                                error)
                        });
                    } else if (clientNum.length < 10) {
                        $("#client_name").val(''); // Clear the client name input
                    }
                });



                // const discountSelect = document.getElementById('discount');
                // const discountError = document.getElementById('discountError');

                // discountSelect.addEventListener('change', () => {
                //     if (discountSelect.value === '0') {
                //         discountError.textContent = 'Please select a discount offer.';
                //         discountError.style.display = 'block';
                //     } else {
                //         discountError.textContent = '';
                //         discountError.style.display = 'none';
                //     }
                // });
                // $('#discount').on('change', function() {
                //     selectedDiscount = parseFloat(this.value);
                //     updateGrossTotal();
                // });

                // $('#addRightOdrbtn').on('click', function() {
                //     const qty = parseInt($('#qtyPlsMns').val());
                //     const itemData = selectedOperation;
                //     // const productName = $.trim($('#categoryPreviewItemName').text());
                //     const productName = itemData.pid;
                //     const category = itemData.item_cat_id;
                //     // const service = $.trim($('#categoryPreviewServiceName').text());
                //     // const service = itemData.op_name;
                //     const service = `${itemData.op_id}, ${itemData.price}`;
                //     const unitPrice = parseFloat(selectedOperation[2]);
                //     console.log("for category", itemData[3]);
                //     console.log("for service", service);
                //     console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>productName", productName, )
                //     console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>category", category)
                //     console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>service", service)
                //     console.log('selectedPrice', selectedOperation);
                //     console.log('itemdata', itemData);
                //     console.log('itemdata', itemData.op_name);
                //     console.log('categoryData', itemData.item_cat_id);
                //     console.log('selectedPrice IDM', {
                //         productName: itemData[4],
                //         category: itemData[3],
                //         service: itemData[0],
                //         qty: qty,
                //         unitPrice
                //     });
                //     if (qty > 0) {
                //         const itemId = `${itemData[4]}`;
                //         // const rBtn = $('#productId' + itemId);

                //         // if (rBtn) {
                //         //     rBtn.removeClass('dev-hide');
                //         // }
                //         // const itemId = `${productName}-${category}-${service}`;
                //         if (!items[itemId]) {
                //             items[itemId] = {
                //                 productName: itemData[4],
                //                 // category: itemData[3],
                //                 category: category,
                //                 service: service,
                //                 qty: qty,
                //                 unitPrice
                //             };
                //         }
                //         console.log("new servicejjjj", service);
                //         items[itemId].qty = qty;

                //         addToDataArray(productName, category, service, qty);
                //         $('#categoryPriceItem').empty();
                //         $('#categoryPriceItem').val(JSON.stringify(array));
                //         $('#productItemError').hide();
                //         updateGrossTotal();
                //     }

                //     document.getElementById('addOrderModel').click();
                //     $('#qtyPlsMns').val(0); // Reset quantity input
                // });
            });

            // function addToDataArray(productName, category, service, qty) {
            //     if (!Array.isArray(productName)) productName = [productName];
            //     if (!Array.isArray(category)) category = [category];
            //     if (!Array.isArray(service)) service = [service];
            //     if (!Array.isArray(qty)) qty = [qty];

            //     productName.forEach((name, index) => {
            //         const categoryValue = category[index];
            //         const serviceValue = service[index];
            //         const qtyValue = qty[index];

            //         console.log(">>>>>>>>>>", name, "<>", categoryValue, ">>>><<<<", serviceValue, "<<<<<<>>>",
            //             qtyValue);

            //         let product = array.data.find(item => item.name === name);
            //         if (!product) {
            //             product = {
            //                 name: name,
            //                 CatItems: [],
            //                 // a2: []
            //             };
            //             array.data.push(product);
            //         }

            //         // For a1 array
            //         let categoryObjA1 = product.CatItems.find(item => item.name === categoryValue);
            //         if (!categoryObjA1) {
            //             categoryObjA1 = {
            //                 // name: categoryValue.trim(),
            //                 name: categoryValue,
            //                 Operations: [],
            //                 // b2: []
            //             };
            //             product.CatItems.push(categoryObjA1);
            //         }

            //         // For a2 array
            //         // let categoryObjA2 = product.a2.find(item => item.name === categoryValue);
            //         // if (!categoryObjA2) {
            //         //     categoryObjA2 = {
            //         //         name: categoryValue.trim(),
            //         //         b1: [],
            //         //         b2: []
            //         //     };
            //         //     product.a2.push(categoryObjA2);
            //         // }

            //         // Push or update serviceObj for a1 -> b1
            //         let serviceObjA1B1 = categoryObjA1.Operations.find(item => item.type === serviceValue);
            //         if (!serviceObjA1B1) {
            //             serviceObjA1B1 = {
            //                 type: serviceValue,
            //                 qt: qtyValue
            //             };
            //             categoryObjA1.Operations.push(serviceObjA1B1);
            //         } else {
            //             serviceObjA1B1.qt = qtyValue;
            //         }

            //         // Push or update serviceObj for a1 -> b2
            //         // let serviceObjA1B2 = categoryObjA1.b2.find(item => item.type === serviceValue);
            //         // if (!serviceObjA1B2) {
            //         //     serviceObjA1B2 = {
            //         //         type: serviceValue,
            //         //         qt: qtyValue
            //         //     };
            //         //     categoryObjA1.b2.push(serviceObjA1B2);
            //         // } else {
            //         //     serviceObjA1B2.qt = qtyValue;
            //         // }

            //         // Push or update serviceObj for a2 -> b1
            //         // let serviceObjA2B1 = categoryObjA2.b1.find(item => item.type === serviceValue);
            //         // if (!serviceObjA2B1) {
            //         //     serviceObjA2B1 = {
            //         //         type: serviceValue,
            //         //         qt: qtyValue
            //         //     };
            //         //     categoryObjA2.b1.push(serviceObjA2B1);
            //         // } else {
            //         //     serviceObjA2B1.qt = qtyValue;
            //         // }

            //         // Push or update serviceObj for a2 -> b2
            //         // let serviceObjA2B2 = categoryObjA2.b2.find(item => item.type === serviceValue);
            //         // if (!serviceObjA2B2) {
            //         //     serviceObjA2B2 = {
            //         //         type: serviceValue,
            //         //         qt: qtyValue
            //         //     };
            //         //     categoryObjA2.b2.push(serviceObjA2B2);
            //         // } else {
            //         //     serviceObjA2B2.qt = qtyValue;
            //         // }

            //         console.log("goodarray", array);
            //     });
            // }

            function toggleCheckbox() {
                const checkbox = document.getElementById("flexSwitchCheckDefault");
                checkbox.value = checkbox.checked ? 1 : 0;
            }

            // function updateGrossTotal() {
            //     let grossTotal = 0;
            //     let totalQty = 0;
            //     console.log(items)
            //     var amount = selectedOperation;
            //     console.log("newdata" + parseInt(amount[2]));
            //     // console.log("newdata" +  amount.price);

            //     var newqty = parseInt($('#qtyPlsMns').val());
            //     console.log("newqty" + newqty);
            //     totalQty = newqty;
            //     // var newamt=parseInt(amount);
            //     var newamt = parseInt(amount[2]);
            //     console.log("newamt " + newamt);
            //     grossTotal = amount.price * totalQty;
            //     console.log(grossTotal + " new gross total");

            //     var gt = document.getElementById('gross_total').value;
            //     gt = (gt) ? parseInt(gt) + grossTotal : grossTotal;
            //     gt = isNaN(gt) ? 0 : gt;
            //     document.getElementById('grossTotal').innerText = gt;
            //     document.getElementById('gross_total').value = gt;
            //     //
            //     var qty = document.getElementById('total_qty').value;
            //     qty = (qty) ? parseInt(qty) + totalQty : totalQty;
            //     qty = isNaN(qty) ? 0 : qty;
            //     document.getElementById('totalQty').innerText = qty + ' pc';
            //     document.getElementById('total_qty').value = qty;

            //     var discountAmount = (gt * selectedDiscount) / 100;
            //     console.log(discountAmount);
            //     var totalAmount = gt - discountAmount;

            //     document.getElementById('discountAmount').innerText = discountAmount.toFixed(2);
            //     document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
            // }

        });
    </script>
