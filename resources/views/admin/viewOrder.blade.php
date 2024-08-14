@extends('backend.layouts.app')
@section('content')
<style>
   .pagination-container{
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg{
        width: 30px;
    }

    .pagination-container nav .justify-between{
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none; /* Hidden by default */
    }
</style>
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp">
            <div class="card">
                <div class="card-body">
                    <div class="client_list_heading_area">
                        <h4>Order List</h4>
                        <div class="client_list_heading_search_area">
                            <i class="menu-icon tf-icons ti ti-search"></i>
                            <input type="search" class="form-control" placeholder="Searching ..." id="orderSearch">
                        </div>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table table-hover table-striped">
                            <thead class="table_head_1f446E">
                                <tr>
                                    <th>S. No.</th>
                                    <th>Booking ID</th>
                                    <th>Client Name</th>
                                    <th>Mobile Number</th>
                                    <th>No. of items</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orders->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                @else
                                    @php
                                        $serialNumber = 1; // Initialize serial number counter
                                    @endphp
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $serialNumber++ }}</td>
                                            <td>
                                                <?php
                                                    // Format the order ID
                                                    // $bookingId = 'ORD-' . date('Y') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT);
                                                    $bookingId =  $order->order_number;
                                                ?>
                                                {{ $bookingId }}
                                            </td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->mobile }}</td>
                                            <td>{{ $order->total_qty }}</td>
                                            <td>
                                                <div class="Client_table_action_area">
                                                    <a href="{{ url('/admin/show-order/' . $order->id) }}" class="btn Client_table_action_icon px-2">
                                                        <i class="tf-icons ti ti-eye"></i>
                                                    </a>
                                                    @if ($order->payment_status !== 'Paid' || $order->item_status !== 'delivered')
                                                        <button class="btn Client_table_action_icon px-2"
                                                            onclick="window.location='{{ route('order.edit', $order->id) }}'">
                                                            <i class="tf-icons ti ti-pencil"></i>
                                                        </button>
                                                        <div class="btn-group dropstart order_list_action_menu_dropmenu">
                                                            <button type="button" class="btn Client_table_action_icon dropdown-toggle px-2"
                                                                data-bs-toggle="dropdown" aria-expanded="false" data-order_id="{{ $order->id }}">
                                                                <i class="tf-icons ti ti-layout-grid"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                @if ($order->payment_status !== 'Paid')
                                                                    <li>
                                                                        <a class="dropdown-item set_ord_btn" data-bs-toggle="modal" data-bs-target="#SettleOrder" data-order_id="{{ $order->id }}">
                                                                            Settle Order
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                {{-- <li> <button type="button" class="btn mark-as-delivered-btn">Deliver</button></li> --}}
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <button type="button" class="btn Client_table_action_icon px-2 rcp_btn" data-order_id="{{ $order->id }}">
                                                        <i class="fas fa-list"></i>
                                                    </button>
                                                    <button class="btn Client_table_action_icon px-2 delete_order_btn" data-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#delete_order">
                                                        <i class="tf-icons ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                    <div class="no-records-found">No records found related to your search.</div>
                    @if ($orders->count() > 0)
                        <div class="pagination-container">
                            {{ $orders->links() }}
                        </div>
                    @endif

                    <!-- Settle Order -->
                    <div class="modal fade" id="SettleOrder" tabindex="-1" aria-labelledby="SettleOrderLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="SettleOrderLabel">Payment Options</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="settleOrderForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="paymentOption"
                                                        id="cashOn" value="cash">
                                                    <label class="form-check-label" for="cashOn">
                                                        <i class="fa-solid fa-money-bill-wave me-2"></i> Cash On
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="paymentOption"
                                                        id="upiPayment" value="Online">
                                                    <label class="form-check-label" for="upiPayment">
                                                        <i class="fa-solid fa-angles-right me-2"></i> UPI Payment
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12"> <span id="paymentError" class="text-danger"></span></div>
                                        </div>


                                    </form>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="settleAndDeliverButton">Settle &
                                        Deliver</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- end -->

                    <!-- Delete Order -->
                    <div class="modal fade" id="delete_order" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this order ?
                                </div>
                                <form method="post" id="deleteOrderForm">
                                    @csrf <!-- Include CSRF token -->
                                    @method('GET')
                                    <input type="hidden" id="order_del_id" name="service_id">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger" id="confirm_delete">Delete</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- end -->
                    {{-- print model --}}
                    <div class="modal fade" id="yes" tabindex="-1" aria-labelledby="yesLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <h5>Please Select from the Following Options</h5>
                                    <!-- Add your modal content here -->
                                    {{-- <a type="button" class="btn btn-success mb-2" id="sendWhatsAppMessage">
                                        <i class="fab fa-whatsapp me-2"></i> Send On WhatsApp
                                    </a> --}}
                                    <a type="button" class="btn btn-primary mb-2" id="printReceipt">
                                        <i class="fa-solid fa-file-invoice me-2"></i> Print Receipt
                                    </a>
                                    <a type="button" class="btn btn-success mb-2" id="printTags"><i
                                            class="fa-solid fa-tag me-2"></i> Print Tag</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- end --}}

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).ready(function() {
            // $('#orderSearch').keyup(function () {
            //     var searchText = $(this).val().toLowerCase();
            //     var noRecord = true;
            //     $('tbody tr').each(function () {
            //         var bookingId = $(this).find('td:nth-child(2)').text()
            //             .toLowerCase();
            //         var clientName = $(this).find('td:nth-child(3)').text()
            //             .toLowerCase();
            //         var clientNumber = $(this).find('td:nth-child(4)').text()
            //             .toLowerCase();
            //         if (bookingId.indexOf(searchText) === -1 &&
            //             clientName.indexOf(searchText) === -1 &&
            //             clientNumber.indexOf(searchText) === -1) {
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

            $('#orderSearch').on('keyup', function() {
                var searchText = $(this).val().toLowerCase();
                $.ajax({
                    url: '/admin/view-order', // The route for searching orders
                    type: 'GET',
                    data: {
                        search: searchText
                    },
                    success: function(response) {
                        console.log("new", response);
                        var orders = response.orders;
                        var pagination = response.pagination;
                        var tbody = $('tbody');
                        tbody.empty();
                        var serialNumber = 1;
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
                    <td>${serialNumber++}</td>
                    <td>${order.order_number}</td>
                    <td>${order.name}</td>
                    <td>${order.mobile}</td>
                    <td>${order.total_qty}</td>
                    <td>
                        <div class="Client_table_action_area">
                            <a href="/admin/show-order/${order.id}" class="btn Client_table_action_icon px-2">
                                <i class="tf-icons ti ti-eye"></i>
                            </a>
                            ${order.payment_status !== 'Paid' || order.item_status !== 'delivered' ? `
                                    <button class="btn Client_table_action_icon px-2" onclick="window.location='/admin/edit-order/${order.id}'">
                                        <i class="tf-icons ti ti-pencil"></i>
                                    </button>
                                    <div class="btn-group dropstart order_list_action_menu_dropmenu">
                                        <button type="button" class="btn Client_table_action_icon dropdown-toggle px-2" data-bs-toggle="dropdown" aria-expanded="false" data-order_id="${order.id}">
                                            <i class="tf-icons ti ti-layout-grid"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            ${order.payment_status !== 'Paid' ? `
                                    <li>
                                        <a class="dropdown-item set_ord_btn" data-bs-toggle="modal" data-bs-target="#SettleOrder" data-order_id="${order.id}">
                                            Settle Order
                                        </a>
                                    </li>` : ''}
                                        </ul>
                                    </div>` : ''}
                            <button class="btn Client_table_action_icon px-2 rcp_btn" data-order_id="${order.id}">
                                <i class="fas fa-list"></i>
                            </button>
                            <button class="btn Client_table_action_icon px-2 delete_order_btn" data-id="${order.id}" data-bs-toggle="modal" data-bs-target="#delete_order">
                                <i class="tf-icons ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
                            tbody.append(row);
                        });
                        attachEventHandlers();
                        // Reattach event handlers for buttons (if any)
                    }
                });
            });

            function attachEventHandlers() {
                // for delete service
                $('.delete_order_btn').click(function() {
                    var orderId = $(this).data('id');
                    $('#order_del_id').val(
                        orderId);
                    $('#delete_order').modal('show');
                });

                $('#confirm_delete').click(function() {
                    var formData = $('#deleteOrderForm').serialize();
                    var orderId = $('#order_del_id').val();

                    $.ajax({
                        url: '/admin/delete-order/' + orderId,
                        type: 'GET',
                        data: formData,
                        success: function(response) {
                            $('#delete_order').modal('hide');
                            window.location.reload();
                        },
                        error: function(xhr) {
                            $('#delete_order').modal('hide');
                        }
                    });
                });



                // for id add and show print model
                $('.rcp_btn').click(function() {
                    // alert("Hello Shaktiman");
                    var id = $(this).data('order_id');
                    // alert(id)
;
                    // $('#sendWhatsAppMessage').attr('href', '/send-wh-message/' + id);
                    $('#printReceipt').attr('href', '/admin/receipt/' + id);
                    $('#printTags').attr('href', '/admin/tagslist/' + id);
                    $('#yes').modal('show');
                });


                $('#SettleOrder').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var orderId = button.data(
                    'order_id'); // Extract order ID from data-* attributes
                    var modal = $(this);
                    modal.attr('data-order-id',
                    orderId); // Set the order ID in the modal's data attribute
                });






            }
            attachEventHandlers();
            $('#settleAndDeliverButton').on('click', function() {
                    var modal = $('#SettleOrder');
                    var orderId = modal.data('order-id');
                    var paymentOption = $('input[name="paymentOption"]:checked').val();

                    if (!paymentOption) {
                        // alert('Please select a payment option.');
                        $('#paymentError').text('Please select a payment option.');
                        return;
                    }

                    $.ajax({
                        url: '/admin/settle-and-deliver-order/' + orderId,
                        method: 'POST',
                        data: {
                            paymentType: paymentOption,
                            _token: $('meta[name="csrf-token"]').attr(
                                    'content'
                                    ) // Assuming you have a CSRF token meta tag
                        },
                        success: function(response) {
                            // Handle success, e.g., show a success message and close the modal
                            alert('Order settled and delivered successfully.');
                            modal.modal('hide');
                            // location
                            //     .reload(); // Optional: Reload the page to update the order status
                            window.location.href = '{{ route('invoice') }}';
                        },
                        error: function(xhr) {
                            // Handle error
                            alert('Error: ' + xhr.responseText);
                        }
                    });
                });
            // Cash On Button Click Handler
            $('.cash-on-btn').on('click', function() {
                    var modal = $('#SettleOrder');
                    var orderId = modal.attr('data-order-id');
                    console.log('Cash On for order ID:', orderId);
                    // Add your logic here to proceed with Cash On delivery
                    // For example: You can submit a form or make an AJAX request with the orderId
                    $('#CashOnConfirmation').modal('show');
                });

                // Upi Payment Button Click Handler
                $('.upi-payment-btn').on('click', function() {
                    var modal = $('#SettleOrder');
                    var orderId = modal.attr('data-order-id');
                    console.log('UPI Payment for order ID:', orderId);
                    // Add your logic here to proceed with UPI payment
                    // For example: You can submit a form or make an AJAX request with the orderId
                    $('#UpiConfirmation').modal('show');
                });

                // Proceed with Cash On Delivery
                $('.proceed-cash-on').on('click', function() {
                    var modal = $('#SettleOrder');
                    var orderId = modal.attr('data-order-id');
                    console.log('Proceed with Cash On Delivery for order ID:', orderId);
                    // Add your logic here to proceed with Cash On delivery
                    // For example: You can submit a form or make an AJAX request with the orderId
                    $('#CashOnConfirmation').modal('hide');
                });

                // Proceed with Upi Payment
                $('.proceed-upi-payment').on('click', function() {
                    var modal = $('#SettleOrder');
                    var orderId = modal.attr('data-order-id');
                    console.log('Proceed with UPI Payment for order ID:', orderId);
                    // Add your logic here to proceed with UPI payment
                    // For example: You can submit a form or make an AJAX request with the orderId
                    $('#UpiConfirmation').modal('hide');
                });
        });

    });

</script>
