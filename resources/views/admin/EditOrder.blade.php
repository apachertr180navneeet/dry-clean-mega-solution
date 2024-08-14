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
                                        Add Order
                                    </h4>
                                </div>
                            </div>

                        </div>
                        <form action="{{ route('add.order') }}" method="POST" id="addOrderFormValidation" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <!-- Form Inputs for Client and Order Details -->
                                    <div class="row">
                                        <!-- Client Number -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_num" class="form-label">Client Number</label>
                                                <input type="text" value="{{ old('mobile', $order->mobile ?? '') }}" id="number" name="client_num" class="form-control" placeholder="Client Number">
                                            </div>
                                        </div>
                                        <!-- Client Name -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_name" class="form-label">Client Name</label>
                                                <input type="text" id="client_name" value="{{ old('name', $order->name ?? '') }}" name="client_name" class="form-control" placeholder="Client Name">
                                                <input type="hidden" id="booking_date" value="{{ $currentdate }}" name="booking_date">
                                                <input type="hidden" id="booking_time" value="{{ $currenttime }}" name="booking_time">
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
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                                <p>No product</p>
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
                                        <hr>
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
                                        <!-- end -->
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12 mb-4">
                                    <!-- Product Items Section -->
                                    <div class="client_list_area_hp">
                                        <div class="client_list_heading_area w-100">
                                            <div class="client_list_heading_search_area w-100">
                                                <i class="menu-icon tf-icons ti ti-search"></i>
                                                <input type="search" class="form-control" placeholder="Searching ...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add_order_items_list_Area_HP">
                                        <div id="productItemError" class="alert alert-danger" style="display: none;">
                                            Please add at least one product item.
                                        </div>
                                        @foreach ($groupedProductItems as $groupedProductItem)
                                            @php
                                                $productItem = $groupedProductItem['product_item'];
                                                $uniqueCategories = $groupedProductItem['unique_categories'];
                                            @endphp

                                            <div class="border rounded p-3 mb-2 hover-shadow">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-9 col-md-9 mainopdiv">
                                                        <h6 class="mb-2 text-dark">{{ $productItem->name }}</h6>
                                                        <div class="categorysection">
                                                            <span onclick="categoryItem('', this)" class="badge text-dark mb-2 subcategory bg-light">test</span>
                                                        </div>
                                                        <div class="oprationData disabled d-flex flex-wrap">
                                                            <div class="mx-2">
                                                                    <label for="" class="font_13">Dry Clean</label>
                                                                    <p class="text-dark fw-bold ">₹ 250/pc</p>
                                                            </div>
                                                            <div class="mx-2">
                                                                    <label for="" class="font_13">Steam Press</label>
                                                                    <p class="text-dark fw-bold ">₹ 250/pc</p>
                                                            </div>
                                                            <div class="mx-2">
                                                                    <label for="" class="font_13">Starching</label>
                                                                    <p class="text-dark fw-bold ">₹ 250/pc</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 text-center">
                                                        <img class="mb-3"
                                                            src="{{ url('images/categories_img/' . $productItem->image) }}"
                                                            alt="{{ $productItem->name }}" style="width: 70px;height: 50px;object-fit: contain;">
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
                                                <h5 id="offcanvasRightLabel" class="mb-0">Curtain Panel</h5>
                                                <button id="addOrderModel" type="button" class="btn-close text-reset"
                                                    data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body mainopdiv">
                                                <div class="border-bottom mb-3 pb-3">
                                                    <h6 class="mb-2 text-dark" id="categoryPreviewItemName">Select Shirt Type
                                                    </h6>
                                                    <div class="categorysection">
                                                        <span class="badge mx-1 text-white mb-2 subcategory bg-success">Kids</span>
                                                        <span class="badge mx-1 text-dark mb-2 subcategory bg-light">Slik</span>
                                                        <span class="badge mx-1 text-dark mb-2 subcategory bg-light">Cotton</span>
                                                    </div>
                                                </div>
                                                <div class="border-bottom mb-3 pb-3">
                                                    <h6 class="mb-2 text-dark" id="categoryPreviewItemName">Select one or more services *
                                                    </h6>
                                                    <div class="categorysection">
                                                        <span class="badge mx-1 text-white mb-2 subcategory bg-success">Dry Clean [₹ 70/pc]</span>
                                                        <span class="badge mx-1 text-dark mb-2 subcategory bg-light/">Starching [₹ 50/pc]</span>
                                                        <span class="badge mx-1 text-dark mb-2 subcategory bg-light/">Steam Press [₹ 270/pc]</span>

                                                    </div>
                                                </div>
                                                <!-- <a href="{}" class="btn-one" > Add Garments </a> -->
                                                <a type="button" class=" btn-one  my-4 w-100" id="backButton" href="{{Url('admin/Categorylists')}}">
                                                    Add Garments
                                                </a>
                                                <!-- <div class="border-bottom mb-3">
                                                    <div>
                                                        <span id="categoryPreviewCategName"></span>
                                                    </div>
                                                </div>
                                                <div class="border-bottom mb-3">
                                                    <div>
                                                        <span id="categoryPreviewServiceName"
                                                            class="mb-2 oprationData"></span>
                                                    </div>
                                                </div> -->

                                                <div class="offcanvas-footer pb-2">

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
                        </form>
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
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                $('#number').on("keyup", function() {
                    const clientNum = $(this).val();
                    if (clientNum.length === 10) {
                        $.ajax({
                            url: "/admin/fetch-client-name",
                            method: "GET",
                            data: { client_num: clientNum },
                            success: response => {
                                if (response.success) {
                                    $("#client_name").val(response.client_name);
                                } else {
                                    console.error(response.message);
                                }
                            },
                            error: (xhr, status, error) => console.error("Error fetching client name:", error)
                        });
                    } else if (clientNum.length < 10) {
                        $("#client_name").val(''); // Clear the client name input
                    }
                });
            });
        </script>
    @endsection
