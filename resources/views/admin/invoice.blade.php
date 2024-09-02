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

                                            {{-- <div class="client_list_heading_search_area me-2 mb-2">
                                                <i class="menu-icon tf-icons ti ti-search"></i>
                                                <input type="search" class="form-control" placeholder="Searching ..."
                                                    id="invoiceSearch">
                                            </div> --}}
                                            <div class="mx-1 mb-2 w-100" id="dateRangeContainer">
                                                <input type="text" id="dateRange" class="form-control"
                                                    placeholder="Select Date Range" />
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
                                        <th>Taxable Amount</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody id="invoiceRow">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <?php
                                            $orderId = $order->id ?? null; // Ensure $order->id is set
                                            $bookingId =  $order->order_number;
                                            $incoiceid =  $order->invoice_number;
                                            $invoiceNumber = $incoiceid;
                                            ?>
                                            <td class="px-6 py-4">{{ $invoiceNumber }}</td>
                                            <td>{{ $bookingId }}</td>
                                            <td>{{  number_format($order->total_price /1.18 , 2) }}</td>
                                            <td>{{ number_format($order->total_price, 2) }}</td>
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
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        <div class="no-records-found">No records found related to your search.</div>
                        @if ($orders->count() > 0)
                        <div class="pagination-container" id="hide-pagination">
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
            document.getElementById('filterButton').addEventListener('click', function () {
                const dateRange = document.getElementById('newdateRange').value;

                // Check if dateRange is properly formatted
                if (!dateRange || !dateRange.includes(' - ')) {
                    console.error('Invalid date range format.');
                    return;
                }

                const dates = dateRange.split(' - ');
                const startDate = moment(dates[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
                const endDate = moment(dates[1], 'DD/MM/YYYY').format('YYYY-MM-DD');

                fetch(`{{ route('indexfilter') }}?startDate=${startDate}&endDate=${endDate}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetched data:', data);

                    const totalTaxableAmount = data.totalTaxableAmount;
                    const totalAmount = data.totalAmount;
                    const orders = data.orders;

                    const tbody = document.querySelector('table tbody');
                    tbody.innerHTML = ''; // Clear existing rows

                    if (orders.length === 0) {
                        document.querySelector('.no-records-found').style.display = 'block';
                    } else {
                        document.querySelector('.no-records-found').style.display = 'none';

                        orders.forEach((order, index) => {
                            const bookingId = order.order_number;
                            const taxableAmount = order.total_price / 1.18;
                            const invoice_number = order.invoice_number;
                            const formattedInvoiceNumber = String(invoice_number).padStart(3, '0');

                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${formattedInvoiceNumber}</td>
                                    <td>${bookingId}</td>
                                    <td>${taxableAmount.toFixed(2)}</td>
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
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
            });

            // });

            $(document).ready(function() {
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
                            var tbody = $('#invoiceRow');
                            tbody.empty();

                            if (orders.length === 0) {
                                $('.no-records-found').show();
                                $('#hide-pagination').addClass('d-none');
                            } else {
                                $('.no-records-found').hide();
                                $('#hide-pagination').addClass('d-none');
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
