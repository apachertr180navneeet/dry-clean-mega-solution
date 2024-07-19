@if (!Auth::user())
    {{-- <script type="text/javascript">
        window.location = "{{ route('login') }}";
    </script> --}}
    @php
        $errorMessage = 'Please contact the superadmin for access.';
        return redirect()->route('login')->withErrors([$errorMessage]);
    @endphp
@else
@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
    <div class="card mb-3">
                <div class="card-body p-3">
    <h4 class="fw-bold mb-0"><span class="text-muted fw-light"> User Profile /</span> Profile</h4>
</div>
</div>
    @php
$loginname = Auth::user()->name;
$loginemail = Auth::user()->email;
$loginmobile = Auth::user()->mobile;
    @endphp
    <!-- Header -->
    <div class="row">
       
        <div class="col-xl-6 col-md-6 ">
            <div class="card mb-4">
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>Welcome </h4>
                                <h6>{{ !empty($loginname) ? $loginname : '' }}</h6>
                            </div>
                            <div class="user-profile-info">
                                <a href="{{ route('edit.profile', ['id' => Auth::user()->id]) }}" class="btn btn-primary">
                                    <i class="ti ti-user-check me-1"></i>Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 ">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <small class="card-text text-uppercase">About</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-user"></i><span class="fw-bold mx-2">Full Name:</span> <span>{{ !empty($loginname) ? $loginname : '' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-check"></i><span class="fw-bold mx-2">Email</span> <span>{{ !empty($loginemail) ? $loginemail : '' }}</span>
                        </li>
                    </ul>
                    {{-- <small class="card-text text-uppercase">Contacts</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-phone-call"></i><span class="fw-bold mx-2">Contact:</span>
                            <span>{{ !empty($loginmobile) ? $loginmobile : '' }}</span>
                        </li>
                    </ul> --}}
                </div>
            </div>
            <!--/ About User -->
        </div>
    </div>

</div>
</div>

@endsection
@endif