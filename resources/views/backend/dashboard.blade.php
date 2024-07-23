@extends('backend.layouts.app')

@section('content')
<div class="layout-page mt-4">
  <div class="content-wrapper">
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

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
                    <h4 class="my-2 pt-1">{{ $clientCounts }} {{ Str::plural('Client', $clientCounts) }}</h4>
                    <div class="progress w-75" style="height: 4px">
                      <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--/ Earning Reports -->
      </div>
    </div>
    <div class="content-backdrop fade"></div>
  </div>
</div>
@endsection
