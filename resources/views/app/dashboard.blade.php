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
<div class="layout-page mt-4">
  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <!-- Earning Reports -->
        <div class="col-lg-12 mb-4">
          <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
              <div class="card-title mb-0">
                <h5 class="mb-0">Total Reports</h5>
                <small class="text-muted">Overview</small>

              </div>
              <!-- </div> -->
            </div>
            <div class="card-body">
              <div class="border rounded p-3 mt-5">
                <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                    <div class="d-flex gap-2 align-items-center">
                      <div class="badge rounded bg-label-primary p-1">
                        <i class="menu-icon tf-icons ti ti-users"></i>
                      </div>
                      <h6 class="mb-0">Clients</h6>
                    </div>
                    <h4 class="my-2 pt-1">@if($clientCounts > 1){{ $clientCounts}} Clients @else {{ $clientCounts}} Client @endif</h4>
                    <div class="progress w-75" style="height: 4px">
                      <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

        </div>
        <hr>
        <div class="col-lg-12 mb-4">
          <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
              {{-- <div class="card-title mb-0">
                <h5 class="mb-0">Total Reports</h5>
                <small class="text-muted">Overview</small>

              </div> --}}
              <!-- </div> -->
            </div>
            <div class="card-body">
              <div class="border rounded p-3 mt-5">
                <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                    <div class="d-flex gap-2 align-items-center">
                      <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                      <h6 class="mb-0">Today's Orders</h6>
                    </div>
                    <h4 class="my-2 pt-1">@if($orderCounts > 1){{ $orderCounts}} Orders @else {{ $orderCounts}} Order @endif </h4>
                    <div class="progress w-75" style="height: 4px">
                      <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <hr>
                  <div class="table-responsive-sm">
                    <table class="table table-hover table-striped">
                        <thead class="table_head_1f446E">
                            <tr>
                                <th>S. No.</th>
                                <th>Booking ID</th>
                                <th>Client Name</th>
                                <th>Invoice Value</th>
                                <th>No. of items</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                          @php
                              $serialNumber = 1; // Initialize serial number counter
                          @endphp
                          @foreach ($orders as $order)
                          <tr>
                              <td>{{ $serialNumber++ }}</td>
                              <td>
                                @php
                                $invoicenumber = $order->order_number;
                                $bookingId =  $invoicenumber;
                            @endphp
                                  {{ $bookingId }}
                              </td>
                              <td>{{ $order->name }}</td>
                              <td>{{ $order->total_price }}</td>
                              <td>{{ $order->total_qty }}</td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                </div>
                {{-- @if ($orders->count() > 0) --}}
                        <div class="pagination-container">
                          <a type="button" class="btn btn-primary text-white" id="backButton" href="{{route('viewOrder')}}">
                            View All
                        </a>
                        </div>
                    {{-- @endif --}}

                </div>
              </div>
            </div>
          </div>

        </div>
        <hr>
        <div class="col-lg-12 mb-4">
          <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
              {{-- <div class="card-title mb-0">
                <h5 class="mb-0">Total Reports</h5>
                <small class="text-muted">Overview</small>

              </div> --}}
              <!-- </div> -->
            </div>
            <div class="card-body">
              <div class="border rounded p-3 mt-5">
                <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                    <div class="d-flex gap-2 align-items-center">
                      <div class="badge rounded bg-label-success p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                      <h6 class="mb-0">10 Days Pending Orders</h6>
                    </div>
                    <h4 class="my-2 pt-1">
                      @if($pendingOrderCounts > 1)
                          {{ $pendingOrderCounts }} Orders
                      @else
                          {{ $pendingOrderCounts }} Order
                      @endif
                  </h4>
                    <div class="progress w-75" style="height: 4px">
                      <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                  </div>
                  <hr>
                  <div class="table-responsive-sm">
                    {{-- @dd($pendingOrders); --}}
                    <table class="table table-hover table-striped">
                        <thead class="table_head_1f446E">
                            <tr>
                                <th>S. No.</th>
                                <th>Booking ID</th>
                                <th>Client Name</th>
                                <th>Invoice Value</th>
                                <th>No. of items</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                          @php
                              $serialNumber = 1;
                          @endphp
                          @foreach ($pendingOrders as $order)
                              <tr>
                                  <td>{{ $serialNumber++ }}</td>
                                  <td>
                                    @php
                                    $invoicenumber = $order->order_number;
                                    $bookingId =  $invoicenumber;
                                @endphp
                                      {{ $bookingId }}
                                  </td>
                                  <td>{{ $order->name }}</td>
                                  <td>{{ $order->total_price }}</td>
                                  <td>{{ $order->total_qty }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>
                </div>
                {{-- @if ($pendingOrders->count() > 0) --}}
                <div class="pagination-container">
                  {{-- <button type="button" class="btn btn-primary">text-primary d-flex align-items-center View All</button> --}}
                  <a type="button" class="btn btn-primary text-white" id="backButton" href="{{route('viewOrder')}}">
                    View All
                </a>
                </div>
                    {{-- @endif --}}

                </div>
              </div>
            </div>
          </div>

        </div>

        <br>
        <br>
        <hr>
        <br>

      <br>
      <br>
      <br>

        <!--/ Earning Reports -->
      </div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
  </div>
  <!-- Content wrapper -->
</div>
@endsection

