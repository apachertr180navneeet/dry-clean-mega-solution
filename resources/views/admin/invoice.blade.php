@extends('backend.layouts.app')
@section('content')
    <style>
        .pagination-container {
            display: flex;
            justify-content: right;
            margin-top: 20px;

        }

        .relative svg {
            width: 30px !important;
        }

        .pagination-container nav .justify-between {
            display: none;
        }

        #dateRangeContainer {
            display: none;
        }

        .no-records-found {
            text-align: center;
            color: red;
            margin-top: 20px;
            font-size: 18px;
            display: none;
            /* Hidden by default */
        }
    </style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="client_list_heading_area">
                            <div class="row w-100 justify-content-between">
                                <div class="col-md-2">
                                    <h4>Invoice</h4>
                                </div>
                                <div class="col-md-8">
                                    <div>
                                        <div class="d-block d-lg-flex align-items-center justify-content-end">

                                            <div class="client_list_heading_search_area me-2 mb-2">
                                                <i class="menu-icon tf-icons ti ti-search"></i>
                                                <input type="search" class="form-control" placeholder="Searching ..."
                                                    id="invoiceSearch">
                                            </div>
                                            @if (session('success'))
                                                <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div>
                                            @endif
                                            @if (session('error'))
                                                <div class="alert alert-danger">
                                                    {{ session('error') }}
                                                </div>
                                            @endif

                                            <a href="#" class="btn btn-primary mb-2" id="exportButton">Export to
                                                Excel</a>

                                        </div>
                                        <div class="d-block d-lg-flex align-items-center ">
                                            <div class="mx-1 mb-2 w-100" id="dateRangeContainer">
                                                <input type="text" id="dateRange" class="form-control"
                                                    placeholder="Select Date Range" />
                                            </div>
                                            <input type="text" id="newdateRange" class="form-control mb-2 me-1"
                                                placeholder="Select Date Range" />
                                            <button class="btn btn-primary w-100 ms-md--2 mb-2" id="filterButton"
                                                type="submit">Load
                                                Invoices</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table_head_1f446E">
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Invoice No.</th>
                                        <th>Order No.</th>
                                        {{-- <th>Mobile No.</th> --}}
                                        <th>Taxable Amount</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    {{-- @php
                                    $serialNumber = 1; // Initialize serial number counter
                                @endphp --}}
                                    {{--  <tr id="totalsRow">
                                        <td colspan="3"><strong>Total</strong></td>
                                        <td id="totalTaxableAmount"> <button type="button"
                                                class="btn btn-success shadow-none p-0 py-1 px-2">
                                                {{ $totalTaxableAmount }}</button>
                                        </td>
                                        <td  id="totalAmount"> <button type="button"
                                                class="btn btn-success shadow-none p-0 py-1 px-2">
                                                {{ $totalAmount }} </button>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>  --}}
                                </tbody>
                                <tbody id="invoiceRow">
                                    @foreach ($orders as $order)
                                    {{-- @dd($orders); --}}
                                    {{-- @if ($order->status === 'delivered') --}}
                                        {{-- @dd($order->toArray()) --}}
                                        {{-- @foreach ($order->orderItems as $orderItem) --}}

                                        <tr>
                                            {{-- <td>{{ $serialNumber++ }}</td> --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <?php


                                            $orderId = $order->id ?? null; // Ensure $order->id is set
                                            $bookingId =  $order->order_number;
                                            $incoiceid =  $order->invoice_number;
                                            ?>
                                            <td class="px-6 py-4">{{ $incoiceid }}</td>
                                            <td>{{ $bookingId }}</td>
                                            {{-- <td class="px-6 py-4">{{ $order->mobile }}</td> --}}
                                            <td>{{  $order->total_price /1.18 }}</td>
                                            <td>{{ $order->total_price }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-success shadow-none p-0 py-1 px-2">{{ $order->status }}</button>
                                            </td>
                                            <td>
                                                <a type="button" class="text-primary inv_btn" id="printReceipt"
                                                    href="{{ url('/admin/invoice/' . $order->id) }}">
                                                    <i class="fa-regular fa-file-lines"></i>
                                                </a>
                                            </td>

                                        </tr>
                                        {{-- @endforeach             --}}
                                        {{-- @endif --}}
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        <div class="no-records-found">No records found related to your search.</div>
                        @if ($orders->count() > 0)
                        <div class="pagination-container">
                            {{ $orders->links() }}
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //new datepicker code
            $('#newdateRange').daterangepicker({
                opens: 'left',
                maxDate: moment(),
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            document.getElementById('filterButton').addEventListener('click', function() {
                // const startDate = document.getElementById('startDate').value;
                // const endDate = document.getElementById('endDate').value;
                const dateRange = document.getElementById('newdateRange').value;
                const dates = dateRange.split(' - ');
                const startDate = moment(dates[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
                const endDate = moment(dates[1], 'DD/MM/YYYY').format('YYYY-MM-DD');
                fetch(`{{ route('indexfilter') }}?startDate=${startDate}&endDate=${endDate}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const totalTaxableAmount = data.totalTaxableAmount;
                        const totalAmount = data.totalAmount;
                        const orders = data.orders;

                        const tbody = document.querySelector('table tbody');
                        tbody.innerHTML = ''; // Clear existing rows

                        orders.forEach((order, index) => {
                            const bookingId =
                            order.order_number;
                            const taxableAmount =  order.total_price /
                                1.18;

                            const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${order.invoice_number}</td>
                        <td>${bookingId}</td>
                        <td>${taxableAmount}</td>
                        <td>${order.total_price}</td>
                        <td>
                            <button type="button" class="btn btn-success shadow-none p-0 py-1 px-2">${order.status}</button>
                        </td>
                        <td>
                            <a type="button" class="text-primary inv_btn" id="printReceipt" href="/admin/invoice/${order.id}">
                                <i class="fa-regular fa-file-lines"></i>
                            </a>
                        </td>
                    </tr>
                `;
                            tbody.insertAdjacentHTML('beforeend', row);
                        });

                        const totalsRow = `
                <tr id="totalsRow">
                    <td colspan="3"><strong>Total</strong></td>
                    <td id="totalTaxableAmount"><button type="button" class="btn btn-success shadow-none p-0 py-1 px-2"> ${totalTaxableAmount}</button></td>
                    <td id="totalAmount"><button type="button" class="btn btn-success shadow-none p-0 py-1 px-2"> ${totalAmount}</button></td>
                    <td colspan="2"></td>
                </tr>
            `;
                        tbody.insertAdjacentHTML('afterbegin', totalsRow);

                        if (orders.length === 0) {
                            document.querySelector('.no-records-found').style.display = 'block';
                        } else {
                            document.querySelector('.no-records-found').style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            });
            // });

            $(document).ready(function() {
                // document.getElementById('filterDate').addEventListener('change', function() {
                //     document.getElementById('filterButton').click();
                // });


                // // Initialize date range picker
                // $('#dateRange').daterangepicker({
                //     locale: {
                //         format: 'YYYY-MM-DD'
                //     }
                // });

                // // Toggle date range picker visibility on button click
                // $('#exportButton').click(function (e) {
                //     e.preventDefault();
                //     $('#dateRangeContainer').toggle();
                //     $('#dateRange').focus();
                // });

                // // Handle export logic after date range selection
                // $('#dateRange').on('apply.daterangepicker', function (ev, picker) {
                //     let dateRange = $(this).val();
                //     let url = '{{ url('/admin/orders/export') }}' + '?date_range=' + dateRange;
                //     window.location.href = url;
                // });

                // Initialize date range picker
                // $('#dateRange').daterangepicker({
                //     opens: 'left'
                // });

                // // Click event for export button
                // $('#exportButton').click(function(e) {
                //     e.preventDefault(); // Prevent default action of the button
                //     $('#dateRangeContainer').show(); // Show the date range input
                //     $('#dateRange').focus(); // Focus on the date range input to trigger the date picker
                // });

                // // Initialize the date picker when the input is focused
                // $('#dateRange').focus(function() {
                //     $(this).daterangepicker({
                //         opens: 'left'
                //     });
                // });




                // Initialize date range picker on document ready
                $('#dateRange').daterangepicker({
                    opens: 'left',
                    maxDate: moment(),
                    locale: {
                    format: 'DD/MM/YYYY'
                }
                });

                // Click event for export button
                $('#exportButton').click(function(e) {
                    e.preventDefault(); // Prevent default action of the button
                    $('#dateRangeContainer').show(); // Show the date range input
                    // $('#dateRange').focus(); // Focus on the date range input to trigger the date picker
                });

                // Handle export logic after date range selection
                $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                    let dateRange = $(this).val();
                    let url = '{{ url('/admin/orders/export') }}' + '?date_range=' + dateRange;
                    window.location.href = url;
                });




                // $('#invoiceSearch').keyup(function() {
                //     var searchText = $(this).val().toLowerCase();
                //     var noRecord = true;
                //     $('tbody tr').each(function() {
                //         var InvNumber = $(this).find('td:nth-child(2)').text()
                //             .toLowerCase();
                //         var OrderNo = $(this).find('td:nth-child(3)').text()
                //             .toLowerCase();
                //         var TaxNo = $(this).find('td:nth-child(4)').text()
                //             .toLowerCase();
                //         var TotalAmount = $(this).find('td:nth-child(5)').text()
                //             .toLowerCase();
                //         if (InvNumber.indexOf(searchText) === -1 &&
                //             OrderNo.indexOf(searchText) === -1 &&
                //             TaxNo.indexOf(searchText) === -1 &&
                //             TotalAmount.indexOf(searchText) === -1) {
                //             $(this).hide();
                //         } else {
                //             $(this).show();
                //             noRecord = false;
                //         }
                //     });
                //     if (noRecord) {
                //         $('.no-records-found').show();
                //         $('.pagination-container').hide(); // Hide pagination
                //     } else {
                //         $('.no-records-found').hide();
                //         $('.pagination-container').show(); // Show pagination
                //     }
                // });

                $('#invoiceSearch').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $.ajax({
                url: '{{ route('invoice') }}',
                type: 'GET',
                data: {
                    search: searchText
                },
                success: function(response) {
                    var orders = response.orders;
                    var pagination = response.pagination;
                    var totalTaxableAmount = response.totalTaxableAmount;
                    var totalAmount = response.totalAmount;
                    // var tbody = document.getElementById('invoiceRow');
                    var tbody = $('#invoiceRow');
                    tbody.empty();

                    if (orders.length === 0) {
                    $('.no-records-found').show();
                    $('.pagination-container').hide();
                } else {
                    $('.no-records-found').hide();
                    $('.pagination-container').show().html(pagination);
                }

                $.each(orders, function(index, order) {
                    var row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${order.invoice_number}</td>
                            <td>${order.order_number}</td>
                            <td>${(order.total_price/1.18).toFixed(2)}</td>
                            <td>${order.total_price.toFixed(2)}</td>
                            <td>
                                <button type="button" class="btn btn-success shadow-none p-0 py-1 px-2">${order.status}</button>
                            </td>
                            <td>
                                <a type="button" class="text-primary inv_btn" id="printReceipt" href="/admin/invoice/${order.id}">
                                    <i class="fa-regular fa-file-lines"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                    });
                }
            });
        });
            });
        });
    </script>
@endsection
