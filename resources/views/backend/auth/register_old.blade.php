@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper mt-5">
    <div class="container-xxl flex-grow-1 container-p-y mt-5">
        <!-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;add /</span> Blog</h4> -->
        <div class="row">
            <!-- Browser Default -->
            <div class="col-md-2 mb-4 mb-md-0"></div>
            <div class="col-md-10 mb-4 mb-md-0">
                <div class="card">
                    <h5 class="card-header">Add Blog</h5>
                    <div class="card-body">
                        <form id="formAuthentication" class="mb-3" action="{{ !empty($user->id) ? route('profile.edit.post',['id'=>$user->id]) : route('register.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="name" placeholder="Enter your username" value="{{ !empty($user->name) ? $user->name : old('name') }}" autofocus />
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile No</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your Mobile No" value="{{ !empty($user->mobile) ? $user->mobile : old('mobile') }}" autofocus />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ !empty($user->email) ? $user->email : old('email') }}" />
                            </div>
                            @if (empty($user->id))
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            @endif
                            <button class="btn btn-primary d-grid w-100">{{ !empty($user->id) ? 'Update' : 'Submit' }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Browser Default -->
        </div>
    </div>
</div>
@endsection