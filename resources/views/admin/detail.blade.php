@extends('backend.layouts.app')
@section('content')
<div class="layout-page mt-4">
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <!-- Earning Reports -->
                <div class="col-md-12 mb-2"> Analytics Dashboard </div>
                <div class="col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gap-4 gap-sm-0">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge badge-kj rounded bg-label-primary p-1">
                                            <i class="fa-solid fa-chart-pie"></i>
                                        </div>
                                        <h6 class="mb-0">Totel Orders</h6>
                                    </div>
                                    <h3 class="my-2 pt-1 text-end">{{ $totalOrders }}</h3>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gap-4 gap-sm-0">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge badge-kj rounded bg-label-success p-1">
                                            <!-- <i class="menu-icon tf-icons ti ti-users"></i> -->
                                            <i class="tf-icons fa-solid fa-check-double"></i>
                                        </div>
                                        <h6 class="mb-0">Completed Orders</h6>
                                    </div>
                                    <h3 class="my-2 pt-1 text-end">{{ $deliveredOrders }}</h3>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gap-4 gap-sm-0">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge badge-kj rounded bg-label-warning p-1">
                                            <!-- <i class="menu-icon tf-icons ti ti-users"></i> -->
                                            <i class=" fa-solid fa-hourglass-half"></i>
                                        </div>
                                        <h6 class="mb-0">Pending Orders</h6>
                                    </div>
                                    <h3 class="my-2 pt-1 text-end">{{ $pendingOrders }}</h3>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gap-4 gap-sm-0">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge badge-kj rounded bg-label-info p-1">
                                            <!-- <i class="menu-icon tf-icons ti ti-users"></i> -->
                                            ₹
                                        </div>
                                        <h6 class="mb-0">Total Amount</h6>
                                    </div>
                                    <h3 class="my-2 pt-1 text-end">₹ {{ $totalOrdersAmount }} </h3>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row justify-content-between">

                                <div class="col-md-4 col-lg-4 col-xl-4"> Customers</div>
                                <div class="col-md-6 col-lg-6 col-xl-6 ">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="client_list_area_hp">
                                                <div class="client_list_heading_area">
                                                    <div class="client_list_heading_search_area me-2 mb-2">
                                                        <i class="menu-icon tf-icons ti ti-search"></i>
                                                        <input type="search" class="form-control"
                                                            placeholder="Searching ..." id="customerSearch">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive-sm">
                                <table class="table table-hover table-striped">
                                    <thead class="table_head_1f446E">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Order ID</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $serialNumber = 1;
                                            $totalprice = 0;
                                        @endphp
                                        @foreach ($totalOrderByCustomers as $totalOrderByCustomer)
                                            @php
                                                $totalprice += $totalOrderByCustomer->total_price;
                                            @endphp
                                            <tr>
                                                <td>{{ $serialNumber++ }}</td>
                                                <td><a href=""> {{ $totalOrderByCustomer->order_number }} </a></td>
                                                <td>{{ $totalOrderByCustomer->name }}</td>
                                                <td>{{ $totalOrderByCustomer->order_date }}</td>
                                                <td>
                                                    @if($totalOrderByCustomer->status == "pending")
                                                        <div class="badge rounded bg-label-warning py-1">Pending</div>
                                                    @else
                                                        <div class="badge rounded bg-label-success py-1">Completed</div>
                                                    @endif
                                                </td>
                                                <td>₹ {{ $totalOrderByCustomer->total_price }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>Total</td>
                                            <td>₹ {{ $totalprice }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).ready(function () {
            // Function to filter table rows based on search input
            $('#customerSearch').keyup(function () {
                var searchText = $(this).val().toLowerCase();

                $('tbody tr').each(function () {
                    // Retrieve text from relevant columns
                    var orderId = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var clientName = $(this).find('td:nth-child(3)').text().toLowerCase();
                    var orderDate = $(this).find('td:nth-child(4)').text().toLowerCase();

                    // Check if any of the columns contain the search text
                    if (orderId.indexOf(searchText) === -1 &&
                        clientName.indexOf(searchText) === -1 &&
                        orderDate.indexOf(searchText) === -1) {
                        $(this).hide(); // Hide row if no match
                    } else {
                        $(this).show(); // Show row if match found
                    }
                });
            });
        });
    });

</script>
@endsection