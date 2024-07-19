@extends('backend.layouts.app')
@section('content')
    <style>
        .disabled {
            pointer-events: none;
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
                                        @if (isset($order))
                                            Edit
                                        @else
                                            Add
                                        @endif Order
                                    </h4>
                                </div>
                            </div>

                        </div>

                        <form action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data"
                            id="addOrderFormValidation">
                            @method('PUT')

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
                                        @php
                                            $discountAmount = 0;
                                        @endphp
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="discount" class="form-label">Discount Offer</label>
                                                <select name="discount" id="discount" class="form-select">
                                                    <option value="0" selected>Select Discount Offer</option>
                                                    @foreach ($discounts as $discount)
                                                        <option
                                                            @if ($discount->id === $order->discount_id) @php
                                                            $discountAmount=$discount->amount;
                                                        @endphp
                                                selected @endif
                                                            value="{{ $discount->amount }}">{{ $discount->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <hr />
                                        @php
                                            $grossTotal = 0;
                                            $itemList = [];
                                        @endphp
                                        @foreach ($orderItems as $orderItem)
                                            @php
                                                $grossTotal += $orderItem['qty'] * $orderItem['unit_price'];
                                                $itemList[$orderItem['product_item_id']] = [
                                                    'productName' => $orderItem['product_item_id'],
                                                    'category' => $orderItem['category_id'],
                                                    'service' => $orderItem['service_id'],
                                                    'qty' => $orderItem['qty'],
                                                    'unitPrice' => $orderItem['unit_price'],
                                                ];
                                            @endphp
                                        @endforeach
                                        <!-- Gross Total Section -->
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3">
                                            <div class="row justify-content-between">
                                                <input type="hidden" name="gross_total" id="gross_total"
                                                    value="{{ $grossTotal }}" />
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Gross Total: </h6>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6 id="grossTotal">{{ $grossTotal }}</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Discount Amount:</h6>
                                                </div>
                                                <div id="discountAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>{{ round(($grossTotal * $discountAmount) / 100, 2) }}</h6>
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
                                                            value="0" onchange="toggleCheckbox()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <input type="hidden" name="total_qty" id="total_qty"
                                                    value="{{ $order->total_qty }}" />
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Total Count:</h6>
                                                </div>
                                                <div id="totalQty" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>{{ $order->total_qty }} pc</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Total Amount:</h6>
                                                </div>
                                                <div id="totalAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>{{ $order->total_price }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Add_order_btn_area text-end mb-2">
                                                <button class="btn w-100" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#UpdateOrder">Update</button>
                                            </div>
                                        </div>
                                        <!-- Create Order Model -->
                                        <div class="modal fade" id="UpdateOrder" tabindex="-1"
                                            aria-labelledby="CreateOrderLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <h5>Would you like to Update an Order?</h5>
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
                                                        <a type="button" class="btn btn-primary" href="{{ url('/admin/receipt/{orderId}') }}">
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

                                    <input type="hidden" value="" name="categoryPriceItem"
                                        id="categoryPriceItem">
                                    <input type="hidden" value="" name="categoryItem" id="categoryItem">

                                    <div class="row">
                                        @foreach ($groupedProductItems as $groupedProductItem)
                                            @php
                                                $product = $groupedProductItem['product'];
                                                $productName = $product->name;
                                                $productImage = $groupedProductItem['image'];
                                                $uniqueCategories = $groupedProductItem['CatItems'];

                                                $orderItemCategoryName =
                                                    $orderItems[$product->id]['category_name'] ?? '';

                                                $orderItemCategoryName = isset(
                                                    $orderItems[$product->id]['category_name'],
                                                )
                                                    ? $orderItems[$product->id]['category_name']
                                                    : '';
                                                $orderItemServiceId = isset($orderItems[$product->id]['service_id'])
                                                    ? $orderItems[$product->id]['service_id']
                                                    : null;
                                                // dd($groupedProductItems);
                                            @endphp

                                            <div class="border rounded p-2 mb-2">
                                                <div class="row">
                                                    <div class="col-lg-9 col-md-9 mainopdiv1">
                                                        <h6 class="mb-2 text-dark">{{ $productName }}</h6>
                                                        <div class="categorysection">
                                                            {{-- @foreach ($uniqueCategories as $category)
                                                                @php
                                                                    $categoryName = $category['name'];
                                                                    // $operationData =
                                                                    //     $groupedProductItem['operationData'];
                                                                    // $operationData = json_encode(
                                                                    //     $category['Operations'],
                                                                    // );
                                                                    $operationData = json_encode($category['Operations'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                                                                    // dd($operationData);
                                                                @endphp

                                                                {{-- <span
                                                                    onclick="categoryItem('{{ $categoryName }}', '{{ $product->id }}', this)"
                                                                    class="badge text-dark mb-2 subcategory @if ($orderItemCategoryName === $categoryName) bg-success selectedCategories @else bg-light @endif">{{ $categoryName }}</span> --}}
                                                            {{-- <span
                                                                    onclick="categoryItem('{{ $categoryName }}', '{{ $product->id }}', this)"
                                                                    class="badge text-dark mb-2 subcategory @if ($orderItemCategoryName === $categoryName) bg-success selectedCategories @else bg-light @endif"
                                                                    data-operation-data="{{ htmlspecialchars($operationData) }}">{{ $categoryName }}</span>
                                                            @endforeach --}}
                                                            @foreach ($operationsArray as $product)
                                                            @if ($product['product_name'] === $productName)
                                                                @foreach ($product['categories'] as $category)
                                                                    @php
                                                                        $operationData = json_encode(
                                                                            $category['operations'],
                                                                            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
                                                                        );
                                                        
                                                                        // Initialize isMatch as false for each category
                                                                        $isMatch = false;
                                                        
                                                                        // Loop through operations to check for matches
                                                                        foreach ($category['operations'] as $operation) {
                                                                            // Loop through uniqueCategories to find a match
                                                                            foreach ($uniqueCategories as $uniqueCategory) {
                                                                                if ($uniqueCategory['name'] === $category['category_name']) {
                                                                                    if (isset($uniqueCategory['Operations']) && is_array($uniqueCategory['Operations'])) {
                                                                                        foreach ($uniqueCategory['Operations'] as $uniqueOperation) {
                                                                                            if ($operation['service_id'] == $uniqueOperation['service_id']) {
                                                                                                // Set isMatch to true if a match is found
                                                                                                $isMatch = true;
                                                                                                // Exit the loop once a match is found
                                                                                                break 3; // Exit all three nested loops
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <span
                                                                        onclick="categoryItem('{{ $category['category_name'] }}', '{{ $product['product_name'] }}', this, {{ $isMatch ? 'true' : 'false' }})"
                                                                        class="badge text-dark mb-2 subcategory bg-light"
                                                                        data-operation-data="{{ htmlspecialchars($operationData) }}">
                                                                        {{ $category['category_name'] }}
                                                                    </span>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                        {{-- @dd($others); --}}
                                                        {{-- @dd($operationsArray); --}}
                                                        {{-- @dd($uniqueCategories); --}}
                                                        
                                                        
                                                        </div>
                                                        {{-- @dd($operationsArray); --}}

                                                        <div class="oprationData disabled">
                                                            {!! $groupedProductItem['operationData'] !!}
                                                            {{-- @dd($groupedProductItem); --}}
                                                        </div>
                                                        {{-- @dd($uniqueCategories); --}}
                                                    </div>
                                                    {{-- @dd($uniqueCategories); --}}
                                                    <div class="col-lg-3 col-md-3 text-center">
                                                        <img class="mb-2"
                                                            src="{{ url('images/categories_img/' . $productImage) }}"
                                                            alt="{{ $productName }}" style="width: 50px;">
                                                        <div class="Add_order_btn_area">
                                                            <button
                                                                onclick="setPrevQty('{{ $orderItems[$product['product_name']]['id'] ?? 0 }}', '{{ $orderItems[$product['product_name']]['qty'] ?? 0 }}', '{{ $product['product_name'] }}');"
                                                                type="button" id="addbtnpreview"
                                                                class="btn add-product-btn" data-bs-toggle="offcanvas"
                                                                data-bs-target="#offcanvasRight"
                                                                aria-controls="offcanvasRight"
                                                                data-product-name="{{ $productName }}"
                                                                data-images="{{ url('images/categories_img/' . $productImage) }}">Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                                            aria-labelledby="offcanvasRightLabel">
                                            <div class="offcanvas-header">
                                                <h5 id="offcanvasRightLabel">Curtain Panel</h5>
                                                <button type="button" class="btn-close text-reset"
                                                    data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body mainopdiv">
                                                <div class="border-bottom mb-3">
                                                    <h6 class="mb-2 text-dark" id="categoryPreviewItemName">
                                                        Chudidar/Payjama
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
                                                            value="0" id="qtyPlsMns" name="qty"
                                                            placeholder="Pc" aria-label="Amount (to the nearest dollar)">
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
                            <div class="modal fade" id="UpdateOrder" tabindex="-1" aria-labelledby="CreateOrderLabel"
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

                            {{-- <!-- Print Order Modal -->
                            <div class="modal fade" id="yes" tabindex="-1" aria-labelledby="yesLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <h5>Please select from the following options:</h5>
                                            <a class="btn btn-primary" href="{{ url('/admin/receipt') }}">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <!-- jQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // alert("hello");
        let selectedPId = 0;
        const autoAppearSelectedServices = () => {
            const selectedServices = $(".selectedCategories");
            for (let index = 0; index < selectedServices.length; index++) {
                selectedServices[index]?.click();
            }
        }
        document.addEventListener('DOMContentLoaded', (event) => {
            autoAppearSelectedServices();
            const bookingDateInput = document.getElementById('booking_date');
            if (!bookingDateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                bookingDateInput.value = today;
            }
            updateGrossTotal();
        });

        function setPrevQty(serviceId, qty, pid) {
            selectedPId = pid;
            console.log('beforeqty', qty);
            if (+qty > 0) {
                console.log('qty', qty);
                let qtyPlsMns = document.getElementById("qtyPlsMns");
                qtyPlsMns.value = +qty;
                if ($('#serviceId' + serviceId)) {
                    console.log('dddd', '#serviceId' + serviceId);
                    $('#serviceId' + serviceId).click();
                }
            }
            //updateGrossTotal();
        }

        let selectedOperation = '';
        let selectedDiscount = parseFloat('{{ $discountAmount }}');
        let items = @json($itemList);
        console.log('items>>', items);
        // let items = {{ json_encode($itemList) }};

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

        // const categoryItem = (operationName, PrdId, control) => {
        //     console.log("newdata", control);
        //     $.ajax({
        //         url: "{{ url('get-allservice') }}",
        //         type: 'POST',
        //         data: {
        //             'name': operationName,
        //             'id': PrdId,
        //             'others': @json($orderItems),
        //             '_token': '{{ csrf_token() }}'
        //         },
        //         success: response => {
        //             const mainDiv = $(control).closest('.mainopdiv');
        //             mainDiv.find('.oprationData').html(response);

        //             // Remove 'bg-success' and add 'bg-light' for all .subcategory elements within the mainDiv
        //             mainDiv.find('.subcategory').removeClass('bg-success').addClass('bg-light');
        //             // Add this console log to inspect mainDiv content
        //     console.log("newhtml",mainDiv.html());

        //             // Add 'bg-success' and remove 'bg-light' for the clicked element
        //             $(control).addClass('bg-success').removeClass('bg-light');
        //         },
        //         error: (xhr, status, error) => console.error(xhr.responseText)
        //     });
        // };

        //     document.addEventListener("DOMContentLoaded", (event) => {
        //     function categoryItem(categoryName, productId, element) {
        //         // Remove the bg-success class and add bg-light class to all category spans
        //         document.querySelectorAll('.subcategory').forEach(function(span) {
        //             span.classList.remove('bg-success', 'selectedCategories');
        //             span.classList.add('bg-light');
        //         });

        //         // Add the bg-success class and remove bg-light class to the clicked span
        //         element.classList.add('bg-success', 'selectedCategories');
        //         element.classList.remove('bg-light');

        //         // Get the operation data from the clicked span
        //         const operationData = element.getAttribute('data-operation-data');

        //         // Find the operationData div within the same mainopdiv1 container
        //         const operationDataDiv = element.closest('.mainopdiv1').querySelector('.oprationData');

        //         // Update the operationData div content
        //         operationDataDiv.innerHTML = operationData;
        //     }
        // });



        // const categoryItem = (operationName, PrdId, control) => {
        //     console.log("newdata", control);
        //     $.ajax({
        //         url: "{{ url('get-allservice') }}",
        //         type: 'POST',
        //         data: {
        //             'name': operationName,
        //             'id': PrdId,
        //             'others': @json($orderItems),
        //             '_token': '{{ csrf_token() }}'
        //         },
        //         success: response => {
        //             const mainDiv = $(control).closest('.mainopdiv1');
        //             mainDiv.find('.oprationData').html(response);

        //             // Ensure only the intended element is updated
        //             // mainDiv.find('.subcategory').removeClass('bg-success').addClass('bg-light');
        //             // $(control).addClass('bg-success').removeClass('bg-light');

        //             // Inspect the mainDiv content to ensure it is correct
        //             console.log("newhtml", mainDiv.html());
        //             console.log("newhtml", response);
        //         },
        //         error: (xhr, status, error) => console.error(xhr.responseText)
        //     });
        // };


        document.addEventListener('DOMContentLoaded', function() {
            // Function to decode HTML entities
            function decodeHTMLEntities(text) {
                const textArea = document.createElement('textarea');
                textArea.innerHTML = text;
                return textArea.value;
            }

            // Define the categoryItem function in the global scope
            window.categoryItem = function(categoryName, productName, control,isMatch) {
                console.log("Clicked on:", categoryName, productName);

                // Remove the bg-success class from all spans and add bg-light class
                document.querySelectorAll('.subcategory').forEach(function(span) {
                    span.classList.remove('bg-success', 'selectedCategories');
                    span.classList.add('bg-light');
                });

                // Add the bg-success class to the clicked span and remove bg-light class
                control.classList.add('bg-success', 'selectedCategories');
                control.classList.remove('bg-light');

                // Get the operation data from the clicked span's data attribute
                let operationDataString = control.getAttribute('data-operation-data');

                // Log the raw operation data string for debugging
                console.log("Raw operation data:", operationDataString);

                // Decode HTML entities
                operationDataString = decodeHTMLEntities(operationDataString);

                try {
                    const operationData = JSON.parse(operationDataString);

                    // Find the operationData div within the same mainopdiv1 container
                    const operationDataDiv = control.closest('.mainopdiv1').querySelector('.oprationData');

                    // Clear the existing content
                    operationDataDiv.innerHTML = '';

                    // Append new operation data to the operationData div
                    operationData.forEach(function(operation) {
                        // const isMatch = operation.service_id == '{{ $orderItems[$product['product_name']]['service_id'] ?? 0 }}';
                        // const isMatch = isServiceIdInUniqueCategories(operation.service_id);
                        const operationHtml = `
                    <div class="col-lg-4 mb-2">
                        <div class="category-service badge w-100 ${isMatch ? 'bg-success text-white' : 'bg-light text-dark'} mb-2 operationData"
                            onclick='categoryPriceItem(${JSON.stringify(operation)}, this)'>
                            <input type="hidden" value='${JSON.stringify(operation)}' id="prdSelectId${operation.service_id}'>
                            <h6 class="mb-0">${operation.service_name}</h6>
                            <h6 class="mb-0 text-dark service-price">${operation.unit_price}/pc</h6>
                        </div>
                    </div>
                `;
                        operationDataDiv.insertAdjacentHTML('beforeend', operationHtml);
                        console.log("isMatch check:", isMatch);
                    });
                } catch (e) {
                    console.error("Error parsing operation data:", e, operationDataString);
                }
            };
            // Function to check if service_id exists in uniqueCategories['Operations']
            // function isServiceIdInUniqueCategories(serviceId) {
            //     const uniqueCategoriesOperations = {!! json_encode($uniqueCategories['Operations'] ?? []) !!};
            //     return uniqueCategoriesOperations.some(op => op.service_id === serviceId);
            // }
        });







        /* const categoryPriceItem = (categoryPriceItemV, control) => {
            console.log(`controlCatSer>>>>>>>>`,control);
            $('#categoryPriceItem').val(categoryPriceItemV);

            // $('.category-service').removeClass('bg-success');
            // $(control).addClass('bg-success');

            $(control).closest('.operationData').removeClass('bg-success').addClass('bg-light');
           $(control).addClass('bg-success').removeClass('bg-light');
            selectedOperation = categoryPriceItemV;
            console.log('selected Operation>>>>>', selectedOperation);
            const itemCatId = selectedOperation.split('|');
            $('#categoryItem').val(itemCatId[3]);
        }; */

        let currentlySelectedService = null;
        const categoryPriceItem = (categoryPriceItemV, control) => {
            console.log(`control`, control);

            // Remove highlight from previously selected service
            // if (currentlySelectedService) {
            //     currentlySelectedService.classList.remove('bg-success', 'text-white');
            //     currentlySelectedService.classList.add('bg-light', 'text-dark');
            // }

            // Highlight the newly selected service
            $(control).addClass('bg-success text-white').removeClass('bg-light text-dark');

            // Update the currently selected service
            currentlySelectedService = control;

            $('#categoryPriceItem').val(categoryPriceItemV);

            selectedOperation = categoryPriceItemV;
            const itemCatId = selectedOperation;
            $('#categoryItem').val(itemCatId[3]);
        };

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

            $('#yesButton').on('click', () => $('#UpdateOrder').modal('hide'));

            $('#number').on("input", function() {
                const val = $(this).val().replace(/\D/g, '');
                $(this).val(val.slice(0, 10));
            });

            $('#name').on("input", function() {
                const val = $(this).val();
                $(this).val(val.slice(0, 50));
            });

            // $.validator.addMethod("minBookingDate", function(value) {
            //     const selectedDate = new Date(value);
            //     const currentDate = new Date();
            //     currentDate.setHours(0, 0, 0, 0);
            //     return selectedDate >= currentDate;
            // }, "Booking date cannot be earlier than today.");

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
                    // booking_date: {
                    //     required: true,
                    //     date: true,
                    //     minBookingDate: true
                    // },
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
                    // booking_date: {
                    //     required: "Please enter booking date",
                    //     date: "Please enter a valid date",
                    //     minBookingDate: "Booking date cannot be earlier than today"
                    // },
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
                    }
                },
                submitHandler: function(form) {
                    const listItems = items;
                    console.log('handler>>>', listItems);
                    // Append item details to form before submission
                    Object.keys(listItems).forEach(key => {

                        const item = listItems[key];
                        if (item.qty > 0) {
                            $('#addOrderFormValidation').append(
                                `<input type="hidden" name="items[${item.productName}][${item.category}][${item.service}][qty]" value="${item.qty}">`
                            );
                            $('#addOrderFormValidation').append(
                                `<input type="hidden" name="items[${item.productName}][${item.category}][${item.service}][unit_price]" value="${item.unitPrice}">`
                            );
                        }
                    });
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

            function removeProductItem(itemId) {
                console.log('selectedPrice-rm', itemId);
                console.log('selrcteitems>>>', items);
                if (items[itemId]) {
                    delete items[itemId];
                    console.log('selrcteitems>>>', items);
                    updateGrossTotal();
                } else {
                    alert('Item does not exist.');
                }
            }
            $('#addRightOdrbtn').on('click', function() {
                const qty = parseInt($('#qtyPlsMns').val());
                if (+selectedPId && !selectedOperation) {
                    console.log(`selectedPId v1>>`, selectedPId);
                    selectedOperation = document.getElementById("prdSelectId" + selectedPId).value;
                    console.log(`selectedPId selectedOperation>>`, selectedOperation);
                }
                const itemData = selectedOperation;
                const productName = $('#categoryPreviewItemName').text();
                const category = $('#categoryPreviewCategName').text();
                const service = $('#categoryPreviewServiceName').text();
                const unitPrice = parseFloat(itemData[2]);
                console.log('selectedPrice IDM', {
                    itemId: itemData[5],
                    productName: itemData[4],
                    category: itemData[3],
                    service: itemData[0],
                    qty: qty,
                    unitPrice
                });
                const itemId = selectedPId != '0' ? selectedPId : itemData[4];
                if (+qty > 0) {
                    delete items[itemId];
                    if (!items[itemId]) {
                        items[itemId] = {
                            itemId: itemData[5],
                            productName: itemData[4],
                            category: itemData[3],
                            service: itemData[0],
                            qty: +qty,
                            unitPrice
                        };
                    }
                    items[itemId].qty = qty;
                    console.log('itemv2', items);
                    // console.log(`qty====`,items[itemId].qty);
                    updateGrossTotal();
                } else {
                    removeProductItem(itemId);
                }

                $('#offcanvasRight').removeClass('show');
                $(".offcanvas-backdrop").remove();
                $('#qtyPlsMns').val(0); // Reset quantity input
            });
        });

        function toggleCheckbox() {
            const checkbox = document.getElementById("flexSwitchCheckDefault");
            checkbox.value = checkbox.checked ? 1 : 0;
        }

        function updateGrossTotal() {
            let grossTotal = 0;
            let totalQty = 0;

            Object.keys(items).forEach(key => {
                const item = items[key];
                console.log('updateGrossTotal item>>', item);
                if (item.qty > 0) {
                    totalQty += item.qty;
                    console.log(`totalQtyNew>>>>`, totalQty);
                    grossTotal += item.qty * parseFloat(item.unitPrice);
                    console.log(`grossTotalNew>>>>`, grossTotal);
                }
            });

            const discountAmount = (grossTotal * selectedDiscount) / 100;
            const totalAmount = grossTotal - discountAmount;

            document.getElementById('grossTotal').innerText = grossTotal.toFixed(2);
            document.getElementById('gross_total').value = grossTotal.toFixed(2);
            document.getElementById('discountAmount').innerText = discountAmount.toFixed(2);
            document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
            document.getElementById('totalQty').innerText = totalQty + ' pc';
            document.getElementById('total_qty').value = totalQty;
        }
    </script>
