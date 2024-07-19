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
                    <h5 class="card-header">Add Role</h5>
                    <div class="card-body">
                        <form class="needs-validation" method="post"  action="{{ route('permission.post') }}" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="validationCustom01">Permission</label>
                                    <input type="text" class="form-control" name="permission" id="validationCustom01" placeholder="Permission Name" required>
                                    <div class="invalid-feedback">
                                        Please provide a Permission Name.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="validationCustom01">Type Id</label>
                                    <input type="text" class="form-control" name="type_id" id="validationCustom21" placeholder="Type Id " required>
                                    <div class="invalid-feedback">
                                        Please provide a Permission Id.
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary text-white" type="submit">Add Permission</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection