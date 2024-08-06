@extends('backend.layouts.app')
@section('content')
    <style>
        .pagination-container {
            display: flex;
            justify-content: end;
            margin-top: 20px;
        }

        .pagination-container svg {
            width: 30px;
        }

        .pagination-container nav .justify-between {
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
                            <h4>Payment</h4>
                            <div class="client_list_heading_search_area">
                                <form action="{{ route('payment') }}" method="GET" class="d-flex">
                                    <i class="menu-icon tf-icons ti ti-search" id="resetSearch"></i>
                                    <input type="search" name="search" class="form-control" placeholder="Searching ..."
                                        id="paymentSearch" value="{{ request()->input('search') }}">
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
                                            <td> {{ $payment->order_number }} </td>
                                            <td>{{ \Carbon\Carbon::parse($payment->updated_at)->format('j F, Y') }}</td>
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
        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                $('#paymentSearch').on('keyup', function() {
                    var searchText = $(this).val().toLowerCase();
                    $.ajax({
                        url: '{{ route('payment') }}',
                        type: 'GET',
                        data: {
                            search: searchText
                        },
                        success: function(response) {
                            var payments = response.payments;
                            var pagination = response.pagination;
                            var tbody = $('tbody');
                            tbody.empty();
                            var serialNumber = 1;

                            if (payments.length === 0) {
                                $('.no-records-found').show();
                                $('.pagination-container').hide();
                            } else {
                                $('.no-records-found').hide();
                                $('.pagination-container').show().html(pagination);
                            }

                            $.each(payments, function(index, payment) {
                                var row = `
                            <tr>
                                <td>${serialNumber++}</td>
                                <td>${payment.order_number}</td> 
                                <td>${payment.updated_at}</td> 
                                <td>${payment.payment_type}</td>
                                <td>₹ ${payment.total_amount}</td>
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
