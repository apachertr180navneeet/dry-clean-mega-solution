@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">

            <div class="OrderDetail_page_heading">
                <div class=" OrderDetail_page_heading_title my-1">
                    <a href="{{ route('viewOrder') }}" class="Back_btn_hp me-3"><i class="fa-solid fa-arrow-left-long"></i></a>
                    {{-- <h4 class="mb-0">Order Detail</h4> --}}
                </div>
                {{-- <div class="OrderDetail_page_heading_action_icons my-1">
                    {{-- <a class="btn mx-1"  href="{{ route('order.edit',$orders->id) }}"><i class="fa-solid fa-pen-to-square me-2"></i> Edit</a> --}}
                    {{-- @if($orders->paymentDetail->status !== 'Paid' || $orders->status !== 'delivered')
                    <button class="btn mx-1" onclick="window.location='{{ route('order.edit', $orders->id) }}'">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                    </button>
                @endif --}}
                    {{-- <button class="btn mx-1"><i class="fa-solid fa-print me-2"></i> Print</button>
                    <button class="btn mx-1"><i class="fa-regular fa-file-pdf me-2"></i> PDF</button> --}}
                {{-- </div>  --}}
            </div>

            <div class="OrderDetail_page_section">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="OrderDetail_page_gird_item_area">
                            <label for="">Booking ID</label>
                            <?php
                            // Format the order ID
                            $bookingId = 'ORD-' . date('Y') . '-' . str_pad($orders->id, 3, '0', STR_PAD_LEFT);
                            ?>
                            <h4>{{ $bookingId }}</h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="OrderDetail_page_gird_item_area">
                            <label for="">Client Name</label>
                            <h4>{{ $orders->user->name }}</h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="OrderDetail_page_gird_item_area">
                            <label for="">Client Number</label>
                            <h4>{{ $orders->user->mobile }}</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="OrderDetail_page_gird_item_area">
                            <label for="">Booking Date</label>
                            <h4>{{ $orders->order_date }}</h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="OrderDetail_page_gird_item_area">
                            <label for="">Booking Time</label>
                            <h4>{{ $orders->order_time }}</h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="OrderDetail_page_gird_item_area">
                            <label for="">Delivery Date</label>
                            <h4>{{ $orders->delivery_date }}</h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="OrderDetail_page_gird_item_area">
                            <label for="">Delivery Time</label>
                            <h4>{{ $orders->delivery_time }}</h4>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-12 mb-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table_head_1f446E">
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Category</th>
                                        <th>Sub-Category</th>
                                        <th>Quantity</th>
                                        <th>Child-Category</th>
                                        <th>Rate</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $serial = 1;
                                    $prevCategory = null;
                                    $prevItem = null;
                                @endphp

                                @foreach ($orders->orderItems as $orderItem)
                                    <tr>
                                        @if ($orderItem->productCategory->name != $prevCategory || $orderItem->productItem->name != $prevItem)
                                            <td>{{ $serial++ }}</td>
                                            <td>{{ $orderItem->productItem->name }}</td>
                                            <td>{{ $orderItem->productCategory->name }}</td>
                                            <td>{{ $orderItem->quantity }}</td>
                                        @else
                                            <td></td> <!-- Empty cell for serial number -->
                                            <td></td> <!-- Empty cell for product category name -->
                                            <td></td> <!-- Empty cell for product item name -->
                                            <td></td> <!-- Empty cell for product item name -->
                                        @endif

                                        <td>{{ $orderItem->opertions->name }}</td>
                                        <td>₹ {{ $orderItem->operation_price }}</td>
                                        {{-- <td>₹ {{ $orderItem->price }}</td> --}}
                                        <td>₹ {{ $orderItem->quantity * $orderItem->operation_price }}</td>
                                        <!-- Include other columns as needed -->
                                    </tr>

                                    @php
                                        $prevCategory = $orderItem->productCategory->name;
                                        $prevItem = $orderItem->productItem->name;
                                    @endphp
                                @endforeach

                                    <tr>
                                        <td colspan="4" class="border-0"></td>
                                        <th colspan="2" class="fw-bold text-dark">Sub-Total Amount</th>
                                        <th class="fw-bold text-dark">₹ {{ $subTotalAmount }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="border-0"></td>
                                        <th colspan="2" class="fw-bold text-dark">{{ optional($orders->discounts)->amount ?? 0 }}% Discount</th>
                                        <th class="fw-bold text-dark">₹ {{ number_format($discountAmount, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="border-0"></td>
                                        <th colspan="2" class="fw-bold text-dark">Total Amount</th>
                                        <th class="fw-bold text-dark">₹ {{ $totalAmount }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endsection
