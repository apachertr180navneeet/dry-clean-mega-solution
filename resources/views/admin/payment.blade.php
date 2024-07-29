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
                        <h4>Payment</h4>
                        <div class="client_list_heading_search_area">
                            <form action="{{ route('payment') }}" method="GET" class="d-flex">
                                <i class="menu-icon tf-icons ti ti-search" id="resetSearch"></i>
                                <input type="search" name="search" class="form-control" placeholder="Searching ..." id="paymentSearch" value="{{ request()->input('search') }}">
                                <button type="submit" class="btn btn-primary ms-2">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table_head_1f446E">
                                <tr>
                                    <th>S. No.</th>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th>Payment Type</th>
                                    {{-- <th>Payment Type</th> --}}
                                    {{-- <th>Cash Amount</th> --}}
                                    {{-- <th>Upi Amount</th> --}}
                                    <th>Total Amount</th> 
                                </tr>
                                {{-- <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th> --}}
                                    {{-- <th>₹ 1000</th> --}}
                                    {{-- <th>₹ 500</th>
                                    <th>₹ 1500</th> --}}
                                {{-- </tr> --}}
                            </thead>
                            <tbody>
                                @php
                                    $serialNumber = 1; // Initialize serial number counter
                                @endphp
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $serialNumber++ }}</td>
                                        <td>
                                            <?php
                                                // Format the booking ID
                                                $bookingId = 'ORDER-' . str_pad($payment->order_id, 3, '0', STR_PAD_LEFT);
                                            ?>
                                            {{ $bookingId }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('j F, Y') }}</td>
                                        <td>{{ $payment->payment_type }}</td>
                                        <td>₹ {{ $payment->total_amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="no-records-found">No records found related to your search.</div>
                    @if ($payments->count() > 0)
                        <div class="pagination-container">
                            {{ $payments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).ready(function () {
            $('#paymentSearch').keyup(function () {
                var searchText = $(this).val().toLowerCase();
                var rows = $('tbody tr');
                var noRecord = true;
                rows.each(function () {
                    var bookingId = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var date = $(this).find('td:nth-child(3)').text().toLowerCase();
                    var paymentType = $(this).find('td:nth-child(4)').text().toLowerCase();
                    var totalAmount = $(this).find('td:nth-child(5)').text().toLowerCase();
                    if (bookingId.indexOf(searchText) === -1 &&
                        date.indexOf(searchText) === -1 &&
                        paymentType.indexOf(searchText) === -1 &&
                        totalAmount.indexOf(searchText) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                        noRecord = false;
                    }
                });
                if (noRecord) {
                    $('.no-records-found').show();
                    $('.pagination-container').hide(); // Hide pagination
                } else {
                    $('.no-records-found').hide();
                    $('.pagination-container').show(); // Show pagination
                }
            });

            $('#resetSearch').click(function () {
                $('#paymentSearch').val(''); // Clear search input
                $('tbody tr').show(); // Show all rows
                $('.no-records-found').hide(); // Hide "no records found" message
                $('.pagination-container').show(); // Show pagination
            });
        });
    });
</script>
@endsection
